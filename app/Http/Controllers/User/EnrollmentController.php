<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kursus;
use App\Models\Enrollment;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use App\Providers\DokuSignatureService;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Show the payment page for a course
     */
    public function showPayment($id)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $kursus = Kursus::with('pengajar')->findOrFail($id);
        
        // Ensure pengajar is loaded
        if (!$kursus->pengajar) {
            \Log::error("Kursus {$id} has no pengajar (user_id may be invalid)");
            abort(500, 'Data kursus tidak lengkap');
        }
        
        // Check if user already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('kursus_id', $id)
            ->first();
        
        if ($existingEnrollment) {
            return redirect()->route('user.pelatihan-saya.index')
                ->with('info', 'Anda sudah terdaftar di pelatihan ini.');
        }

        // Cek apakah ada transaksi existing
        $transaksi = Transaksi::where('user_id', $user->id)
            ->where('kursus_id', $id)
            ->whereIn('status', ['pending', 'success'])
            ->first();

        // Jika transaksi sudah success, redirect ke pelatihan
        if ($transaksi && $transaksi->status === 'success') {
            return redirect()->route('user.pelatihan-saya.index')
                ->with('success', 'Pembayaran sudah berhasil!');
        }

        // Generate DOKU Payment URL only for pending transactions
        [$paymentUrl, $snapError] = $this->getSnapToken($transaksi, $kursus);
        
        // Pass transaksi ke view (bisa null jika belum ada)
        return view('user.pembayaran', compact('kursus', 'transaksi', 'paymentUrl', 'snapError'));
    }

    /**
     * Generate DOKU Payment URL
     */
    private function getSnapToken($transaksi, $kursus)
    {
        // Double check user authentication
        if (!Auth::check()) {
            \Log::error('DOKU Payment Error: User not authenticated');
            return [null, 'User not authenticated'];
        }

        // Local payment - bypass DOKU for now
        \Log::info('Using local payment bypass');
        return ['#local-payment', null];

        /* ===== DOKU INTEGRATION - COMMENTED FOR LATER =====
        
        $baseUrl = config('doku.base_url');
        $path = '/checkout/v1/payment';
        $endpoint = $baseUrl . $path;

        // Prepare request body
        $user = Auth::user();
        $body = [
            'order' => [
                'amount' => (int) $transaksi->jumlah,
                'invoice_number' => $transaksi->kode_transaksi,
                'currency' => 'IDR',
            ],
            'payment' => [
                'payment_due_date' => 60, // minutes
            ],
            'customer' => [
                'name' => $user->name ?? 'Guest',
                'email' => $user->email ?? 'guest@example.com',
            ],
        ];

        // Generate signature using service
        $sig = DokuSignatureService::generate($body, $path);

        // Debug logging
        \Log::info('DOKU Request Debug:', [
            'endpoint' => $endpoint,
            'path' => $path,
            'client_id' => config('doku.client_id'),
            'request_id' => $sig['request_id'],
            'timestamp' => $sig['request_timestamp'],
            'signature' => $sig['signature'],
            'json_body' => $sig['json_body'],
        ]);

        try {
            $response = \Http::timeout(30)
                ->connectTimeout(10)
                ->withHeaders([
                    'Client-Id' => config('doku.client_id'),
                    'Request-Id' => $sig['request_id'],
                    'Request-Timestamp' => $sig['request_timestamp'],
                    'Signature' => $sig['signature'],
                    'Content-Type' => 'application/json',
                ]);
            
            if (env('DOKU_DISABLE_SSL_VERIFY', false)) {
                $response = $response->withOptions(['verify' => false]);
            }
            
            // Kirim body yang persis sama dengan yang di-hash
            $response = $response->withBody($sig['json_body'], 'application/json')->post($endpoint);

            $data = $response->json();
            
            // Debug response structure
            \Log::info('DOKU Response:', ['status' => $response->status(), 'data' => $data]);

            if ($response->successful()) {
                \Log::info('DOKU Payment Success:', $data);
                
                // Cek struktur response DOKU
                $paymentUrl = $data['payment']['url'] ?? 
                              $data['response']['payment']['url'] ?? 
                              $data['data']['payment']['url'] ?? 
                              null;
                              
                if (!$paymentUrl) {
                    \Log::warning('DOKU Payment URL not found in response', $data);
                }
                
                return [$paymentUrl, null];
            }

            $msg = 'HTTP ' . $response->status() . ' Response: ' . $response->body();
            \Log::error('DOKU Payment Error: ' . $msg);
            return [null, $msg];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $msg = 'Koneksi ke DOKU timeout atau gagal. Silakan coba lagi atau gunakan metode pembayaran lain.';
            \Log::error('DOKU Connection Error: ' . $e->getMessage());
            return [null, $msg];
        } catch (\Exception $e) {
            $msg = 'Terjadi kesalahan saat menghubungi DOKU: ' . $e->getMessage();
            \Log::error('DOKU Payment Error: ' . $msg);
            return [null, $msg];
        }
        
        ===== END DOKU INTEGRATION ===== */

        // Prepare request body
        $user = Auth::user();
        $body = [
            'order' => [
                'amount' => (int) $transaksi->jumlah,
                'invoice_number' => $transaksi->kode_transaksi,
                'currency' => 'IDR',
            ],
            'payment' => [
                'payment_due_date' => 60, // minutes
            ],
            'customer' => [
                'name' => $user->name ?? 'Guest',
                'email' => $user->email ?? 'guest@example.com',
            ],
        ];

        // Generate signature using service
        $sig = DokuSignatureService::generate($body, $path);

        // Debug logging
        \Log::info('DOKU Request Debug:', [
            'endpoint' => $endpoint,
            'path' => $path,
            'client_id' => config('doku.client_id'),
            'request_id' => $sig['request_id'],
            'timestamp' => $sig['request_timestamp'],
            'signature' => $sig['signature'],
            'json_body' => $sig['json_body'],
        ]);

        try {
            $response = \Http::timeout(30)
                ->connectTimeout(10)
                ->withHeaders([
                    'Client-Id' => config('doku.client_id'),
                    'Request-Id' => $sig['request_id'],
                    'Request-Timestamp' => $sig['request_timestamp'],
                    'Signature' => $sig['signature'],
                    'Content-Type' => 'application/json',
                ]);
            
            if (env('DOKU_DISABLE_SSL_VERIFY', false)) {
                $response = $response->withOptions(['verify' => false]);
            }
            
            // Kirim body yang persis sama dengan yang di-hash
            $response = $response->withBody($sig['json_body'], 'application/json')->post($endpoint);

            $data = $response->json();
            
            // Debug response structure
            \Log::info('DOKU Response:', ['status' => $response->status(), 'data' => $data]);

            if ($response->successful()) {
                \Log::info('DOKU Payment Success:', $data);
                
                // Cek struktur response DOKU
                $paymentUrl = $data['payment']['url'] ?? 
                              $data['response']['payment']['url'] ?? 
                              $data['data']['payment']['url'] ?? 
                              null;
                              
                if (!$paymentUrl) {
                    \Log::warning('DOKU Payment URL not found in response', $data);
                }
                
                return [$paymentUrl, null];
            }

            $msg = 'HTTP ' . $response->status() . ' Response: ' . $response->body();
            \Log::error('DOKU Payment Error: ' . $msg);
            return [null, $msg];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $msg = 'Koneksi ke DOKU timeout atau gagal. Silakan coba lagi atau gunakan metode pembayaran lain.';
            \Log::error('DOKU Connection Error: ' . $e->getMessage());
            return [null, $msg];
        } catch (\Exception $e) {
            $msg = 'Terjadi kesalahan saat menghubungi DOKU: ' . $e->getMessage();
            \Log::error('DOKU Payment Error: ' . $msg);
            return [null, $msg];
        }
    }

    /**
     * Process enrollment - Create pending transaction
     */
    public function enroll(Request $request, $id)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $user = Auth::user();
        $kursus = Kursus::findOrFail($id);
        
        // Check if user already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('kursus_id', $id)
            ->first();
        
        if ($existingEnrollment) {
            return response()->json([
                'success' => true,
                'message' => 'Anda sudah terdaftar',
                'redirect' => route('user.pelatihan-saya.index')
            ]);
        }
        
        // Get payment method from request
        $paymentMethod = $request->input('payment_method', 'local_payment');
        
        // Cek apakah sudah ada transaksi pending atau success untuk kursus ini
        $existingTransaksi = Transaksi::where('user_id', $user->id)
            ->where('kursus_id', $id)
            ->whereIn('status', ['pending', 'success'])
            ->first();
            
        // Jika ada transaksi pending, kembalikan data transaksi tersebut
        if ($existingTransaksi && $existingTransaksi->status === 'pending') {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi pending ditemukan',
                'data' => [
                    'kode_transaksi' => $existingTransaksi->kode_transaksi,
                    'status' => 'pending',
                    'expired_at' => $existingTransaksi->updated_at->addMinutes(10)->toIso8601String(),
                ]
            ]);
        }
        
        // Jika ada transaksi success, redirect ke pelatihan
        if ($existingTransaksi && $existingTransaksi->status === 'success') {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran sudah berhasil',
                'redirect' => route('user.pelatihan-saya.index')
            ]);
        }
        
        // Buat transaksi baru dengan status pending
        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-' . strtoupper(Str::random(10)),
            'user_id' => $user->id,
            'kursus_id' => $id,
            'jumlah' => $kursus->harga,
            'nominal_pembayaran' => $kursus->harga,
            'status' => 'pending',
            'metode_pembayaran' => $paymentMethod,
        ]);
        
        // Log pembayaran pending
        \Log::info('Transaksi pending dibuat', [
            'user_id' => $user->id,
            'kursus_id' => $id,
            'kode_transaksi' => $transaksi->kode_transaksi,
            'payment_method' => $paymentMethod
        ]);
        
        // Return JSON response dengan data transaksi
        return response()->json([
            'success' => true,
            'message' => 'Transaksi pending berhasil dibuat',
            'data' => [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'status' => 'pending',
                'expired_at' => $transaksi->updated_at->addMinutes(10)->toIso8601String(),
            ]
        ]);
        
        /* ===== ORIGINAL ENROLLMENT LOGIC - COMMENTED FOR LATER =====
        
        // Create enrollment
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'kursus_id' => $kursus->id,
            'tanggal_daftar' => now(),
            'status' => $kursus->harga > 0 ? 'pending' : 'active',
            'progress' => 0,
        ]);
        
        // If free course, redirect to training page
        if ($kursus->harga <= 0) {
            return redirect()->route('user.pelatihan-saya.index')
                ->with('success', 'Pendaftaran berhasil! Silakan mulai belajar.');
        }
        
        // For paid courses, redirect to payment
        return redirect()->route('user.kursus.pembayaran', $kursus->id);
        
        ===== END ORIGINAL ENROLLMENT LOGIC ===== */
    }

    /**
     * Complete payment - Ubah status dari pending ke success
     */
    public function completePayment(Request $request, $kodeTransaksi)
    {
        // Pastikan user terautentikasi
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $user = Auth::user();
        
        // Cari transaksi berdasarkan kode transaksi dan user_id
        $transaksi = Transaksi::where('kode_transaksi', $kodeTransaksi)
            ->where('user_id', $user->id)
            ->first();
        
        // Validasi transaksi ditemukan
        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }
        
        // Validasi transaksi masih pending
        if ($transaksi->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah diproses sebelumnya',
                'status' => $transaksi->status
            ], 400);
        }
        
        // Cek apakah transaksi sudah expired (lebih dari 10 menit)
        $expiredAt = $transaksi->updated_at->addMinutes(10);
        if (now()->isAfter($expiredAt)) {
            // Update status menjadi expired
            $transaksi->update(['status' => 'expired']);
            
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah expired',
                'status' => 'expired'
            ], 400);
        }
        
        // Update status transaksi menjadi success
        $transaksi->update([
            'status' => 'success',
            'nominal_pembayaran' => $transaksi->jumlah,
        ]);
        
        // Buat enrollment dengan status active
        Enrollment::create([
            'user_id' => $user->id,
            'kursus_id' => $transaksi->kursus_id,
            'tanggal_daftar' => now(),
            'status' => 'active',
            'progress' => 0,
        ]);
        
        // Log pembayaran berhasil
        \Log::info('Pembayaran selesai', [
            'user_id' => $user->id,
            'kode_transaksi' => $kodeTransaksi,
        ]);
        
        // Return response dengan data kursus untuk invoice
        $kursus = Kursus::find($transaksi->kursus_id);
        
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diselesaikan',
            'data' => [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'status' => 'success',
                'kursus' => [
                    'judul' => $kursus->judul,
                    'harga' => $kursus->harga,
                ],
                'tanggal_pembayaran' => now()->format('d M Y H:i'),
            ]
        ]);
    }

    /**
     * Check payment status - Untuk cek apakah transaksi expired
     */
    public function checkPaymentStatus($kodeTransaksi)
    {
        // Pastikan user terautentikasi
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();
        
        // Cari transaksi
        $transaksi = Transaksi::where('kode_transaksi', $kodeTransaksi)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }
        
        // Jika status pending, cek apakah sudah expired
        if ($transaksi->status === 'pending') {
            $expiredAt = $transaksi->updated_at->addMinutes(10);
            
            // Jika sudah expired, update status
            if (now()->isAfter($expiredAt)) {
                $transaksi->update(['status' => 'expired']);
                
                return response()->json([
                    'success' => true,
                    'status' => 'expired',
                    'message' => 'Transaksi telah expired'
                ]);
            }
            
            // Masih pending dan belum expired
            return response()->json([
                'success' => true,
                'status' => 'pending',
                'expired_at' => $expiredAt->toIso8601String(),
                'remaining_seconds' => now()->diffInSeconds($expiredAt, false)
            ]);
        }
        
        // Return status saat ini jika bukan pending
        return response()->json([
            'success' => true,
            'status' => $transaksi->status
        ]);
    }

    /**
     * Handle DOKU notification/webhook
     */
    public function notification(Request $request)
    {
        $clientId = config('doku.client_id');
        $secretKey = config('doku.secret_key');
        
        // DOKU signature verification
        $timestamp = $request->header('Request-Timestamp');
        $signature = $request->header('Signature');
        $requestBody = $request->getContent();
        
        $digestValue = base64_encode(hash('sha256', $requestBody, true));
        $componentSignature = "Client-Id:{$clientId}\nRequest-Timestamp:{$timestamp}\nRequest-Target:/doku/notification\nDigest:SHA-256={$digestValue}";
        $calculatedSignature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));
        
        // Remove HMACSHA256= prefix if exists
        $receivedSignature = str_replace('HMACSHA256=', '', $signature);
        
        if ($calculatedSignature !== $receivedSignature) {
            \Log::warning('DOKU Invalid signature');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        try {
            $data = json_decode($requestBody, true);
            $transactionStatus = $data['transaction']['status'] ?? '';
            $orderId = $data['order']['invoice_number'] ?? '';

            // Find transaction
            $transaksi = Transaksi::where('kode_transaksi', $orderId)->first();
            
            if (!$transaksi) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Map DOKU status to our enum: pending, success, failed, expired
            if ($transactionStatus === 'SUCCESS' || $transactionStatus === 'success') {
                $transaksi->status = 'success';
            } elseif ($transactionStatus === 'PENDING' || $transactionStatus === 'pending') {
                $transaksi->status = 'pending';
            } elseif ($transactionStatus === 'EXPIRED' || $transactionStatus === 'expired') {
                $transaksi->status = 'expired';
            } elseif ($transactionStatus === 'FAILED' || $transactionStatus === 'failed' || $transactionStatus === 'CANCELLED') {
                $transaksi->status = 'failed';
            }

            $transaksi->save();

            // If payment successful, activate enrollment
            if ($transaksi->status === 'success') {
                $enrollment = Enrollment::where('user_id', $transaksi->user_id)
                    ->where('kursus_id', $transaksi->kursus_id)
                    ->first();

                if ($enrollment) {
                    $enrollment->status = 'active';
                    $enrollment->save();
                } else {
                    // Create enrollment if not exists
                    Enrollment::create([
                        'user_id' => $transaksi->user_id,
                        'kursus_id' => $transaksi->kursus_id,
                        'tanggal_daftar' => now(),
                        'status' => 'active',
                        'progress' => 0,
                    ]);
                }
            }

            return response()->json(['message' => 'Notification processed']);
            
        } catch (\Exception $e) {
            \Log::error('DOKU Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus($kode_transaksi)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)
            ->where('user_id', $user->id)
            ->with('kursus')
            ->firstOrFail();

        // If still pending, try to check DOKU status
        if ($transaksi->status === 'pending') {
            try {
                \Artisan::call('doku:check-status', ['--kode' => $kode_transaksi]);
                // Refresh transaction from database
                $transaksi->refresh();
            } catch (\Exception $e) {
                \Log::error('Error checking DOKU status: ' . $e->getMessage());
            }
        }

        return response()->json([
            'status' => $transaksi->status,
            'kode_transaksi' => $transaksi->kode_transaksi,
            'kursus' => $transaksi->kursus->judul ?? '',
        ]);
    }
}