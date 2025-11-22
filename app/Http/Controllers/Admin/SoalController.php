<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ujian_id' => 'required|exists:ujian,id',
            'pertanyaan' => 'required|string',
            'pilihan' => 'required|array|min:2',
            'pilihan.*' => 'required|string',
            'kunci_jawaban' => 'required|integer|min:0',
        ]);

        // Get ujian to get kursus_id
        $ujian = \App\Models\Ujian::findOrFail($validated['ujian_id']);

        // Convert index to letter (0=A, 1=B, etc)
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $kunciJawabanLetter = $letters[$validated['kunci_jawaban']] ?? 'A';

        // Create soal
        $soal = Soal::create([
            'ujian_id' => $validated['ujian_id'],
            'kursus_id' => $ujian->kursus_id,
            'pertanyaan' => $validated['pertanyaan'],
            'kunci_jawaban' => $kunciJawabanLetter,
        ]);

        // Create pilihan jawaban
        foreach ($validated['pilihan'] as $index => $pilihan) {
            PilihanJawaban::create([
                'soal_id' => $soal->id,
                'pilihan' => $pilihan,
                'is_correct' => $index == $validated['kunci_jawaban'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil ditambahkan',
            'data' => $soal
        ]);
    }

    public function destroy($id)
    {
        $soal = Soal::findOrFail($id);
        $soal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dihapus'
        ]);
    }
}
