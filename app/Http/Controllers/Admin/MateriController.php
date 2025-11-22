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
            'file_pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
        ]);

        // Auto-generate urutan
        $maxUrutan = Materi::where('modul_id', $request->modul_id)->max('urutan');
        $validated['urutan'] = $maxUrutan ? $maxUrutan + 1 : 1;

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pdfs', $filename, 'public');
            $validated['file_pdf'] = $path;
        }

        Materi::create($validated);

        return response()->json(['success' => true, 'message' => 'PDF berhasil ditambahkan!']);
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
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_pdf')) {
            // Delete old file
            if ($materi->file_pdf) {
                Storage::disk('public')->delete($materi->file_pdf);
            }
            
            $file = $request->file('file_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pdfs', $filename, 'public');
            $validated['file_pdf'] = $path;
        }

        $materi->update($validated);

        return response()->json(['success' => true, 'message' => 'PDF berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);
        
        // Delete associated file
        if ($materi->file_pdf) {
            Storage::disk('public')->delete($materi->file_pdf);
        }
        
        $materi->delete();

        return response()->json(['success' => true, 'message' => 'PDF berhasil dihapus!']);
    }
}
