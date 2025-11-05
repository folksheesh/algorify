<?php // Controller untuk fitur kursus

namespace App\Http\Controllers; // Namespace controller

use Illuminate\Http\Request; // Import class Request untuk HTTP
use App\Models\Kursus; // Import model Kursus

// Controller ini menangani permintaan terkait data kursus
class KursusController extends Controller
{
    // Menampilkan daftar kursus yang sudah dipublish
    public function index(Request $request)
    {
        // Ambil data kursus beserta data pengajar, hanya yang statusnya published
        $query = Kursus::with('pengajar')->where('status', 'published');

        // Filter berdasarkan kategori jika parameter kategori ada dan tidak kosong
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Pencarian berdasarkan judul, deskripsi, atau deskripsi singkat
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_singkat', 'like', '%' . $request->search . '%');
            });
        }

        // Urutkan dari yang terbaru dan paginasi 9 data per halaman
        $kursus = $query->latest()->paginate(9);

        // Tampilkan ke view kursus.index dengan data kursus
        return view('kursus.index', compact('kursus'));
    }

    // Menampilkan detail satu kursus berdasarkan id
    public function show($id)
    {
        // Ambil data kursus beserta relasi pengajar, modul, dan enrollments
        $kursus = Kursus::with('pengajar', 'modul', 'enrollments')->findOrFail($id);
        // Tampilkan ke view kursus.show dengan data kursus
        return view('kursus.show', compact('kursus'));
    }
}
