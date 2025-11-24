<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\BankSoalPilihan;
use App\Models\KategoriSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSoalController extends Controller
{
    public function index(Request $request)
    {
        $query = BankSoal::with(['kategori', 'creator', 'pilihan']);

        // Filter by kategori
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter by tingkat kesulitan
        if ($request->has('tingkat_kesulitan') && $request->tingkat_kesulitan != '') {
            $query->where('tingkat_kesulitan', $request->tingkat_kesulitan);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('pertanyaan', 'LIKE', '%' . $request->search . '%');
        }

        $bankSoal = $query->latest()->paginate(20);
        $kategoris = KategoriSoal::all();

        return view('admin.bank-soal.index', compact('bankSoal', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_soal,id',
            'pertanyaan' => 'required|string',
            'tingkat_kesulitan' => 'required|in:mudah,sedang,sulit',
            'pilihan' => 'required|array|min:2',
            'pilihan.*' => 'required|string',
            'kunci_jawaban' => 'required|integer|min:0',
        ]);

        // Convert index to letter
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $kunciJawabanLetter = $letters[$validated['kunci_jawaban']] ?? 'A';

        // Create bank soal
        $bankSoal = BankSoal::create([
            'kategori_id' => $validated['kategori_id'],
            'pertanyaan' => $validated['pertanyaan'],
            'tingkat_kesulitan' => $validated['tingkat_kesulitan'],
            'kunci_jawaban' => $kunciJawabanLetter,
            'created_by' => Auth::id(),
        ]);

        // Create pilihan jawaban
        foreach ($validated['pilihan'] as $index => $pilihan) {
            BankSoalPilihan::create([
                'bank_soal_id' => $bankSoal->id,
                'pilihan' => $pilihan,
                'is_correct' => $index == $validated['kunci_jawaban'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil ditambahkan ke bank soal'
        ]);
    }

    public function edit($id)
    {
        $bankSoal = BankSoal::with(['kategori', 'pilihan'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $bankSoal
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_soal,id',
            'pertanyaan' => 'required|string',
            'tingkat_kesulitan' => 'required|in:mudah,sedang,sulit',
            'pilihan' => 'required|array|min:2',
            'pilihan.*' => 'required|string',
            'kunci_jawaban' => 'required|integer|min:0',
        ]);

        $bankSoal = BankSoal::findOrFail($id);

        // Convert index to letter
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $kunciJawabanLetter = $letters[$validated['kunci_jawaban']] ?? 'A';

        // Update bank soal
        $bankSoal->update([
            'kategori_id' => $validated['kategori_id'],
            'pertanyaan' => $validated['pertanyaan'],
            'tingkat_kesulitan' => $validated['tingkat_kesulitan'],
            'kunci_jawaban' => $kunciJawabanLetter,
        ]);

        // Delete old pilihan and create new ones
        $bankSoal->pilihan()->delete();
        foreach ($validated['pilihan'] as $index => $pilihan) {
            BankSoalPilihan::create([
                'bank_soal_id' => $bankSoal->id,
                'pilihan' => $pilihan,
                'is_correct' => $index == $validated['kunci_jawaban'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $bankSoal = BankSoal::findOrFail($id);
        $bankSoal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dihapus'
        ]);
    }

    public function getByKategori($kategoriId)
    {
        $bankSoal = BankSoal::with('pilihan')
            ->where('kategori_id', $kategoriId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bankSoal
        ]);
    }
}
