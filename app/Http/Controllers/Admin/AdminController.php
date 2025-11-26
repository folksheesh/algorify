<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * ADMIN CONTROLLER
 * Controller untuk mengelola data admin
 * Fitur: CRUD admin, validasi data
 */
class AdminController extends Controller
{
    /**
     * Menampilkan halaman index data admin
     */
    public function index()
    {
        return view('admin.admin.index');
    }

    /**
     * Mengambil data admin dalam format JSON untuk AJAX request
     */
    public function getData(Request $request)
    {
        $query = User::role('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($query);
    }

    /**
     * Menyimpan data admin baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        $user->assignRole('admin');

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil ditambahkan!',
            'data' => $user
        ]);
    }

    /**
     * Update data admin
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Data admin berhasil diperbarui!',
            'data' => $user
        ]);
    }

    /**
     * Hapus data admin
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Cek jika admin terakhir
        $adminCount = User::role('admin')->count();
        if ($adminCount <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus admin terakhir!'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil dihapus!'
        ]);
    }
}