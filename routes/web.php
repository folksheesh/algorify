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
    
    // Admin routes
    Route::middleware('role:admin|super admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/peserta', [\App\Http\Controllers\Admin\PesertaController::class, 'index'])->name('peserta.index');
        Route::get('/peserta/data', [\App\Http\Controllers\Admin\PesertaController::class, 'getData'])->name('peserta.data');
        Route::get('/pengajar', [\App\Http\Controllers\Admin\PengajarController::class, 'index'])->name('pengajar.index');
        Route::get('/pelatihan', [\App\Http\Controllers\Admin\PelatihanController::class, 'index'])->name('pelatihan.index');
        Route::get('/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/analitik', [\App\Http\Controllers\Admin\AnalitikController::class, 'index'])->name('analitik.index');
        Route::get('/sertifikat', [\App\Http\Controllers\Admin\SertifikatController::class, 'index'])->name('sertifikat.index');
    });
    
    // User routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/pelatihan-saya', [\App\Http\Controllers\User\PelatihanSayaController::class, 'index'])->name('pelatihan-saya.index');
        Route::get('/sertifikat', [\App\Http\Controllers\User\SertifikatSayaController::class, 'index'])->name('sertifikat.index');
    });
});

require __DIR__.'/auth.php';
