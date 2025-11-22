<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modul;
use App\Models\Kursus;

class ModulController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kursus_id' => 'required|exists:kursus,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        // Auto-generate urutan
        $maxUrutan = Modul::where('kursus_id', $request->kursus_id)->max('urutan') ?? 0;
        $validated['urutan'] = $maxUrutan + 1;

        Modul::create($validated);

        return redirect()->route('admin.pelatihan.show', $request->kursus_id)
            ->with('success', 'Modul berhasil ditambahkan');
    }

    public function edit($id)
    {
        $modul = Modul::findOrFail($id);
        return response()->json($modul);
    }

    public function update(Request $request, $id)
    {
        $modul = Modul::findOrFail($id);

        $validated = $request->validate([
            'kursus_id' => 'required|exists:kursus,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $modul->update($validated);

        return redirect()->route('admin.pelatihan.show', $request->kursus_id)
            ->with('success', 'Modul berhasil diupdate');
    }

    public function destroy($id)
    {
        $modul = Modul::findOrFail($id);
        $modul->delete();

        return response()->json([
            'success' => true,
            'message' => 'Modul berhasil dihapus'
        ]);
    }
}
