<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kursus;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PelatihanController extends Controller
{
    public function index()
    {
        // Get all courses with their related data
        $kursus = Kursus::with(['pengajar', 'modul', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all users with pengajar role
        $pengajars = User::role('pengajar')->orWhere('id', Auth::id())->get();
        
        return view('admin.pelatihan.index', compact('kursus', 'pengajars'));
    }

    public function show($id)
    {
        $kursus = Kursus::with(['pengajar', 'modul' => function($query) {
            $query->orderBy('urutan', 'asc')->with([
                'materi' => function($q) {
                    $q->orderBy('urutan', 'asc');
                },
                'video' => function($q) {
                    $q->orderBy('urutan', 'asc');
                }
            ]);
        }])->findOrFail($id);
        
        return view('admin.pelatihan.show', compact('kursus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:programming,design,business,marketing,data_science,other',
            'tipe_kursus' => 'required|in:online,hybrid,offline',
            'deskripsi' => 'nullable|string',
            'pengajar_id' => 'required|exists:users,id',
            'durasi' => 'required|string|max:100',
            'harga' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        // Set the instructor from form
        $validated['user_id'] = $request->pengajar_id;
        
        // Get pengajar name and store it
        $pengajar = User::find($request->pengajar_id);
        $validated['pengajar'] = $pengajar->name;
        
        // Map tipe_kursus to status for database
        $validated['status'] = 'published'; // Default status

        Kursus::create($validated);

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Kursus berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kursus = Kursus::findOrFail($id);
        return response()->json($kursus);
    }

    public function update(Request $request, $id)
    {
        $kursus = Kursus::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:programming,design,business,marketing,data_science,other',
            'tipe_kursus' => 'required|in:online,hybrid,offline',
            'deskripsi' => 'nullable|string',
            'pengajar_id' => 'required|exists:users,id',
            'durasi' => 'required|string|max:100',
            'harga' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($kursus->thumbnail) {
                Storage::disk('public')->delete($kursus->thumbnail);
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }
        
        // Set the instructor from form
        $validated['user_id'] = $request->pengajar_id;
        
        // Get pengajar name and store it
        $pengajar = User::find($request->pengajar_id);
        $validated['pengajar'] = $pengajar->name;
        
        // Map tipe_kursus to status for database
        $validated['status'] = $kursus->status; // Keep existing status

        $kursus->update($validated);

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Kursus berhasil diupdate');
    }

    public function destroy($id)
    {
        $kursus = Kursus::findOrFail($id);

        // Delete thumbnail if exists
        if ($kursus->thumbnail) {
            Storage::disk('public')->delete($kursus->thumbnail);
        }

        $kursus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil dihapus'
        ]);
    }
}
