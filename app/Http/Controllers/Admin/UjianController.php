<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\Soal;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function show($id)
    {
        $ujian = Ujian::with(['modul.kursus', 'soal.pilihanJawaban'])->findOrFail($id);
        
        return view('admin.pelatihan.ujian-detail', compact('ujian'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul_id' => 'required|exists:modul,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:quiz,ujian',
        ]);

        // Get kursus_id from modul
        $modul = \App\Models\Modul::findOrFail($validated['modul_id']);
        
        $ujian = Ujian::create([
            'kursus_id' => $modul->kursus_id,
            'modul_id' => $validated['modul_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'tipe' => $validated['tipe'] === 'quiz' ? 'practice' : 'exam',
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ujian berhasil ditambahkan',
            'data' => $ujian
        ]);
    }

    public function edit($id)
    {
        $ujian = Ujian::findOrFail($id);
        return response()->json($ujian);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $ujian = Ujian::findOrFail($id);
        $ujian->update([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ujian berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $ujian = Ujian::findOrFail($id);
        $ujian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ujian berhasil dihapus'
        ]);
    }
}
