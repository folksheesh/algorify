<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kursus;
use Spatie\Permission\Models\Role;

class PengajarController extends Controller
{
    public function index()
    {
        // Get all instructors (users with 'pengajar' role) with their courses count
        $pengajar = User::role('pengajar')
            ->withCount('kursus')
            ->with('kursus')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all courses for dropdown
        $kursus = Kursus::orderBy('judul', 'asc')->get();
        
        return view('admin.pengajar.index', compact('pengajar', 'kursus'));
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'profesi' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'address' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive',
            'kursus_id' => 'required|exists:kursus,id',
        ]);

        // Handle file upload
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/pengajar', $filename);
            $validated['foto_profil'] = 'storage/pengajar/' . $filename;
        }

        // Create user with default password
        $validated['password'] = bcrypt('password123');
        $validated['tanggal_daftar'] = now();
        
        $user = User::create($validated);
        
        // Assign pengajar role
        $user->assignRole('pengajar');

        // Update kursus user_id (hasMany relationship)
        if ($request->kursus_id) {
            Kursus::where('id', $request->kursus_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.pengajar.index')->with('success', 'Pengajar berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'profesi' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'address' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive',
            'kursus_id' => 'required|exists:kursus,id',
        ]);

        // Handle file upload
        if ($request->hasFile('foto_profil')) {
            // Delete old file if exists
            if ($user->foto_profil && file_exists(public_path($user->foto_profil))) {
                unlink(public_path($user->foto_profil));
            }

            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/pengajar', $filename);
            $validated['foto_profil'] = 'storage/pengajar/' . $filename;
        }

        $user->update($validated);

        // Update kursus user_id (hasMany relationship)
        if ($request->kursus_id) {
            // Remove old association
            Kursus::where('user_id', $user->id)->update(['user_id' => null]);
            // Set new association
            Kursus::where('id', $request->kursus_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.pengajar.index')->with('success', 'Pengajar berhasil diperbarui');
    }
}
