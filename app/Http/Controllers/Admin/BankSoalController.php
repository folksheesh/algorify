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
        // This filters bank soal where the linked kursus has a matching kategori field
        if ($request->has('kategori_nama') && $request->kategori_nama != '') {
            $kategoriNama = $request->kategori_nama;
            $query->where(function($q) use ($kategoriNama) {
                // Filter by kategori_id (which links to kursus with matching kategori)
                $q->whereHas('kategori', function($subQ) use ($kategoriNama) {
                    $subQ->where('kategori', $kategoriNama);
                })
                // Or filter by kursus_id (which links to kursus with matching kategori)
                ->orWhereHas('kursus', function($subQ) use ($kategoriNama) {
                    $subQ->where('kategori', $kategoriNama);
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
                    'kategori' => $item->kategori ? $item->kategori->judul : ($item->kursus ? $item->kursus->judul : '-'),
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
}
