<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kursus;
use App\Models\KategoriPelatihan;

class PengajarKursusController extends Controller
{
    // Menampilkan daftar kursus yang hanya milik pengajar yang login
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Kursus::with('pengajar')
            ->where('user_id', $user->id);

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_singkat', 'like', '%' . $request->search . '%');
            });
        }
        $kursus = $query->latest()->paginate(9);
        $categories = KategoriPelatihan::all();
        return view('pengajar.kursus', compact('kursus', 'categories'));
    }
}
