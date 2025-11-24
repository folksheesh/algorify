<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Http\Request;
use App\Exports\SoalTemplateExport;
use App\Imports\SoalImport;
use Maatwebsite\Excel\Facades\Excel;

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

    public function downloadTemplate()
    {
        return Excel::download(new SoalTemplateExport, 'template_soal.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|exists:ujian,id',
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        try {
            $ujian = \App\Models\Ujian::findOrFail($request->ujian_id);
            
            Excel::import(
                new SoalImport($request->ujian_id, $ujian->kursus_id), 
                $request->file('file')
            );

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimport soal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export($ujianId)
    {
        $ujian = \App\Models\Ujian::with('soal.pilihanJawaban')->findOrFail($ujianId);
        
        return Excel::download(
            new \App\Exports\SoalExport($ujianId), 
            'soal_' . \Illuminate\Support\Str::slug($ujian->judul) . '.xlsx'
        );
    }

    public function addFromBank(Request $request)
    {
        $validated = $request->validate([
            'ujian_id' => 'required|exists:ujian,id',
            'bank_soal_ids' => 'required|array',
            'bank_soal_ids.*' => 'required|exists:bank_soal,id',
        ]);

        $ujian = \App\Models\Ujian::findOrFail($validated['ujian_id']);
        $bankSoals = \App\Models\BankSoal::with('pilihan')->whereIn('id', $validated['bank_soal_ids'])->get();

        foreach ($bankSoals as $bankSoal) {
            // Create soal from bank soal
            $soal = Soal::create([
                'ujian_id' => $ujian->id,
                'kursus_id' => $ujian->kursus_id,
                'pertanyaan' => $bankSoal->pertanyaan,
                'kunci_jawaban' => $bankSoal->kunci_jawaban,
            ]);

            // Copy pilihan jawaban
            foreach ($bankSoal->pilihan as $pilihanBank) {
                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'pilihan' => $pilihanBank->pilihan,
                    'is_correct' => $pilihanBank->is_correct,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($bankSoals) . ' soal berhasil ditambahkan dari bank soal'
        ]);
    }
}
