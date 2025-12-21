<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\Soal;
use App\Repositories\ProgressRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    protected ProgressRepository $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    public function show($id)
    {
        $ujian = Ujian::with(['modul.kursus.pengajar', 'modul.video', 'modul.materi', 'modul.ujian', 'soal.pilihanJawaban'])->findOrFail($id);
        
        // Get all items from the same module for navigation
        $modul = $ujian->modul;
        $allItems = collect();
        $completedItems = [];
        
        // Get user's completed items for this course
        if (Auth::check() && $modul) {
            $kursusId = $modul->kursus_id;
            $userId = Auth::id();
            $completedItems = $this->progressRepository->getCompletedItems($userId, $kursusId);
        }
        
        if ($modul) {
            // Add videos from this module
            foreach ($modul->video ?? [] as $video) {
                $isCompleted = collect($completedItems)->contains(fn($item) => $item['type'] === 'video' && $item['id'] == $video->id);
                $allItems->push([
                    'type' => 'video', 
                    'data' => $video, 
                    'urutan' => $video->urutan ?? 0,
                    'completed' => $isCompleted,
                ]);
            }
            
            // Add materi/bacaan from this module
            foreach ($modul->materi ?? [] as $materi) {
                $isCompleted = collect($completedItems)->contains(fn($item) => $item['type'] === 'materi' && $item['id'] == $materi->id);
                $allItems->push([
                    'type' => 'bacaan', 
                    'data' => $materi, 
                    'urutan' => ($materi->urutan ?? 0) + 100,
                    'completed' => $isCompleted,
                ]);
            }
            
            // Add quiz and ujian from this module
            foreach ($modul->ujian ?? [] as $ujianItem) {
                $type = ($ujianItem->tipe === 'practice') ? 'quiz' : 'ujian';
                $isCompleted = collect($completedItems)->contains(fn($item) => $item['type'] === $type && $item['id'] == $ujianItem->id);
                $allItems->push([
                    'type' => $type, 
                    'data' => $ujianItem, 
                    'urutan' => 200 + ($ujianItem->id ?? 0),
                    'completed' => $isCompleted,
                ]);
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
            'minimum_score' => 'nullable|integer|min:0|max:100',
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
            'minimum_score' => $validated['minimum_score'] ?? 70,
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
            'minimum_score' => 'nullable|integer|min:0|max:100',
        ]);

        $ujian = Ujian::findOrFail($id);
        $ujian->update([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'waktu_pengerjaan' => $validated['waktu_pengerjaan'],
            'minimum_score' => $validated['minimum_score'] ?? $ujian->minimum_score ?? 70,
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
