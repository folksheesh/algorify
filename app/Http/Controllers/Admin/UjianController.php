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
        
        // Get all items from the same module for navigation
        $allItems = collect();
        
        if ($ujian->modul) {
            // Add videos from this module
            foreach ($ujian->modul->video as $video) {
                $allItems->push(['type' => 'video', 'data' => $video, 'urutan' => $video->urutan ?? 0]);
            }
            
            // Add materi/bacaan from this module
            foreach ($ujian->modul->materi as $materi) {
                $allItems->push(['type' => 'bacaan', 'data' => $materi, 'urutan' => $materi->urutan ?? 0]);
            }
            
            // Add quiz and ujian from this module
            foreach ($ujian->modul->ujian as $ujianItem) {
                $type = ($ujianItem->tipe === 'practice') ? 'quiz' : 'ujian';
                $allItems->push(['type' => $type, 'data' => $ujianItem, 'urutan' => 999]); // Put quiz/ujian at end
            }
        }
        
        // Sort by urutan
        $allItems = $allItems->sortBy('urutan')->values();
        
        return view('admin.pelatihan.ujian-detail', compact('ujian', 'allItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul_id' => 'required|exists:modul,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:quiz,ujian',
            'waktu_pengerjaan' => 'required|integer|min:1',
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
            'waktu_pengerjaan' => $validated['waktu_pengerjaan'],
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
            'waktu_pengerjaan' => 'required|integer|min:1',
        ]);

        $ujian = Ujian::findOrFail($id);
        $ujian->update([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'waktu_pengerjaan' => $validated['waktu_pengerjaan'],
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
