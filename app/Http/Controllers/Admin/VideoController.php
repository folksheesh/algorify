<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Repositories\ProgressRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    protected ProgressRepository $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul_id' => 'required|exists:modul,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_video' => 'required|file|mimetypes:video/mp4,video/mpeg,video/quicktime,video/x-msvideo|max:204800', // 200MB
        ]);

        // Auto-generate urutan
        $maxUrutan = Video::where('modul_id', $request->modul_id)->max('urutan');
        $validated['urutan'] = $maxUrutan ? $maxUrutan + 1 : 1;

        if ($request->hasFile('file_video')) {
            $file = $request->file('file_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('videos', $filename, 'public');
            $validated['file_video'] = $path;
        }

        Video::create($validated);

        return response()->json(['success' => true, 'message' => 'Video berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return response()->json($video);
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_video' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime,video/x-msvideo|max:204800',
        ]);

        if ($request->hasFile('file_video')) {
            // Delete old file
            if ($video->file_video) {
                Storage::disk('public')->delete($video->file_video);
            }
            
            $file = $request->file('file_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('videos', $filename, 'public');
            $validated['file_video'] = $path;
        }

        $video->update($validated);

        return response()->json(['success' => true, 'message' => 'Video berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        
        // Delete associated file
        if ($video->file_video) {
            Storage::disk('public')->delete($video->file_video);
        }
        
        $video->delete();

        return response()->json(['success' => true, 'message' => 'Video berhasil dihapus!']);
    }

    public function show($id)
    {
        $video = Video::with(['modul.kursus.pengajar', 'modul.video', 'modul.materi', 'modul.ujian'])->findOrFail($id);
        
        // Get all materials in the same module (videos + materi + ujian)
        $modul = $video->modul;
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
        
        // Get current video's completion status
        $videoCompleted = collect($completedItems)->contains(fn($item) => $item['type'] === 'video' && $item['id'] == $video->id);
        
        return view('admin.pelatihan.video-detail', compact('video', 'allItems', 'videoCompleted'));
    }
}
