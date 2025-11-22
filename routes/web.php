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


// Breeze auth routes (login, register, password reset, etc.)

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Check if user has admin or super admin role
    if ($user->hasAnyRole(['admin', 'super admin'])) {
        // Admin Dashboard - Get stats
        $totalPeserta = \App\Models\User::role('peserta')->count();
        $totalPengajar = \App\Models\User::role('pengajar')->count();
        $totalKursus = \App\Models\Kursus::count();
        
        return view('admin.dashboard', compact('totalPeserta', 'totalPengajar', 'totalKursus'));
    }
    
    // Student Dashboard
    return view('dashboard');
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
    });
    
    // Admin routes - Routes yang hanya untuk admin dan pengajar
    Route::middleware('role:admin|super admin|pengajar')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/peserta', [\App\Http\Controllers\Admin\PesertaController::class, 'index'])->name('peserta.index');
        Route::get('/peserta/data', [\App\Http\Controllers\Admin\PesertaController::class, 'getData'])->name('peserta.data');
        Route::get('/pengajar', [\App\Http\Controllers\Admin\PengajarController::class, 'index'])->name('pengajar.index');
<<<<<<< HEAD
        Route::post('/pengajar', [\App\Http\Controllers\Admin\PengajarController::class, 'store'])->name('pengajar.store');
        Route::put('/pengajar/{id}', [\App\Http\Controllers\Admin\PengajarController::class, 'update'])->name('pengajar.update');
        
        // Pelatihan/Kursus CUD routes (Create, Update, Delete)
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
        
        // Materi (PDF) CUD routes
        Route::post('/materi', [\App\Http\Controllers\Admin\MateriController::class, 'store'])->name('materi.store');
        Route::get('/materi/{id}/edit', [\App\Http\Controllers\Admin\MateriController::class, 'edit'])->name('materi.edit');
        Route::put('/materi/{id}', [\App\Http\Controllers\Admin\MateriController::class, 'update'])->name('materi.update');
        Route::delete('/materi/{id}', [\App\Http\Controllers\Admin\MateriController::class, 'destroy'])->name('materi.destroy');
        
        // Ujian CUD routes
        Route::post('/ujian', [\App\Http\Controllers\Admin\UjianController::class, 'store'])->name('ujian.store');
        Route::get('/ujian/{id}/edit', [\App\Http\Controllers\Admin\UjianController::class, 'edit'])->name('ujian.edit');
        Route::put('/ujian/{id}', [\App\Http\Controllers\Admin\UjianController::class, 'update'])->name('ujian.update');
        Route::delete('/ujian/{id}', [\App\Http\Controllers\Admin\UjianController::class, 'destroy'])->name('ujian.destroy');
        
        // Soal routes
        Route::post('/soal', [\App\Http\Controllers\Admin\SoalController::class, 'store'])->name('soal.store');
        Route::delete('/soal/{id}', [\App\Http\Controllers\Admin\SoalController::class, 'destroy'])->name('soal.destroy');
        
=======
        Route::get('/pengajar/data', [\App\Http\Controllers\Admin\PengajarController::class, 'getData'])->name('pengajar.data');
        Route::post('/pengajar', [\App\Http\Controllers\Admin\PengajarController::class, 'store'])->name('pengajar.store');
        Route::put('/pengajar/{id}', [\App\Http\Controllers\Admin\PengajarController::class, 'update'])->name('pengajar.update');
        Route::delete('/pengajar/{id}', [\App\Http\Controllers\Admin\PengajarController::class, 'destroy'])->name('pengajar.destroy');
        Route::get('/pelatihan', [\App\Http\Controllers\Admin\PelatihanController::class, 'index'])->name('pelatihan.index');
>>>>>>> 33de6b98a48a196f91f1255ae3d986a902e7243d
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
    });
});

require __DIR__.'/auth.php';
