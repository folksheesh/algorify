<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Modul;
use App\Repositories\ProgressRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    protected ProgressRepository $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    public function show($id)
    {
        $materi = Materi::with(['modul.kursus.pengajar', 'modul.video', 'modul.materi', 'modul.ujian'])->findOrFail($id);
        
        // Get all materials in the same module (videos + materi + ujian)
        $modul = $materi->modul;
        $allItems = collect();
        $completedItems = [];
        
        // Get user's completed items for this course
        if (Auth::check() && $modul) {
            $kursusId = $modul->kursus_id;
            $userId = Auth::id();
            $completedItems = $this->progressRepository->getCompletedItems($userId, $kursusId);
        }
        
        if ($modul) {
            foreach ($modul->video ?? [] as $v) {
                $isCompleted = collect($completedItems)->contains(fn($item) => $item['type'] === 'video' && $item['id'] == $v->id);
                $allItems->push([
                    'type' => 'video',
                    'data' => $v,
                    'urutan' => $v->urutan,
                    'completed' => $isCompleted,
                ]);
            }
            
            foreach ($modul->materi ?? [] as $m) {
                $isCompleted = collect($completedItems)->contains(fn($item) => $item['type'] === 'materi' && $item['id'] == $m->id);
                $allItems->push([
                    'type' => 'bacaan',
                    'data' => $m,
                    'urutan' => $m->urutan + 100, // Bacaan setelah video
                    'completed' => $isCompleted,
                ]);
            }
            
            foreach ($modul->ujian ?? [] as $u) {
                $type = $u->tipe === 'exam' ? 'ujian' : 'quiz';
                $isCompleted = collect($completedItems)->contains(fn($item) => $item['type'] === $type && $item['id'] == $u->id);
                $allItems->push([
                    'type' => $type,
                    'data' => $u,
                    'urutan' => 200 + ($u->id ?? 0), // Quiz/Ujian di akhir
                    'completed' => $isCompleted,
                ]);
            }
        }
        
        $allItems = $allItems->sortBy('urutan')->values();
        
        // Get current materi's completion status
        $materiCompleted = collect($completedItems)->contains(fn($item) => $item['type'] === 'materi' && $item['id'] == $materi->id);
        
        return view('admin.pelatihan.materi-detail', compact('materi', 'allItems', 'materiCompleted'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul_id' => 'required|exists:modul,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'konten' => 'required|string',
        ]);

        // Auto-generate urutan
        $maxUrutan = Materi::where('modul_id', $request->modul_id)->max('urutan');
        $validated['urutan'] = $maxUrutan ? $maxUrutan + 1 : 1;

        Materi::create($validated);

        return response()->json(['success' => true, 'message' => 'Materi berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $materi = Materi::findOrFail($id);
        return response()->json($materi);
    }

    public function update(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'konten' => 'required|string',
        ]);

        $materi->update($validated);

        return response()->json(['success' => true, 'message' => 'Materi berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);
        
        // Delete associated file if exists
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }
        
        $materi->delete();

        return response()->json(['success' => true, 'message' => 'Materi berhasil dihapus!']);
    }
    
    /**
     * Upload image untuk konten editor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materi_content', $filename, 'public');
            
            return response()->json([
                'success' => true,
                'url' => Storage::url($path)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No image uploaded'], 400);
    }
}
