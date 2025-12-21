<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\KategoriPelatihan;
use App\Models\Kursus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BankSoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.bank-soal.index');
    }

    /**
     * Get data for DataTable
     */
    public function getData(Request $request)
    {
        $query = BankSoal::with(['kategori', 'kursus', 'creator']);

        // Search functionality - enhanced to search in kategori, kursus, and tipe
        if ($request->has('search') && $request->search != '') {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(pertanyaan) like ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(tipe_soal) like ?', ["%{$search}%"])
                  ->orWhereHas('kategori', function($q) use ($search) {
                      $q->whereRaw('LOWER(nama_kategori) like ?', ["%{$search}%"]);
                  })
                  ->orWhereHas('kursus', function($q) use ($search) {
                      $q->whereRaw('LOWER(judul) like ?', ["%{$search}%"]);
                  });
            });
        }

        // Filter by tipe soal
        if ($request->has('tipe_soal') && $request->tipe_soal != '') {
            $query->where('tipe_soal', $request->tipe_soal);
        }

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter by kategori name (for modal bank soal in ujian)
        // This filters bank soal where the linked kategori pelatihan matches the provided name
        if ($request->has('kategori_nama') && $request->kategori_nama != '') {
            $kategoriNama = $request->kategori_nama;
            $query->where(function($q) use ($kategoriNama) {
                $q->whereHas('kategori', function($subQ) use ($kategoriNama) {
                    $subQ->where('nama_kategori', $kategoriNama);
                });
            });
        }

        // Filter by kursus
        if ($request->has('kursus') && $request->kursus != '') {
            $query->where('kursus_id', $request->kursus);
        }

        // Filter by creator
        if ($request->has('creator') && $request->creator != '') {
            $query->where('created_by', $request->creator);
        }

        $soal = $query->latest()->get();

        return response()->json([
            'data' => $soal->map(function($item, $index) {
                return [
                    'id' => $item->id,
                    'no' => $index + 1,
                    'pertanyaan' => $item->pertanyaan,
                    'tipe_soal' => $item->tipe_soal,
                    'opsi_jawaban' => $item->opsi_jawaban,
                    'jawaban_benar' => $item->jawaban_benar,
                    'kategori' => $item->kategori ? $item->kategori->nama_kategori : ($item->kursus ? $item->kursus->judul : '-'),
                    'poin' => $item->poin ?? 1,
                    'created_by' => $item->creator ? $item->creator->name : '-',
                    'created_at' => $item->created_at->format('d/m/Y')
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pertanyaan' => 'required|string',
                'tipe_soal' => 'required|in:pilihan_ganda,multi_jawaban,essay',
                'opsi_jawaban' => 'nullable|string',
                'jawaban_benar' => 'nullable|string',
                'kategori_id' => 'nullable|exists:kategori_pelatihan,id',
                'kursus_id' => 'nullable|exists:kursus,id',
                'poin' => 'nullable|integer|min:1|max:5',
                'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ]);

            $data = [
                'pertanyaan' => $validated['pertanyaan'],
                'tipe_soal' => $validated['tipe_soal'],
                'created_by' => Auth::id(),
            ];

            // Add optional fields
            if (isset($validated['kategori_id'])) {
                $data['kategori_id'] = $validated['kategori_id'];
            }
            if (isset($validated['kursus_id'])) {
                $data['kursus_id'] = $validated['kursus_id'];
            }
            if (isset($validated['poin'])) {
                $data['poin'] = $validated['poin'];
            }

            // Handle opsi_jawaban - decode JSON string
            if (isset($validated['opsi_jawaban'])) {
                $data['opsi_jawaban'] = json_decode($validated['opsi_jawaban'], true);
            }

            // Handle jawaban_benar - decode JSON string and generate kunci_jawaban
            if (isset($validated['jawaban_benar'])) {
                $decoded = json_decode($validated['jawaban_benar'], true);
                $data['jawaban_benar'] = $decoded;
                
                // Generate kunci_jawaban string dari opsi yang dipilih
                if (isset($data['opsi_jawaban']) && !empty($data['opsi_jawaban'])) {
                    if (is_array($decoded)) {
                        // Multi jawaban: gabungkan semua jawaban yang benar
                        $kunciArray = [];
                        foreach ($decoded as $index) {
                            if (isset($data['opsi_jawaban'][$index])) {
                                $kunciArray[] = $data['opsi_jawaban'][$index];
                            }
                        }
                        $data['kunci_jawaban'] = implode(', ', $kunciArray);
                    } else {
                        // Pilihan ganda: ambil satu jawaban
                        if (isset($data['opsi_jawaban'][$decoded])) {
                            $data['kunci_jawaban'] = $data['opsi_jawaban'][$decoded];
                        }
                    }
                }
            }

            // Handle file upload
            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('bank-soal', $filename, 'public');
                $data['lampiran'] = $path;
            }

            BankSoal::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil ditambahkan'
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $soal = BankSoal::with(['kategori', 'kursus', 'creator'])->findOrFail($id);
        return response()->json(['data' => $soal]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $soal = BankSoal::findOrFail($id);

            $validated = $request->validate([
                'pertanyaan' => 'required|string',
                'tipe_soal' => 'required|in:pilihan_ganda,multi_jawaban,essay',
                'opsi_jawaban' => 'nullable|string',
                'jawaban_benar' => 'nullable|string',
                'kategori_id' => 'nullable|exists:kategori_pelatihan,id',
                'kursus_id' => 'nullable|exists:kursus,id',
                'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ]);

            $data = [
                'pertanyaan' => $validated['pertanyaan'],
                'tipe_soal' => $validated['tipe_soal'],
            ];

            // Add optional fields
            if (isset($validated['kategori_id'])) {
                $data['kategori_id'] = $validated['kategori_id'];
            }
            if (isset($validated['kursus_id'])) {
                $data['kursus_id'] = $validated['kursus_id'];
            }
            if (isset($validated['poin'])) {
                $data['poin'] = $validated['poin'];
            }

            // Handle opsi_jawaban - decode JSON string
            if (isset($validated['opsi_jawaban'])) {
                $data['opsi_jawaban'] = json_decode($validated['opsi_jawaban'], true);
            }

            // Handle jawaban_benar - decode JSON string and generate kunci_jawaban
            if (isset($validated['jawaban_benar'])) {
                $decoded = json_decode($validated['jawaban_benar'], true);
                $data['jawaban_benar'] = $decoded;
                
                // Generate kunci_jawaban string dari opsi yang dipilih
                if (isset($data['opsi_jawaban']) && !empty($data['opsi_jawaban'])) {
                    if (is_array($decoded)) {
                        // Multi jawaban: gabungkan semua jawaban yang benar
                        $kunciArray = [];
                        foreach ($decoded as $index) {
                            if (isset($data['opsi_jawaban'][$index])) {
                                $kunciArray[] = $data['opsi_jawaban'][$index];
                            }
                        }
                        $data['kunci_jawaban'] = implode(', ', $kunciArray);
                    } else {
                        // Pilihan ganda: ambil satu jawaban
                        if (isset($data['opsi_jawaban'][$decoded])) {
                            $data['kunci_jawaban'] = $data['opsi_jawaban'][$decoded];
                        }
                    }
                }
            }

            // Handle file upload
            if ($request->hasFile('lampiran')) {
                // Delete old file
                if ($soal->lampiran) {
                    Storage::disk('public')->delete($soal->lampiran);
                }

                $file = $request->file('lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('bank-soal', $filename, 'public');
                $data['lampiran'] = $path;
            }

            $soal->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil diperbarui'
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $soal = BankSoal::findOrFail($id);

        // Delete file if exists
        if ($soal->lampiran) {
            Storage::disk('public')->delete($soal->lampiran);
        }

        $soal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dihapus'
        ]);
    }

    /**
     * Get kursus list for filter
     */
    public function getKursusList()
    {
        $kursus = Kursus::select('id', 'judul')->get();
        return response()->json(['data' => $kursus]);
    }

    /**
     * Get creators list for filter
     */
    public function getCreatorsList()
    {
        $creators = BankSoal::with('creator:id,name')
            ->select('created_by')
            ->distinct()
            ->get()
            ->pluck('creator')
            ->filter()
            ->values();
        return response()->json(['data' => $creators]);
    }

    /**
     * Download CSV template for import
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_bank_soal.csv"',
        ];

        $columns = ['pertanyaan', 'tipe_soal', 'opsi_jawaban', 'jawaban_benar', 'poin'];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row - use semicolon for Excel Indonesia
            fputcsv($file, $columns, ';');
            
            // Example rows
            fputcsv($file, [
                'Apa kepanjangan dari HTML?',
                'pilihan_ganda',
                'Hyper Text Markup Language|Hyper Transfer Markup Language|High Text Markup Language|Hyper Text Machine Language',
                '0',
                '1'
            ], ';');
            fputcsv($file, [
                'Manakah yang termasuk bahasa pemrograman?',
                'multi_jawaban',
                'Python|HTML|JavaScript|CSS',
                '0,2',
                '2'
            ], ';');
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export bank soal to Excel with styling
     */
    public function exportCsv(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\BankSoalExport($request), 
            'bank_soal_' . now()->timezone('Asia/Jakarta')->format('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Import bank soal from CSV
     */
    public function importCsv(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt|max:2048'
            ]);

            $file = $request->file('file');
            $path = $file->getRealPath();
            
            // Read file and detect delimiter (semicolon or comma)
            $lines = file($path);
            $firstLine = $lines[0] ?? '';
            $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
            
            $data = array_map(function($line) use ($delimiter) {
                return str_getcsv($line, $delimiter);
            }, $lines);
            
            // Remove header row
            $header = array_shift($data);
            
            $imported = 0;
            $errors = [];
            
            foreach ($data as $index => $row) {
                $rowNum = $index + 2; // +2 because header is row 1 and array is 0-indexed
                
                if (count($row) < 5) {
                    $errors[] = "Baris {$rowNum}: Data tidak lengkap";
                    continue;
                }
                
                $pertanyaan = trim($row[0] ?? '');
                $tipeSoal = trim($row[1] ?? '');
                $opsiStr = trim($row[2] ?? '');
                $jawabanStr = trim($row[3] ?? '');
                $poin = (int) ($row[4] ?? 1);
                
                if (empty($pertanyaan)) {
                    $errors[] = "Baris {$rowNum}: Pertanyaan kosong";
                    continue;
                }
                
                if (!in_array($tipeSoal, ['pilihan_ganda', 'multi_jawaban', 'essay'])) {
                    $errors[] = "Baris {$rowNum}: Tipe soal tidak valid (harus pilihan_ganda, multi_jawaban, atau essay)";
                    continue;
                }
                
                // Parse opsi jawaban (separated by |)
                $opsiJawaban = array_map('trim', explode('|', $opsiStr));
                if ($tipeSoal !== 'essay' && count($opsiJawaban) < 2) {
                    $errors[] = "Baris {$rowNum}: Minimal 2 opsi jawaban untuk soal pilihan";
                    continue;
                }
                
                // Parse jawaban benar (single index or comma-separated for multi)
                $jawabanBenar = null;
                if ($tipeSoal === 'pilihan_ganda') {
                    $jawabanBenar = (int) $jawabanStr;
                } elseif ($tipeSoal === 'multi_jawaban') {
                    $jawabanBenar = array_map('intval', array_map('trim', explode(',', $jawabanStr)));
                }
                
                // Generate kunci_jawaban
                $kunciJawaban = '';
                if ($tipeSoal !== 'essay' && !empty($opsiJawaban)) {
                    if (is_array($jawabanBenar)) {
                        $kunciArray = [];
                        foreach ($jawabanBenar as $idx) {
                            if (isset($opsiJawaban[$idx])) {
                                $kunciArray[] = $opsiJawaban[$idx];
                            }
                        }
                        $kunciJawaban = implode(', ', $kunciArray);
                    } else {
                        $kunciJawaban = $opsiJawaban[$jawabanBenar] ?? '';
                    }
                }
                
                BankSoal::create([
                    'pertanyaan' => $pertanyaan,
                    'tipe_soal' => $tipeSoal,
                    'opsi_jawaban' => $tipeSoal !== 'essay' ? $opsiJawaban : null,
                    'jawaban_benar' => $jawabanBenar,
                    'kunci_jawaban' => $kunciJawaban,
                    'poin' => max(1, min(5, $poin)),
                    'created_by' => Auth::id(),
                ]);
                
                $imported++;
            }
            
            $message = "Berhasil import {$imported} soal.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " baris gagal diimport.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'errors' => $errors
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid. Pastikan file berformat CSV.',
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
