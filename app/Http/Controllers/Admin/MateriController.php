<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Modul;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function show($id)
    {
        $materi = Materi::with(['modul.kursus.pengajar'])->findOrFail($id);
        
        // Get all materials in the same module (videos + materi)
        $modul = $materi->modul;
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
        
        return view('admin.pelatihan.materi-detail', compact('materi', 'allItems'));
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
