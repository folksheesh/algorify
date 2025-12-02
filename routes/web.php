<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});
// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// DOKU Payment Callback (no auth required)
Route::get('/payment/callback', [\App\Http\Controllers\User\EnrollmentController::class, 'paymentCallback'])->name('payment.callback');
Route::post('/doku/notification', [\App\Http\Controllers\User\EnrollmentController::class, 'notification'])->name('doku.notification');

// Breeze auth routes (login, register, password reset, etc.)

Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    // Check if user has admin or super admin role
    if ($user->hasAnyRole(['admin', 'super admin'])) {
        // Admin Dashboard - Get stats
        $totalPeserta = \App\Models\User::role('peserta')->count();
        $totalPengajar = \App\Models\User::role('pengajar')->count();
        $totalKursus = \App\Models\Kursus::count();
        
        return view('admin.dashboard', compact('totalPeserta', 'totalPengajar', 'totalKursus'));
    }
    
    // Pengajar Dashboard
    if ($user->hasRole('pengajar')) {
        $totalKursus = \App\Models\Kursus::count();
        $totalSiswa = \App\Models\User::role('peserta')->count();
        
        // Get kategori stats based on enum field in kursus table
        $kategoriEnum = ['programming', 'design', 'business', 'marketing'];
        $kategoriNames = [
            'programming' => 'Programming',
            'design' => 'Design', 
            'business' => 'Business',
            'marketing' => 'Marketing',
        ];
        
        $kategoriCounts = [];
        foreach ($kategoriEnum as $kat) {
            $kategoriCounts[$kat] = \App\Models\Kursus::where('kategori', $kat)->count();
        }
        $maxKursus = max($kategoriCounts) ?: 1;
        
        $kategoriStats = collect($kategoriEnum)->map(function($slug) use ($kategoriCounts, $kategoriNames, $maxKursus) {
            return [
                'nama' => $kategoriNames[$slug],
                'slug' => $slug,
                'total' => $kategoriCounts[$slug],
                'percentage' => $maxKursus > 0 ? round(($kategoriCounts[$slug] / $maxKursus) * 100) : 0,
            ];
        });
        
        // Get popular courses
        $kursusPopuler = \App\Models\Kursus::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(4)
            ->get();
        
        // Get monthly performance (last 6 months)
        $performaBulanan = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanNama = $date->locale('id')->translatedFormat('M');
            
            $siswaCount = \App\Models\User::role('peserta')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $kursusCount = \App\Models\Kursus::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $performaBulanan->push([
                'nama' => $bulanNama,
                'siswa' => $siswaCount,
                'kursus' => $kursusCount,
            ]);
        }
        
        return view('pengajar.dashboard', compact(
            'totalKursus', 
            'totalSiswa', 
            'kategoriStats', 
            'kursusPopuler',
            'performaBulanan'
        ));
    }
    
    // Student Dashboard - Get user's enrollments
    $enrollments = \App\Models\Enrollment::where('user_id', $user->id)
        ->with(['kursus'])
        ->latest()
        ->get();
    
    // Get recommended courses (latest courses that user hasn't enrolled in)
    $enrolledKursusIds = $enrollments->pluck('kursus_id')->toArray();
    $recommendedCourses = \App\Models\Kursus::whereNotIn('id', $enrolledKursusIds)
        ->latest()
        ->limit(6)
        ->get();
    
    return view('dashboard', compact('enrollments', 'recommendedCourses'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Kursus routes
    Route::get('/kursus', [\App\Http\Controllers\KursusController::class, 'index'])->name('kursus.index');
    Route::get('/kursus/{id}', [\App\Http\Controllers\KursusController::class, 'show'])->name('kursus.show');
    
    // Admin routes - Routes yang dapat diakses semua role (view only)
    Route::prefix('admin')->name('admin.')->group(function () {
        // View routes - accessible by all authenticated users
        Route::get('/pelatihan', [\App\Http\Controllers\Admin\PelatihanController::class, 'index'])->name('pelatihan.index');
        Route::get('/pelatihan/{id}', [\App\Http\Controllers\Admin\PelatihanController::class, 'show'])->name('pelatihan.show');
        Route::get('/video/{id}', [\App\Http\Controllers\Admin\VideoController::class, 'show'])->name('video.show');
        Route::get('/materi/{id}', [\App\Http\Controllers\Admin\MateriController::class, 'show'])->name('materi.show');
        Route::get('/ujian/{id}', [\App\Http\Controllers\Admin\UjianController::class, 'show'])->name('ujian.show');
        
        // Dashboard API routes
        Route::get('/dashboard/transaksi-data', [\App\Http\Controllers\Admin\DashboardController::class, 'getTransaksiData'])->name('dashboard.transaksi-data');
        Route::get('/dashboard/pertumbuhan-data', [\App\Http\Controllers\Admin\DashboardController::class, 'getPertumbuhanData'])->name('dashboard.pertumbuhan-data');
    });
    
    // Super Admin only routes - Routes yang hanya untuk super admin
    Route::middleware('role:super admin')->prefix('admin')->name('admin.')->group(function () {
        // Data Admin
        Route::get('/admin', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/data', [\App\Http\Controllers\Admin\AdminController::class, 'getData'])->name('admin.data');
        Route::post('/admin', [\App\Http\Controllers\Admin\AdminController::class, 'store'])->name('admin.store');
        Route::put('/admin/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admin.update');
        Route::delete('/admin/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'destroy'])->name('admin.destroy');
    });

    // Admin routes - Routes yang hanya untuk admin dan pengajar
    Route::middleware('role:admin|super admin|pengajar')->prefix('admin')->name('admin.')->group(function () {
        // Data Peserta
        Route::get('/peserta', [\App\Http\Controllers\Admin\PesertaController::class, 'index'])->name('peserta.index');
        Route::get('/peserta/data', [\App\Http\Controllers\Admin\PesertaController::class, 'getData'])->name('peserta.data');
        Route::get('/peserta/{id}', [\App\Http\Controllers\Admin\PesertaController::class, 'show'])->name('peserta.show');
        
        // Data Pengajar
        Route::get('/pengajar', [\App\Http\Controllers\Admin\PengajarController::class, 'index'])->name('pengajar.index');
        Route::get('/pengajar/data', [\App\Http\Controllers\Admin\PengajarController::class, 'getData'])->name('pengajar.data');
        Route::post('/pengajar', [\App\Http\Controllers\Admin\PengajarController::class, 'store'])->name('pengajar.store');
        Route::get('/pengajar/{id}', [\App\Http\Controllers\Admin\PengajarController::class, 'show'])->name('pengajar.show');
        Route::put('/pengajar/{id}', [\App\Http\Controllers\Admin\PengajarController::class, 'update'])->name('pengajar.update');
        Route::delete('/pengajar/{id}', [\App\Http\Controllers\Admin\PengajarController::class, 'destroy'])->name('pengajar.destroy');
        
        // Pelatihan/Kursus CUD routes (Create, Update, Delete)
        Route::get('/pelatihan/{id}/peserta', [\App\Http\Controllers\Admin\PelatihanController::class, 'peserta'])->name('pelatihan.peserta');
        Route::post('/pelatihan', [\App\Http\Controllers\Admin\PelatihanController::class, 'store'])->name('pelatihan.store');
        Route::get('/pelatihan/{id}/edit', [\App\Http\Controllers\Admin\PelatihanController::class, 'edit'])->name('pelatihan.edit');
        Route::put('/pelatihan/{id}', [\App\Http\Controllers\Admin\PelatihanController::class, 'update'])->name('pelatihan.update');
        Route::delete('/pelatihan/{id}', [\App\Http\Controllers\Admin\PelatihanController::class, 'destroy'])->name('pelatihan.destroy');
        
        // Modul CRUD routes
        Route::post('/modul', [\App\Http\Controllers\Admin\ModulController::class, 'store'])->name('modul.store');
        Route::get('/modul/{id}/edit', [\App\Http\Controllers\Admin\ModulController::class, 'edit'])->name('modul.edit');
        Route::put('/modul/{id}', [\App\Http\Controllers\Admin\ModulController::class, 'update'])->name('modul.update');
        Route::delete('/modul/{id}', [\App\Http\Controllers\Admin\ModulController::class, 'destroy'])->name('modul.destroy');
        
        // Video CUD routes
        Route::post('/video', [\App\Http\Controllers\Admin\VideoController::class, 'store'])->name('video.store');
        Route::get('/video/{id}/edit', [\App\Http\Controllers\Admin\VideoController::class, 'edit'])->name('video.edit');
        Route::put('/video/{id}', [\App\Http\Controllers\Admin\VideoController::class, 'update'])->name('video.update');
        Route::delete('/video/{id}', [\App\Http\Controllers\Admin\VideoController::class, 'destroy'])->name('video.destroy');
        
        // Materi (Reading Content) CUD routes
        Route::post('/materi', [\App\Http\Controllers\Admin\MateriController::class, 'store'])->name('materi.store');
        Route::get('/materi/{id}/edit', [\App\Http\Controllers\Admin\MateriController::class, 'edit'])->name('materi.edit');
        Route::put('/materi/{id}', [\App\Http\Controllers\Admin\MateriController::class, 'update'])->name('materi.update');
        Route::delete('/materi/{id}', [\App\Http\Controllers\Admin\MateriController::class, 'destroy'])->name('materi.destroy');
        Route::post('/materi/upload-image', [\App\Http\Controllers\Admin\MateriController::class, 'uploadImage'])->name('materi.upload-image');
        
        // Ujian CUD routes
        Route::post('/ujian', [\App\Http\Controllers\Admin\UjianController::class, 'store'])->name('ujian.store');
        Route::get('/ujian/{id}/edit', [\App\Http\Controllers\Admin\UjianController::class, 'edit'])->name('ujian.edit');
        Route::put('/ujian/{id}', [\App\Http\Controllers\Admin\UjianController::class, 'update'])->name('ujian.update');
        Route::delete('/ujian/{id}', [\App\Http\Controllers\Admin\UjianController::class, 'destroy'])->name('ujian.destroy');
        
        // Soal routes
        Route::post('/soal', [\App\Http\Controllers\Admin\SoalController::class, 'store'])->name('soal.store');
        Route::get('/soal/{id}/edit', [\App\Http\Controllers\Admin\SoalController::class, 'edit'])->name('soal.edit');
        Route::put('/soal/{id}', [\App\Http\Controllers\Admin\SoalController::class, 'update'])->name('soal.update');
        Route::delete('/soal/{id}', [\App\Http\Controllers\Admin\SoalController::class, 'destroy'])->name('soal.destroy');
        Route::get('/soal/template', [\App\Http\Controllers\Admin\SoalController::class, 'downloadTemplate'])->name('soal.template');
        Route::post('/soal/import', [\App\Http\Controllers\Admin\SoalController::class, 'import'])->name('soal.import');
        Route::get('/soal/export/{ujianId}', [\App\Http\Controllers\Admin\SoalController::class, 'export'])->name('soal.export');
        Route::post('/soal/add-from-bank', [\App\Http\Controllers\Admin\SoalController::class, 'addFromBank'])->name('soal.add-from-bank');
        
        // Urutan routes (drag and drop)
        Route::post('/urutan/modul', [\App\Http\Controllers\Admin\UrutanController::class, 'updateModulOrder'])->name('urutan.modul');
        Route::post('/urutan/materi', [\App\Http\Controllers\Admin\UrutanController::class, 'updateMateriOrder'])->name('urutan.materi');
        Route::post('/urutan/video', [\App\Http\Controllers\Admin\UrutanController::class, 'updateVideoOrder'])->name('urutan.video');
        
        // Bank Soal routes
        Route::get('/bank-soal', [\App\Http\Controllers\Admin\BankSoalController::class, 'index'])->name('bank-soal.index');
        Route::post('/bank-soal', [\App\Http\Controllers\Admin\BankSoalController::class, 'store'])->name('bank-soal.store');
        Route::get('/bank-soal/{id}/edit', [\App\Http\Controllers\Admin\BankSoalController::class, 'edit'])->name('bank-soal.edit');
        Route::put('/bank-soal/{id}', [\App\Http\Controllers\Admin\BankSoalController::class, 'update'])->name('bank-soal.update');
        Route::delete('/bank-soal/{id}', [\App\Http\Controllers\Admin\BankSoalController::class, 'destroy'])->name('bank-soal.destroy');
        Route::get('/bank-soal/kategori/{kategoriId}', [\App\Http\Controllers\Admin\BankSoalController::class, 'getByKategori'])->name('bank-soal.by-kategori');
        
        // Kategori Soal routes
        Route::get('/kategori-soal', [\App\Http\Controllers\Admin\KategoriSoalController::class, 'index'])->name('kategori-soal.index');
        Route::post('/kategori-soal', [\App\Http\Controllers\Admin\KategoriSoalController::class, 'store'])->name('kategori-soal.store');
        Route::put('/kategori-soal/{id}', [\App\Http\Controllers\Admin\KategoriSoalController::class, 'update'])->name('kategori-soal.update');
        Route::delete('/kategori-soal/{id}', [\App\Http\Controllers\Admin\KategoriSoalController::class, 'destroy'])->name('kategori-soal.destroy');
        
        // Bank Soal routes
        Route::get('/bank-soal', [\App\Http\Controllers\Admin\BankSoalController::class, 'index'])->name('bank-soal.index');
        Route::get('/bank-soal/data', [\App\Http\Controllers\Admin\BankSoalController::class, 'getData'])->name('bank-soal.data');
        Route::get('/bank-soal/kursus-list', [\App\Http\Controllers\Admin\BankSoalController::class, 'getKursusList'])->name('bank-soal.kursus-list');
        Route::get('/bank-soal/creators-list', [\App\Http\Controllers\Admin\BankSoalController::class, 'getCreatorsList'])->name('bank-soal.creators-list');
        Route::post('/bank-soal', [\App\Http\Controllers\Admin\BankSoalController::class, 'store'])->name('bank-soal.store');
        Route::get('/bank-soal/{id}', [\App\Http\Controllers\Admin\BankSoalController::class, 'show'])->name('bank-soal.show');
        Route::put('/bank-soal/{id}', [\App\Http\Controllers\Admin\BankSoalController::class, 'update'])->name('bank-soal.update');
        Route::delete('/bank-soal/{id}', [\App\Http\Controllers\Admin\BankSoalController::class, 'destroy'])->name('bank-soal.destroy');
        
        // Kategori Pelatihan routes
        Route::get('/kategori', [\App\Http\Controllers\Admin\KategoriController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/data', [\App\Http\Controllers\Admin\KategoriController::class, 'getData'])->name('kategori.data');
        Route::post('/kategori', [\App\Http\Controllers\Admin\KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{id}', [\App\Http\Controllers\Admin\KategoriController::class, 'show'])->name('kategori.show');
        Route::put('/kategori/{id}', [\App\Http\Controllers\Admin\KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [\App\Http\Controllers\Admin\KategoriController::class, 'destroy'])->name('kategori.destroy');
        
        Route::get('/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/analitik', [\App\Http\Controllers\Admin\AnalitikController::class, 'index'])->name('analitik.index');
        Route::get('/sertifikat', [\App\Http\Controllers\Admin\SertifikatController::class, 'index'])->name('sertifikat.index');
        Route::post('/sertifikat/upload-signature', [\App\Http\Controllers\Admin\SertifikatController::class, 'uploadSignature'])->name('sertifikat.upload-signature');
        Route::delete('/sertifikat/delete-signature', [\App\Http\Controllers\Admin\SertifikatController::class, 'deleteSignature'])->name('sertifikat.delete-signature');
    });
    
    // User routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/pelatihan-saya', [\App\Http\Controllers\User\PelatihanSayaController::class, 'index'])->name('pelatihan-saya.index');
        Route::get('/sertifikat', [\App\Http\Controllers\User\SertifikatSayaController::class, 'index'])->name('sertifikat.index');
        Route::get('/sertifikat/{id}/download', [\App\Http\Controllers\User\SertifikatSayaController::class, 'download'])->name('sertifikat.download');
        Route::get('/sertifikat/{id}/preview', [\App\Http\Controllers\User\SertifikatSayaController::class, 'preview'])->name('sertifikat.preview');
        Route::post('/sertifikat/{enrollmentId}/generate', [\App\Http\Controllers\User\SertifikatSayaController::class, 'generate'])->name('sertifikat.generate');
        
        // Enrollment and Payment routes
        Route::get('/kursus/{id}/pembayaran', [\App\Http\Controllers\User\EnrollmentController::class, 'showPayment'])->name('kursus.pembayaran');
        Route::post('/kursus/{id}/enroll', [\App\Http\Controllers\User\EnrollmentController::class, 'enroll'])->name('kursus.enroll');
        
        // Payment status and completion routes
        Route::post('/pembayaran/{kode_transaksi}/complete', [\App\Http\Controllers\User\EnrollmentController::class, 'completePayment'])->name('pembayaran.complete');
        Route::get('/pembayaran/{kode_transaksi}/status', [\App\Http\Controllers\User\EnrollmentController::class, 'checkPaymentStatus'])->name('pembayaran.status');
        
        Route::get('/transaksi/{kode_transaksi}/status', [\App\Http\Controllers\User\EnrollmentController::class, 'checkStatus'])->name('transaksi.status');
        
        // Ujian routes
        Route::post('/ujian/{id}/submit', [\App\Http\Controllers\User\UjianController::class, 'submit'])->name('ujian.submit');
        Route::get('/ujian/{id}/result', [\App\Http\Controllers\User\UjianController::class, 'result'])->name('ujian.result');
    });
});

require __DIR__.'/auth.php';
