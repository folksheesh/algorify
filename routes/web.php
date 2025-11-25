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
        Route::post('/sertifikat/upload-signature', [\App\Http\Controllers\Admin\SertifikatController::class, 'uploadSignature'])->name('sertifikat.upload-signature');
        Route::delete('/sertifikat/delete-signature', [\App\Http\Controllers\Admin\SertifikatController::class, 'deleteSignature'])->name('sertifikat.delete-signature');
    });
    
    // User routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/pelatihan-saya', [\App\Http\Controllers\User\PelatihanSayaController::class, 'index'])->name('pelatihan-saya.index');
        Route::get('/sertifikat', [\App\Http\Controllers\User\SertifikatSayaController::class, 'index'])->name('sertifikat.index');
        
        // Enrollment and Payment routes
        Route::get('/kursus/{id}/pembayaran', [\App\Http\Controllers\User\EnrollmentController::class, 'showPayment'])->name('kursus.pembayaran');
        Route::post('/kursus/{id}/enroll', [\App\Http\Controllers\User\EnrollmentController::class, 'enroll'])->name('kursus.enroll');
        Route::get('/transaksi/{kode_transaksi}/status', [\App\Http\Controllers\User\EnrollmentController::class, 'checkStatus'])->name('transaksi.status');
    });
});

// DOKU notification webhook (outside auth middleware)
Route::post('/doku/notification', [\App\Http\Controllers\User\EnrollmentController::class, 'notification'])->name('doku.notification');

// Debug DOKU Signature
Route::get('/debug/doku-test', function() {
    $body = [
        'order' => [
            'amount' => 700000,
            'invoice_number' => 'TEST-' . time(),
            'currency' => 'IDR',
        ],
        'payment' => [
            'payment_due_date' => 60,
        ],
        'customer' => [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ],
    ];
    
    $path = '/checkout/v1/payment';
    $sig = \App\Services\DokuSignatureService::generate($body, $path);
    
    $endpoint = config('doku.base_url') . $path;
    
    $response = \Http::withHeaders([
        'Client-Id' => config('doku.client_id'),
        'Request-Id' => $sig['request_id'],
        'Request-Timestamp' => $sig['request_timestamp'],
        'Signature' => $sig['signature'],
        'Content-Type' => 'application/json',
    ])
    ->withOptions(['verify' => false])
    ->withBody($sig['json_body'], 'application/json')
    ->post($endpoint);
    
    return response()->json([
        'request' => [
            'endpoint' => $endpoint,
            'client_id' => config('doku.client_id'),
            'headers' => [
                'Request-Id' => $sig['request_id'],
                'Request-Timestamp' => $sig['request_timestamp'],
                'Signature' => $sig['signature'],
            ],
            'body' => $sig['json_body'],
        ],
        'response' => [
            'status' => $response->status(),
            'body' => $response->json(),
        ],
    ], 200, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

// Update transaction status manually (for testing)
Route::get('/debug/update-transaction/{kode}', function($kode) {
    $transaksi = \App\Models\Transaksi::where('kode_transaksi', $kode)->first();
    
    if (!$transaksi) {
        return response()->json(['error' => 'Transaction not found'], 404);
    }
    
    $transaksi->status = 'success';
    $transaksi->save();
    
    // Activate enrollment
    $enrollment = \App\Models\Enrollment::where('user_id', $transaksi->user_id)
        ->where('kursus_id', $transaksi->kursus_id)
        ->first();
    
    if ($enrollment) {
        $enrollment->status = 'active';
        $enrollment->save();
    } else {
        $enrollment = \App\Models\Enrollment::create([
            'user_id' => $transaksi->user_id,
            'kursus_id' => $transaksi->kursus_id,
            'tanggal_daftar' => now(),
            'status' => 'active',
            'progress' => 0,
        ]);
    }
    
    return response()->json([
        'success' => true,
        'transaction' => $transaksi,
        'enrollment' => $enrollment,
    ]);
});

require __DIR__.'/auth.php';
