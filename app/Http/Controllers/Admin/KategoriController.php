<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPelatihan;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.kategori.index');
    }

    /**
     * Get data for DataTable
     */
    public function getData(Request $request)
    {
        $query = KategoriPelatihan::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        $kategori = $query->latest()->get();

        return response()->json([
            'data' => $kategori->map(function($item, $index) {
                return [
                    'id' => $item->id,
                    'no' => $index + 1,
                    'nama_kategori' => $item->nama_kategori,
                    'deskripsi' => $item->deskripsi,
                    'jumlah_soal' => $item->bankSoal()->count()
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        KategoriPelatihan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = KategoriPelatihan::findOrFail($id);
        return response()->json(['data' => $kategori]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategori = KategoriPelatihan::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $kategori->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = KategoriPelatihan::findOrFail($id);

        // Jika kategori memiliki soal terkait, pindahkan ke kategori placeholder
        if ($kategori->bankSoal()->count() > 0) {
            // Cari atau buat kategori placeholder "Umum"
            $placeholderKategori = KategoriPelatihan::firstOrCreate(
                ['nama_kategori' => 'Umum'],
                ['slug' => 'umum', 'deskripsi' => 'Kategori default untuk soal yang kategorinya dihapus']
            );
            
            // Pindahkan semua soal ke kategori placeholder
            $kategori->bankSoal()->update(['kategori_id' => $placeholderKategori->id]);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus. Soal terkait dipindahkan ke kategori Umum.'
        ]);
    }
}
