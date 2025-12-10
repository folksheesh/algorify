<?php // Controller untuk fitur kursus

namespace App\Http\Controllers; // Namespace controller

use Illuminate\Http\Request; // Import class Request untuk HTTP
use App\Models\Kursus; // Import model Kursus
use App\Models\KategoriPelatihan; // Import model KategoriPelatihan

// Controller ini menangani permintaan terkait data kursus
class KursusController extends Controller
{
    // Menampilkan daftar kursus yang sudah dipublish
    public function index(Request $request)
    {
        // Ambil data kursus beserta data pengajar, hanya yang statusnya published
        $query = Kursus::with('pengajar')->where('status', 'published');

        // Filter berdasarkan kategori (string) jika parameter kategori ada dan tidak kosong
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan tipe kursus jika parameter tipe_kursus ada dan tidak kosong
        if ($request->filled('tipe_kursus')) {
            $query->where('tipe_kursus', $request->tipe_kursus);
        }

        // Pencarian berdasarkan judul, deskripsi, atau deskripsi singkat (case-insensitive)
        if ($request->filled('search')) {
            $search = strtolower(trim($request->search));
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(judul) LIKE ?', ['%' . $search . '%'])
                  ->orWhereRaw('LOWER(deskripsi) LIKE ?', ['%' . $search . '%'])
                  ->orWhereRaw('LOWER(deskripsi_singkat) LIKE ?', ['%' . $search . '%']);
            });
        }

        // Urutkan dari yang terbaru dan paginasi 9 data per halaman
        $kursus = $query->latest()->paginate(9);
        
        // Dummy categories (tidak perlu query dari DB)
        $categories = collect([
            (object)['id' => 1, 'nama_kategori' => 'Programming'],
            (object)['id' => 2, 'nama_kategori' => 'Design'],
            (object)['id' => 3, 'nama_kategori' => 'Business'],
            (object)['id' => 4, 'nama_kategori' => 'Marketing'],
            (object)['id' => 5, 'nama_kategori' => 'Data Science'],
            (object)['id' => 6, 'nama_kategori' => 'Other'],
        ]);

        // Tampilkan ke view kursus.index dengan data kursus dan kategori
        return view('kursus.index', compact('kursus', 'categories'));
    }

    // Menampilkan detail satu kursus berdasarkan id
    public function show($id)
    {
        // Ambil data kursus beserta relasi pengajar, modul, dan enrollments
        $kursus = Kursus::with('pengajar', 'modul', 'enrollments')->findOrFail($id);
        
        // Cek apakah user sudah enrolled di kursus ini
        if (auth()->check()) {
            $enrollment = \App\Models\Enrollment::where('user_id', auth()->id())
                ->where('kursus_id', $id)
                ->first();
            
            // Jika sudah enrolled, redirect ke halaman pelatihan admin
            if ($enrollment) {
                return redirect()->route('admin.pelatihan.show', $id);
            }
        }
        
        // Tampilkan ke view kursus.show dengan data kursus
        return view('kursus.show', compact('kursus'));
    }
}
