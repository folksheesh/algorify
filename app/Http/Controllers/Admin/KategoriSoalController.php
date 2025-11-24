<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSoal;
use Illuminate\Http\Request;

class KategoriSoalController extends Controller
{
    public function index()
    {
        $kategoris = KategoriSoal::withCount('bankSoal')->get();
        return view('admin.kategori-soal.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:kategori_soal,kode',
            'deskripsi' => 'nullable|string',
            'warna' => 'required|string',
        ]);

        KategoriSoal::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:kategori_soal,kode,' . $id,
            'deskripsi' => 'nullable|string',
            'warna' => 'required|string',
        ]);

        $kategori = KategoriSoal::findOrFail($id);
        $kategori->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $kategori = KategoriSoal::findOrFail($id);
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
