<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
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
        $video = Video::with(['modul.kursus.pengajar'])->findOrFail($id);
        
        // Get all materials in the same module (videos + materi)
        $modul = $video->modul;
        $allItems = collect();
        
        foreach ($modul->video as $v) {
            $allItems->push([
                'type' => 'video',
                'data' => $v,
                'urutan' => $v->urutan
            ]);
        }
        
        foreach ($modul->materi as $m) {
            $allItems->push([
                'type' => 'pdf',
                'data' => $m,
                'urutan' => $m->urutan
            ]);
        }
        
        $allItems = $allItems->sortBy('urutan')->values();
        
        return view('admin.pelatihan.video-detail', compact('video', 'allItems'));
    }
}
