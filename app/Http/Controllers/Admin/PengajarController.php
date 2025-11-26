<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kursus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * PENGAJAR CONTROLLER
 * Controller untuk mengelola data pengajar (teacher/instructor)
 * Fitur: CRUD pengajar, validasi data, upload sertifikasi
 */
class PengajarController extends Controller
{
    /**
     * Menampilkan halaman index data pengajar
     * Route: GET /admin/pengajar
     * 
     * @return \Illuminate\View\View
     */
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

    /**
     * Mengambil data pengajar dalam format JSON untuk AJAX request
     * Route: GET /admin/pengajar/data
     * 
     * Data yang diambil:
     * - User dengan role 'pengajar'
     * - Jumlah kursus yang diajar
     * - Detail kursus beserta jumlah siswa per kursus
     * - Total siswa keseluruhan
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        // Query untuk mengambil user dengan role pengajar
        // Hitung jumlah kursus yang diajar
        // Hitung siswa per kursus
        $query = User::role('pengajar')
            ->withCount('kursus as kursus_count')
            ->with(['kursus' => function($query) {
                $query->select('id', 'judul', 'user_id')
                      ->withCount('enrollments as total_siswa');
            }]);

        // Ambil data dan urutkan berdasarkan tanggal dibuat (terbaru dulu)
        $pengajar = $query->orderBy('created_at', 'desc')->paginate(10);

        // Hitung total siswa untuk setiap pengajar dari semua kursusnya
        $pengajar->getCollection()->transform(function($item) {
            $item->total_siswa = $item->kursus->sum('total_siswa');
            return $item;
        });

        return response()->json($pengajar);
    }

    /**
     * Menyimpan data pengajar baru ke database
     * Route: POST /admin/pengajar
     * 
     * Validasi:
     * - name: required, string, max 255
     * - email: required, unique, valid email
     * - password: required, sesuai aturan default Laravel
     * - sertifikasi: optional, file (pdf/jpg/jpeg/png), max 2MB
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'keahlian' => ['nullable', 'string'],
            'pengalaman' => ['nullable', 'string'],
            'sertifikasi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'status' => ['nullable', 'in:active,inactive,suspended'],
        ]);

        // Upload file sertifikasi jika ada
        $sertifikasiPath = null;
        if ($request->hasFile('sertifikasi')) {
            $sertifikasiPath = $request->file('sertifikasi')->store('sertifikasi', 'public');
        }

        // Buat user baru dengan data yang sudah divalidasi
        // Password di-hash untuk keamanan
        // Default status adalah 'active' jika tidak diisi
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'keahlian' => $validated['keahlian'] ?? null,
            'pengalaman' => $validated['pengalaman'] ?? null,
            'sertifikasi' => $sertifikasiPath,
            'status' => $validated['status'] ?? 'active',
        ]);

        // Assign role 'pengajar' ke user yang baru dibuat
        $user->assignRole('pengajar');

        return response()->json([
            'success' => true,
            'message' => 'Pengajar berhasil ditambahkan',
            'data' => $user
        ]);
    }

    /**
     * Update data pengajar yang sudah ada
     * Route: PUT /admin/pengajar/{id}
     * 
     * Perbedaan dengan store:
     * - Email unique kecuali untuk user ini sendiri
     * - Password optional (kosongkan jika tidak ingin ubah)
     * - File sertifikasi lama akan dihapus jika upload yang baru
     * 
     * @param Request $request
     * @param int $id - ID user yang akan diupdate
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Cari user dengan role pengajar berdasarkan ID
        $user = User::role('pengajar')->findOrFail($id);

        // Validasi input
        // Email unique kecuali untuk user ini sendiri
        // Password optional saat update
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'keahlian' => ['nullable', 'string'],
            'pengalaman' => ['nullable', 'string'],
            'sertifikasi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'status' => ['nullable', 'in:active,inactive,suspended'],
            'password' => ['nullable', Rules\Password::defaults()],
        ]);

        // Update field-field user
        // Gunakan nilai lama jika input kosong
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->address = $validated['address'] ?? $user->address;
        $user->tanggal_lahir = $validated['tanggal_lahir'] ?? $user->tanggal_lahir;
        $user->jenis_kelamin = $validated['jenis_kelamin'] ?? $user->jenis_kelamin;
        $user->keahlian = $validated['keahlian'] ?? $user->keahlian;
        $user->pengalaman = $validated['pengalaman'] ?? $user->pengalaman;
        $user->status = $validated['status'] ?? $user->status;
        
        // Handle upload sertifikasi baru
        if ($request->hasFile('sertifikasi')) {
            // Hapus file lama jika ada (untuk menghemat storage)
            if ($user->sertifikasi && \Storage::disk('public')->exists($user->sertifikasi)) {
                \Storage::disk('public')->delete($user->sertifikasi);
            }
            
            // Upload file baru
            $user->sertifikasi = $request->file('sertifikasi')->store('sertifikasi', 'public');
        }
        
        // Update password hanya jika diisi
        // Password optional saat edit
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Simpan perubahan ke database
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Data pengajar berhasil diupdate',
            'data' => $user
        ]);
    }

    /**
     * Menampilkan detail data pengajar
     * Route: GET /admin/pengajar/{id}
     * 
     * @param int $id - ID user yang akan ditampilkan
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::role('pengajar')
            ->with(['kursus' => function($query) {
                $query->select('id', 'judul', 'user_id')
                      ->withCount('enrollments as total_siswa');
            }])
            ->findOrFail($id);
        
        $user->total_siswa = $user->kursus->sum('total_siswa');
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Menghapus data pengajar dari database
     * Route: DELETE /admin/pengajar/{id}
     * 
     * Validasi:
     * - Pengajar tidak boleh dihapus jika masih memiliki kursus aktif
     * - Ini untuk mencegah data inconsistency
     * 
     * @param int $id - ID user yang akan dihapus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Cari user dengan role pengajar
        $user = User::role('pengajar')->findOrFail($id);
        
        // Cek apakah pengajar memiliki kursus aktif
        // Jika ada, tidak boleh dihapus untuk menjaga integritas data
        if ($user->kursus()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajar tidak dapat dihapus karena masih memiliki kursus aktif'
            ], 400);
        }

        // Hapus user dari database
        // Soft delete jika diaktifkan
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajar berhasil dihapus'
        ]);
    }
}
