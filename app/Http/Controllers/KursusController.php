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
        Kursus::whereNull('slug')->get()->each->save();

        // Ambil data kursus beserta data pengajar, hanya yang statusnya published
        $query = Kursus::with('pengajar')->where('status', 'published');

        // Filter berdasarkan kategori (supports multiple values separated by comma)
        if ($request->filled('kategori')) {
            $kategoriValues = array_filter(explode(',', $request->kategori));
            if (count($kategoriValues) > 0) {
                $query->whereIn('kategori', $kategoriValues);
            }
        }

        // Filter berdasarkan tipe kursus (supports multiple values separated by comma)
        if ($request->filled('tipe_kursus')) {
            $tipeValues = array_filter(explode(',', $request->tipe_kursus));
            if (count($tipeValues) > 0) {
                $query->whereIn('tipe_kursus', $tipeValues);
            }
        }

        // Filter berdasarkan pengajar (supports multiple values separated by comma)
        if ($request->filled('pengajar_id')) {
            $pengajarValues = array_filter(explode(',', $request->pengajar_id));
            if (count($pengajarValues) > 0) {
                $query->whereIn('user_id', $pengajarValues);
            }
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
        
        // Get categories from database
        $kategoris = KategoriPelatihan::orderBy('nama_kategori', 'asc')->get();
        
        // Get all pengajar with role 'pengajar'
        $pengajars = \App\Models\User::role('pengajar')->get();

        // Tampilkan ke view kursus.index dengan data kursus, kategori, dan pengajar
        return view('kursus.index', compact('kursus', 'kategoris', 'pengajars'));
    }

    // Menampilkan detail satu kursus berdasarkan id
    public function show(Kursus $kursus)
    {
        // Ambil data kursus beserta relasi pengajar, modul, dan enrollments
        $kursus->load('pengajar', 'modul', 'enrollments');
        
        // Cek apakah user sudah enrolled di kursus ini
        if (auth()->check()) {
            $enrollment = \App\Models\Enrollment::where('user_id', auth()->id())
                ->where('kursus_id', $kursus->id)
                ->first();
            
            // Jika sudah enrolled, redirect ke halaman pelatihan admin
            if ($enrollment) {
                return redirect()->route('admin.pelatihan.show', $kursus->slug);
            }
        }
        
        // Tampilkan ke view kursus.show dengan data kursus
        return view('kursus.show', compact('kursus'));
    }
}
