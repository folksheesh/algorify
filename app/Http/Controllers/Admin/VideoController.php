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
        try {
            // YouTube URL takes priority over file upload
            $hasYoutube = $request->filled('youtube_url');
            
            $validated = $request->validate([
                'modul_id' => 'required|exists:modul,id',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'youtube_url' => ['nullable', 'string', function ($attribute, $value, $fail) {
                    if ($value && !preg_match('/(youtube\.com|youtu\.be)/', $value)) {
                        $fail('URL harus berupa link YouTube yang valid.');
                    }
                }],
                'file_video' => $hasYoutube ? 'nullable' : 'required|file|mimetypes:video/mp4,video/mpeg,video/quicktime,video/x-msvideo|max:204800', // 200MB
            ]);

            // Auto-generate urutan
            $maxUrutan = Video::where('modul_id', $request->modul_id)->max('urutan');
            $validated['urutan'] = $maxUrutan ? $maxUrutan + 1 : 1;

            // Handle YouTube URL - extract video ID and store
            if ($hasYoutube) {
                $validated['youtube_url'] = $this->normalizeYoutubeUrl($request->youtube_url);
                $validated['file_video'] = null;
            } elseif ($request->hasFile('file_video')) {
                $file = $request->file('file_video');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('videos', $filename, 'public');
                $validated['file_video'] = $path;
                $validated['youtube_url'] = null;
            }

            Video::create($validated);

            return response()->json(['success' => true, 'message' => 'Video berhasil ditambahkan!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan video: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Normalize YouTube URL to embed format
     */
    private function normalizeYoutubeUrl($url)
    {
        // Extract video ID from various YouTube URL formats
        $videoId = null;
        
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/embed\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        
        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : $url;
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return response()->json($video);
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $hasYoutube = $request->filled('youtube_url');
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'file_video' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime,video/x-msvideo|max:204800',
        ]);

        // Handle YouTube URL - takes priority
        if ($hasYoutube) {
            // Delete old file if exists
            if ($video->file_video) {
                Storage::disk('public')->delete($video->file_video);
            }
            $validated['youtube_url'] = $this->normalizeYoutubeUrl($request->youtube_url);
            $validated['file_video'] = null;
        } elseif ($request->hasFile('file_video')) {
            // Delete old file
            if ($video->file_video) {
                Storage::disk('public')->delete($video->file_video);
            }
            
            $file = $request->file('file_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('videos', $filename, 'public');
            $validated['file_video'] = $path;
            $validated['youtube_url'] = null;
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

    public function show(Video $video)
    {
        $video->load(['modul.kursus.pengajar', 'modul.video', 'modul.materi', 'modul.ujian']);
        
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
