<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kursus;

class KursusController extends Controller
{
    public function index(Request $request)
    {
        $query = Kursus::with('pengajar')->where('status', 'published');

        // Filter by category
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_singkat', 'like', '%' . $request->search . '%');
            });
        }

        $kursus = $query->latest()->paginate(9);

        return response()->json($kursus);
    }

    public function show($id)
    {
        $kursus = Kursus::with('pengajar', 'modul', 'enrollments')->findOrFail($id);
        
        return response()->json($kursus);
    }
}
