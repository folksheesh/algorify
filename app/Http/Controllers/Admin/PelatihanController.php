<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kursus;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Nilai;
use App\Models\KategoriPelatihan;
use App\Repositories\ProgressRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PelatihanController extends Controller
{
    protected ProgressRepository $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    public function index(Request $request)
    {
        // Get all courses with their related data
        $query = Kursus::with(['pengajar', 'modul', 'enrollments']);
        
        // Apply kategori filter (supports multiple values separated by comma)
        if ($request->filled('kategori')) {
            $kategoriValues = array_filter(explode(',', $request->kategori));
            if (count($kategoriValues) > 0) {
                $query->whereIn('kategori', $kategoriValues);
            }
        }

        // Apply pengajar filter (supports multiple values separated by comma)
        if ($request->filled('pengajar_id')) {
            $pengajarValues = array_filter(explode(',', $request->pengajar_id));
            if (count($pengajarValues) > 0) {
                $query->whereIn('user_id', $pengajarValues);
            }
        }

        // Apply tipe kursus filter (supports multiple values separated by comma)
        if ($request->filled('tipe_kursus')) {
            $tipeValues = array_filter(explode(',', $request->tipe_kursus));
            if (count($tipeValues) > 0) {
                $query->whereIn('tipe_kursus', $tipeValues);
            }
        }
        
        $kursus = $query->orderBy('created_at', 'desc')->get();
        
        // Get all users with pengajar role
        $pengajars = User::role('pengajar')->orWhere('id', Auth::id())->get();
        
        // Get categories from database (KategoriPelatihan model)
        $kategoris = KategoriPelatihan::orderBy('nama_kategori', 'asc')->get();
        
        return view('admin.pelatihan.index', compact('kursus', 'pengajars', 'kategoris'));
    }

    public function show($id)
    {
        $kursus = Kursus::with(['pengajar', 'modul' => function($query) {
            $query->orderBy('urutan', 'asc')->with([
                'materi' => function($q) {
                    $q->orderBy('urutan', 'asc');
                },
                'video' => function($q) {
                    $q->orderBy('urutan', 'asc');
                },
                'ujian'
            ]);
        }])->findOrFail($id);
        
        // Get user's progress data if user is enrolled
        $userProgress = null;
        $completedItems = [];
        
        if (Auth::check()) {
            $userId = Auth::id();
            $userProgress = $this->progressRepository->calculateProgress($userId, $id);
            $completedItems = $this->progressRepository->getCompletedItems($userId, $id);
        }
        
        return view('admin.pelatihan.show', compact('kursus', 'userProgress', 'completedItems'));
    }

    public function store(Request $request)
    {
        // Normalisasi input numerik
        $request->merge([
            'durasi' => $request->durasi !== null ? preg_replace('/[^0-9]/', '', $request->durasi) : null,
            'harga' => $request->harga !== null ? preg_replace('/[^0-9]/', '', $request->harga) : null,
            'kapasitas' => $request->kapasitas !== null ? preg_replace('/[^0-9]/', '', $request->kapasitas) : null,
        ]);

        // Get valid category slugs from database
        $validKategoris = KategoriPelatihan::pluck('slug')->toArray();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', $validKategoris),
            'tipe_kursus' => 'required|in:online,hybrid,offline',
            'deskripsi' => 'nullable|string',
            'pengajar_id' => 'required|exists:users,id',
            'durasi' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'kapasitas' => 'nullable|integer|min:1',
            // Batas ukuran thumbnail dinaikkan menjadi 1MB (1024 KB)
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        // Set the instructor from form
        $validated['user_id'] = $request->pengajar_id;
        
        // Get pengajar name and store it
        $pengajar = User::find($request->pengajar_id);
        $validated['pengajar'] = $pengajar->name;
        
        // Map tipe_kursus to status for database
        $validated['status'] = 'published'; // Default status

        Kursus::create($validated);

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Kursus berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kursus = Kursus::findOrFail($id);
        return response()->json($kursus);
    }

    public function update(Request $request, $id)
    {
        $kursus = Kursus::findOrFail($id);

        // Normalisasi input numerik
        $request->merge([
            'durasi' => $request->durasi !== null ? preg_replace('/[^0-9]/', '', $request->durasi) : null,
            'harga' => $request->harga !== null ? preg_replace('/[^0-9]/', '', $request->harga) : null,
            'kapasitas' => $request->kapasitas !== null ? preg_replace('/[^0-9]/', '', $request->kapasitas) : null,
        ]);

        // Get valid category slugs from database
        $validKategoris = KategoriPelatihan::pluck('slug')->toArray();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', $validKategoris),
            'tipe_kursus' => 'required|in:online,hybrid,offline',
            'deskripsi' => 'nullable|string',
            'pengajar_id' => 'required|exists:users,id',
            'durasi' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'kapasitas' => 'nullable|integer|min:1',
            // Batas ukuran thumbnail dinaikkan menjadi 1MB (1024 KB)
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($kursus->thumbnail) {
                Storage::disk('public')->delete($kursus->thumbnail);
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }
        
        // Set the instructor from form
        $validated['user_id'] = $request->pengajar_id;
        
        // Get pengajar name and store it
        $pengajar = User::find($request->pengajar_id);
        $validated['pengajar'] = $pengajar->name;
        
        // Map tipe_kursus to status for database
        $validated['status'] = $kursus->status; // Keep existing status

        $kursus->update($validated);

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Kursus berhasil diupdate');
    }

    public function destroy($id)
    {
        $kursus = Kursus::findOrFail($id);

        // Delete thumbnail if exists
        if ($kursus->thumbnail) {
            Storage::disk('public')->delete($kursus->thumbnail);
        }

        $kursus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil dihapus'
        ]);
    }

    /**
     * Show peserta (students) enrolled in the course with their grades
     */
    public function peserta($id)
    {
        $kursus = Kursus::with(['modul.ujian'])->findOrFail($id);
        
        // Get all enrollments for this course with user data
        $enrollments = Enrollment::where('kursus_id', $id)
            ->with('user')
            ->get();
        
        // Get all ujian (exams) for this course
        $ujianList = $kursus->modul->flatMap(function($modul) {
            return $modul->ujian;
        });
        
        // Prepare peserta data with their grades
        $pesertaData = $enrollments->map(function($enrollment) use ($ujianList) {
            $user = $enrollment->user;
            
            // Get nilai for each ujian
            $nilaiData = [];
            foreach ($ujianList as $ujian) {
                $nilai = Nilai::where('user_id', $user->id)
                    ->where('ujian_id', $ujian->id)
                    ->first();
                
                $nilaiData[] = [
                    'ujian_id' => $ujian->id,
                    'ujian_judul' => $ujian->judul,
                    'ujian_tipe' => $ujian->tipe,
                    'nilai' => $nilai ? $nilai->nilai : null,
                    'status' => $nilai ? $nilai->status : 'belum',
                ];
            }
            
            // Calculate average
            $nilaiValues = collect($nilaiData)->pluck('nilai')->filter()->values();
            $rataRata = $nilaiValues->count() > 0 ? $nilaiValues->avg() : null;
            
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => $user->foto_profil,
                'tanggal_daftar' => $enrollment->tanggal_daftar,
                'progress' => $enrollment->progress,
                'nilai_list' => $nilaiData,
                'rata_rata' => $rataRata,
            ];
        });
        
        return view('admin.pelatihan.peserta', compact('kursus', 'pesertaData', 'ujianList'));
    }
}
