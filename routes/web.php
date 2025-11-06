<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Public certificate verification (no auth required)
Route::get('/verifikasi-sertifikat', [\App\Http\Controllers\SertifikatController::class, 'verifyForm'])->name('sertifikat.verify.form');
Route::post('/verifikasi-sertifikat', [\App\Http\Controllers\SertifikatController::class, 'verify'])->name('sertifikat.verify');
// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');


// Breeze auth routes (login, register, password reset, etc.)

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Pelatihan (my trainings)
    Route::get('/pelatihan', [\App\Http\Controllers\PelatihanController::class, 'index'])->name('pelatihan.index');
    Route::get('/pelatihan/{id}', [\App\Http\Controllers\PelatihanController::class, 'show'])->name('pelatihan.show');
    
    // Kursus routes
    Route::get('/kursus', [\App\Http\Controllers\KursusController::class, 'index'])->name('kursus.index');
    Route::get('/kursus/{id}', [\App\Http\Controllers\KursusController::class, 'show'])->name('kursus.show');
    // Enrollment / Pembayaran
    Route::get('/kursus/{id}/daftar', [\App\Http\Controllers\EnrollmentController::class, 'show'])->name('kursus.daftar');
    Route::post('/kursus/{id}/daftar', [\App\Http\Controllers\EnrollmentController::class, 'store'])->name('kursus.daftar.store');
    Route::get('/pembayaran/{id}', [\App\Http\Controllers\EnrollmentController::class, 'status'])->name('pembayaran.status');
    // Dev helper: simulate payment callback (POST)
    Route::post('/pembayaran/{id}/simulate-success', [\App\Http\Controllers\EnrollmentController::class, 'simulateSuccess'])->name('pembayaran.simulate');
    // Sertifikat routes
    Route::get('/sertifikat', [\App\Http\Controllers\SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('/sertifikat/{id}', [\App\Http\Controllers\SertifikatController::class, 'show'])->name('sertifikat.show');
});

require __DIR__.'/auth.php';
