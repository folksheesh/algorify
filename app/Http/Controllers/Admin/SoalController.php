<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Http\Request;
use App\Exports\SoalTemplateExport;
use App\Imports\SoalImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class SoalController extends Controller
{
    public function store(Request $request)
    {
        // Validasi berbeda untuk single dan multiple
        $rules = [
            'ujian_id' => 'required|exists:ujian,id',
            'pertanyaan' => 'required|string',
            'tipe_soal' => 'required|in:single,multiple',
            'lampiran_foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'pilihan' => 'required|array|min:2',
            'pilihan.*' => 'required|string',
            'pembahasan' => 'nullable|string',
        ];

        // Validasi kunci_jawaban tergantung tipe_soal
        if ($request->tipe_soal === 'multiple') {
            $rules['kunci_jawaban'] = 'required|array|min:1';
            $rules['kunci_jawaban.*'] = 'required|integer|min:0';
        } else {
            $rules['kunci_jawaban'] = 'required|integer|min:0';
        }

        $validated = $request->validate($rules);

        // Get ujian to get kursus_id
        $ujian = \App\Models\Ujian::findOrFail($validated['ujian_id']);

        // Handle file upload
        $lampiranFotoPath = null;
        if ($request->hasFile('lampiran_foto')) {
            $lampiranFotoPath = $request->file('lampiran_foto')->store('soal_lampiran', 'public');
        }

        // Convert index to letter (0=A, 1=B, etc)
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        
        // Handle kunci_jawaban untuk single dan multiple
        $kunciJawabanText = '';
        $kunciJawabanIndices = [];
        
        if ($validated['tipe_soal'] === 'multiple') {
            // Multiple answer: array of indices
            $kunciJawabanIndices = $validated['kunci_jawaban'];
            $kunciJawabanLetters = array_map(function($index) use ($letters) {
                return $letters[$index] ?? 'A';
            }, $kunciJawabanIndices);
            $kunciJawabanText = implode(', ', $kunciJawabanLetters);
        } else {
            // Single answer: one index
            $kunciJawabanIndices = [$validated['kunci_jawaban']];
            $kunciJawabanText = $letters[$validated['kunci_jawaban']] ?? 'A';
        }

        // Create soal
        $soal = Soal::create([
            'ujian_id' => $validated['ujian_id'],
            'kursus_id' => $ujian->kursus_id,
            'pertanyaan' => $validated['pertanyaan'],
            'tipe_soal' => $validated['tipe_soal'],
            'lampiran_foto' => $lampiranFotoPath,
            'kunci_jawaban' => $kunciJawabanText,
            'pembahasan' => $validated['pembahasan'] ?? null,
        ]);

        // Create pilihan jawaban
        foreach ($validated['pilihan'] as $index => $pilihan) {
            PilihanJawaban::create([
                'soal_id' => $soal->id,
                'pilihan' => $pilihan,
                'is_correct' => in_array($index, $kunciJawabanIndices),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil ditambahkan',
            'data' => $soal
        ]);
    }

    public function edit($id)
    {
        $soal = Soal::with('pilihanJawaban')->findOrFail($id);
        
        // Find which indices are the correct answers
        $kunciJawabanIndices = [];
        foreach ($soal->pilihanJawaban as $index => $pilihan) {
            if ($pilihan->is_correct) {
                $kunciJawabanIndices[] = $index;
            }
        }
        
        // For single answer, return just the first index, for multiple return array
        $kunciJawaban = $soal->tipe_soal === 'multiple' ? $kunciJawabanIndices : ($kunciJawabanIndices[0] ?? 0);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $soal->id,
                'ujian_id' => $soal->ujian_id,
                'pertanyaan' => $soal->pertanyaan,
                'tipe_soal' => $soal->tipe_soal,
                'lampiran_foto' => $soal->lampiran_foto,
                'pilihan' => $soal->pilihanJawaban->pluck('pilihan')->toArray(),
                'kunci_jawaban' => $kunciJawaban,
                'pembahasan' => $soal->pembahasan,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi berbeda untuk single dan multiple
        $rules = [
            'pertanyaan' => 'required|string',
            'tipe_soal' => 'required|in:single,multiple',
            'lampiran_foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'pilihan' => 'required|array|min:2',
            'pilihan.*' => 'required|string',
            'pembahasan' => 'nullable|string',
        ];

        // Validasi kunci_jawaban tergantung tipe_soal
        if ($request->tipe_soal === 'multiple') {
            $rules['kunci_jawaban'] = 'required|array|min:1';
            $rules['kunci_jawaban.*'] = 'required|integer|min:0';
        } else {
            $rules['kunci_jawaban'] = 'required|integer|min:0';
        }

        $validated = $request->validate($rules);

        $soal = Soal::findOrFail($id);

        // Handle file upload
        $updateData = [
            'pertanyaan' => $validated['pertanyaan'],
            'tipe_soal' => $validated['tipe_soal'],
            'pembahasan' => $validated['pembahasan'] ?? null,
        ];

        if ($request->hasFile('lampiran_foto')) {
            // Delete old file if exists
            if ($soal->lampiran_foto && \Storage::disk('public')->exists($soal->lampiran_foto)) {
                \Storage::disk('public')->delete($soal->lampiran_foto);
            }
            $updateData['lampiran_foto'] = $request->file('lampiran_foto')->store('soal_lampiran', 'public');
        }

        // Convert index to letter (0=A, 1=B, etc)
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        
        // Handle kunci_jawaban untuk single dan multiple
        $kunciJawabanText = '';
        $kunciJawabanIndices = [];
        
        if ($validated['tipe_soal'] === 'multiple') {
            // Multiple answer: array of indices
            $kunciJawabanIndices = $validated['kunci_jawaban'];
            $kunciJawabanLetters = array_map(function($index) use ($letters) {
                return $letters[$index] ?? 'A';
            }, $kunciJawabanIndices);
            $kunciJawabanText = implode(', ', $kunciJawabanLetters);
        } else {
            // Single answer: one index
            $kunciJawabanIndices = [$validated['kunci_jawaban']];
            $kunciJawabanText = $letters[$validated['kunci_jawaban']] ?? 'A';
        }
        
        $updateData['kunci_jawaban'] = $kunciJawabanText;

        // Update soal
        $soal->update($updateData);

        // Delete existing pilihan jawaban
        $soal->pilihanJawaban()->delete();

        // Create new pilihan jawaban
        foreach ($validated['pilihan'] as $index => $pilihan) {
            PilihanJawaban::create([
                'soal_id' => $soal->id,
                'pilihan' => $pilihan,
                'is_correct' => in_array($index, $kunciJawabanIndices),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil diupdate',
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

    public function export(\App\Models\Ujian $ujian)
    {
        $ujian->load('soal.pilihanJawaban');
        
        return Excel::download(
            new \App\Exports\SoalExport($ujian->id), 
            'soal_' . \Illuminate\Support\Str::slug($ujian->judul) . '.xlsx'
        );
    }

    public function addFromBank(Request $request)
    {
        try {
            $validated = $request->validate([
                'ujian_id' => 'required|exists:ujian,id',
                'bank_soal_ids' => 'required|array',
                'bank_soal_ids.*' => 'required|exists:bank_soal,id',
            ]);

            $ujian = \App\Models\Ujian::findOrFail($validated['ujian_id']);
            $bankSoals = \App\Models\BankSoal::whereIn('id', $validated['bank_soal_ids'])->get();

            $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

            foreach ($bankSoals as $bankSoal) {
                // Determine kunci_jawaban from jawaban_benar
                // jawaban_benar bisa berupa array [0, 1] atau integer 0
                $jawabanBenar = $bankSoal->jawaban_benar;
                
                // Normalize jawaban_benar ke array
                if (!is_array($jawabanBenar)) {
                    $jawabanBenar = $jawabanBenar !== null ? [$jawabanBenar] : [];
                }
                
                $kunciJawabanLetters = array_map(function($index) use ($letters) {
                    return $letters[$index] ?? 'A';
                }, $jawabanBenar);
                $kunciJawabanText = implode(', ', $kunciJawabanLetters);

                // Determine tipe_soal based on jawaban_benar count
                $tipeSoal = count($jawabanBenar) > 1 ? 'multiple' : 'single';

                // Create soal from bank soal
                $soal = Soal::create([
                    'ujian_id' => $ujian->id,
                    'kursus_id' => $ujian->kursus_id,
                    'pertanyaan' => $bankSoal->pertanyaan,
                    'tipe_soal' => $tipeSoal,
                    'lampiran_foto' => $bankSoal->lampiran,
                    'kunci_jawaban' => $kunciJawabanText,
                ]);

                // Copy pilihan jawaban from opsi_jawaban array
                $opsiJawaban = $bankSoal->opsi_jawaban ?? [];
                foreach ($opsiJawaban as $index => $pilihan) {
                    PilihanJawaban::create([
                        'soal_id' => $soal->id,
                        'pilihan' => $pilihan,
                        'is_correct' => in_array($index, $jawabanBenar),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($bankSoals) . ' soal berhasil ditambahkan dari bank soal'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
