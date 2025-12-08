<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kursus;
use App\Models\Enrollment;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use App\Services\DokuSignatureService;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Show the payment page for a course
     */
    public function showPayment($id, Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $kursus = Kursus::with('pengajar')->findOrFail($id);
        
        // Pengajar tidak wajib, lanjut meski tidak ada pengajar
        
        // Check if user already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('kursus_id', $id)
            ->first();
        
        if ($existingEnrollment) {
            return redirect()->route('user.pelatihan-saya.index')
                ->with('info', 'Anda sudah terdaftar di pelatihan ini.');
        }

        // Store message for expired/failed transactions
        $transactionMessage = null;
        $forceNew = $request->get('new', false);
        
        // Check for any existing transaction
        $transaksi = Transaksi::where('user_id', $user->id)
            ->where('kursus_id', $id)
            ->latest()
            ->first();

        // If force new is requested, always create new transaction
        if ($forceNew && $transaksi) {
            $transactionMessage = [
                'type' => 'warning',
                'message' => 'Membuat transaksi baru dengan invoice number baru.'
            ];
            
            // Create new transaction with unique code
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'kursus_id' => $id,
                'kode_transaksi' => 'TRX-' . strtoupper(Str::random(12)),
                'jumlah' => $kursus->harga,
                'nominal_pembayaran' => $kursus->harga,
                'status' => 'pending',
                'metode_pembayaran' => 'e_wallet',
            ]);
        } 
        // Handle existing transactions
        elseif ($transaksi) {
            if ($transaksi->status === 'success') {
                // Already paid, redirect
                return redirect()->route('user.pelatihan-saya.index')
                    ->with('success', 'Pembayaran sudah berhasil!');
            }
            
            // Auto create new transaction if old transaction is failed/expired
            if (in_array($transaksi->status, ['failed', 'expired'])) {
                if ($transaksi->status === 'expired') {
                    $transactionMessage = [
                        'type' => 'warning',
                        'message' => 'Transaksi sebelumnya telah kadaluarsa. Membuat transaksi baru.'
                    ];
                } elseif ($transaksi->status === 'failed') {
                    $transactionMessage = [
                        'type' => 'error',
                        'message' => 'Transaksi sebelumnya gagal. Membuat transaksi baru.'
                    ];
                }
                
                // Create new transaction with unique code
                $transaksi = Transaksi::create([
                    'user_id' => $user->id,
                    'kursus_id' => $id,
                    'kode_transaksi' => 'TRX-' . strtoupper(Str::random(12)),
                    'jumlah' => $kursus->harga,
                    'nominal_pembayaran' => $kursus->harga,
                    'status' => 'pending',
                    'metode_pembayaran' => 'e_wallet',
                ]);
            }
            // If pending and not forcing new, keep using existing transaction
        } else {
            // No transaction exists, create new one
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'kursus_id' => $id,
                'kode_transaksi' => 'TRX-' . strtoupper(Str::random(12)),
                'jumlah' => $kursus->harga,
                'nominal_pembayaran' => $kursus->harga,
                'status' => 'pending',
                'metode_pembayaran' => 'e_wallet',
            ]);
        }

        // Generate DOKU Payment URL only for pending transactions
        [$paymentUrl, $snapError] = $this->getSnapToken($transaksi, $kursus);
        
        return view('user.pembayaran', compact('kursus', 'transaksi', 'paymentUrl', 'snapError', 'transactionMessage'));
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

        $baseUrl = config('doku.base_url');
        $path = '/checkout/v1/payment';
        $endpoint = $baseUrl . $path;

        // Prepare request body
        $user = Auth::user();
        $callbackUrl = url('/payment/callback') . '?invoice=' . $transaksi->kode_transaksi;
        
        $body = [
            'order' => [
                // DOKU expects integer amount (IDR) without fractional parts
                'amount' => (int) $transaksi->jumlah,
                'invoice_number' => $transaksi->kode_transaksi,
                'currency' => 'IDR',
                'session_id' => session()->getId(),
                'callback_url' => $callbackUrl,
            ],
            'payment' => [
                'payment_due_date' => 60, // minutes
                'payment_method_types' => [
                    'VIRTUAL_ACCOUNT_BCA',
                    'VIRTUAL_ACCOUNT_BANK_MANDIRI', 
                    'VIRTUAL_ACCOUNT_BANK_SYARIAH_MANDIRI',
                    'VIRTUAL_ACCOUNT_DOKU',
                    'VIRTUAL_ACCOUNT_BRI',
                    'VIRTUAL_ACCOUNT_BNI',
                    'VIRTUAL_ACCOUNT_BANK_PERMATA',
                    'VIRTUAL_ACCOUNT_BANK_CIMB',
                    'VIRTUAL_ACCOUNT_BANK_DANAMON',
                    'ONLINE_TO_OFFLINE_ALFA',
                    'CREDIT_CARD',
                    'DIRECT_DEBIT_BRI',
                    'EMONEY_SHOPEE_PAY',
                    'EMONEY_OVO',
                    'QRIS',
                ],
            ],
            'customer' => [
                'id' => (string) $user->id,
                'name' => $user->name ?? 'Guest',
                'email' => $user->email ?? 'guest@example.com',
            ],
            'additional_info' => [
                'allow_redirect' => true,
                'success_payment_url' => $callbackUrl,
                'failed_payment_url' => url('/payment/callback') . '?invoice=' . $transaksi->kode_transaksi . '&status=failed',
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
            $response = \Http::withHeaders([
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

            // Check if invoice already used - generate new kode_transaksi and retry
            $responseBody = $response->body();
            if ($response->status() === 400 && str_contains($responseBody, 'INVOICE ALREADY USED')) {
                \Log::warning('DOKU Invoice already used, generating new kode_transaksi', [
                    'old_kode' => $transaksi->kode_transaksi
                ]);
                
                // Generate new unique kode_transaksi
                $newKode = 'TRX-' . strtoupper(Str::random(10)) . '-' . time();
                $transaksi->update(['kode_transaksi' => $newKode]);
                
                \Log::info('Retrying DOKU payment with new kode_transaksi', [
                    'new_kode' => $newKode
                ]);
                
                // Retry with new invoice number (recursive call, max 1 retry)
                static $retryCount = 0;
                if ($retryCount < 1) {
                    $retryCount++;
                    return $this->getSnapToken($transaksi, $kursus);
                }
            }
            
            $msg = 'HTTP ' . $response->status() . ' Response: ' . $responseBody;
            \Log::error('DOKU Payment Error: ' . $msg);
            return [null, $msg];
        } catch (\Exception $e) {
            $msg = 'Exception: ' . $e->getMessage();
            \Log::error('DOKU Payment Error: ' . $msg);
            return [null, $msg];
        }
    }

    /**
     * Process enrollment
     */
    public function enroll(Request $request, $id)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $kursus = Kursus::findOrFail($id);
        
        // Check if user already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('kursus_id', $id)
            ->first();
        
        if ($existingEnrollment) {
            return redirect()->route('user.pelatihan-saya.index')
                ->with('info', 'Anda sudah terdaftar di pelatihan ini.');
        }
        
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
    }

    /**
     * Handle DOKU payment callback (redirect after payment)
     */
    public function paymentCallback(Request $request)
    {
        $invoice = $request->get('invoice');
        
        if (!$invoice) {
            return redirect()->route('dashboard')->with('error', 'Invoice tidak ditemukan.');
        }
        
        $transaksi = Transaksi::where('kode_transaksi', $invoice)->first();
        
        if (!$transaksi) {
            return redirect()->route('dashboard')->with('error', 'Transaksi tidak ditemukan.');
        }
        
        // Check payment status from DOKU API
        $status = $this->verifyPaymentStatus($invoice);
        
        if ($status === 'SUCCESS') {
            // Update transaction status
            $transaksi->status = 'success';
            $transaksi->save();
            
            // Activate enrollment
            $enrollment = Enrollment::where('user_id', $transaksi->user_id)
                ->where('kursus_id', $transaksi->kursus_id)
                ->first();

            if ($enrollment) {
                $enrollment->status = 'active';
                $enrollment->save();
            } else {
                Enrollment::create([
                    'user_id' => $transaksi->user_id,
                    'kursus_id' => $transaksi->kursus_id,
                    'tanggal_daftar' => now(),
                    'status' => 'active',
                    'progress' => 0,
                ]);
            }
            
            return redirect()->route('user.pelatihan-saya.index')
                ->with('success', 'Pembayaran berhasil! Selamat belajar.');
        } elseif (in_array($status, ['FAILED', 'CANCELLED', 'EXPIRED'])) {
            $transaksi->status = strtolower($status);
            $transaksi->save();
            
            return redirect()->route('user.kursus.pembayaran', $transaksi->kursus_id)
                ->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
        }
        
        // Still pending, redirect to payment page to wait
        return redirect()->route('user.kursus.pembayaran', $transaksi->kursus_id)
            ->with('info', 'Menunggu konfirmasi pembayaran...');
    }
    
    /**
     * Verify payment status from DOKU API using GET endpoint
     */
    private function verifyPaymentStatus($invoiceNumber)
    {
        $baseUrl = config('doku.base_url');
        $path = "/orders/v1/status/{$invoiceNumber}";
        $endpoint = $baseUrl . $path;
        
        $clientId = config('doku.client_id');
        $secretKey = config('doku.secret_key');
        $requestId = (string) Str::uuid();
        $requestTimestamp = now('UTC')->format('Y-m-d\TH:i:s\Z');
        
        // For GET request, no body - just sign the path
        $stringToSign = "Client-Id:{$clientId}\n"
            . "Request-Id:{$requestId}\n"
            . "Request-Timestamp:{$requestTimestamp}\n"
            . "Request-Target:{$path}";
        
        $hmac = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $signature = "HMACSHA256={$hmac}";
        
        try {
            $response = \Http::timeout(10)
                ->withHeaders([
                    'Client-Id' => $clientId,
                    'Request-Id' => $requestId,
                    'Request-Timestamp' => $requestTimestamp,
                    'Signature' => $signature,
                ])
                ->withOptions(['verify' => config('doku.disable_ssl_verify', false) ? false : true])
                ->get($endpoint);
            
            \Log::info('DOKU Verify Status Response:', [
                'invoice' => $invoiceNumber,
                'status_code' => $response->status(),
                'body' => $response->json()
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                // DOKU returns status in transaction.status
                return $data['transaction']['status'] 
                    ?? $data['order']['status'] 
                    ?? $data['status']
                    ?? 'PENDING';
            }
            
            return 'PENDING';
        } catch (\Exception $e) {
            \Log::error('DOKU Verify Status Error: ' . $e->getMessage());
            return 'PENDING';
        }
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

        // If still pending, check DOKU status
        if ($transaksi->status === 'pending') {
            $cacheKey = "doku_check_{$kode_transaksi}";
            $lastCheck = \Cache::get($cacheKey);
            
            // Only check DOKU API every 3 seconds to avoid rate limiting
            if (!$lastCheck || now()->diffInSeconds($lastCheck) >= 3) {
                try {
                    // Use direct API call for faster response
                    $status = $this->verifyPaymentStatus($kode_transaksi);
                    \Cache::put($cacheKey, now(), 3);
                    
                    if ($status === 'SUCCESS') {
                        $transaksi->status = 'success';
                        $transaksi->save();
                        
                        // Activate enrollment
                        $enrollment = Enrollment::where('user_id', $transaksi->user_id)
                            ->where('kursus_id', $transaksi->kursus_id)
                            ->first();

                        if ($enrollment) {
                            $enrollment->status = 'active';
                            $enrollment->save();
                        } else {
                            Enrollment::create([
                                'user_id' => $transaksi->user_id,
                                'kursus_id' => $transaksi->kursus_id,
                                'tanggal_daftar' => now(),
                                'status' => 'active',
                                'progress' => 0,
                            ]);
                        }
                    } elseif (in_array($status, ['FAILED', 'CANCELLED', 'EXPIRED'])) {
                        $transaksi->status = strtolower($status);
                        $transaksi->save();
                    }
                } catch (\Exception $e) {
                    \Log::error('Error checking DOKU status: ' . $e->getMessage());
                }
            }
        }

        // If payment successful, activate/create enrollment
        if ($transaksi->status === 'success') {
            $enrollment = Enrollment::where('user_id', $transaksi->user_id)
                ->where('kursus_id', $transaksi->kursus_id)
                ->first();

            if ($enrollment) {
                if ($enrollment->status !== 'active') {
                    $enrollment->status = 'active';
                    $enrollment->save();
                }
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

        return response()->json([
            'status' => $transaksi->status,
            'kode_transaksi' => $transaksi->kode_transaksi,
            'kursus' => $transaksi->kursus->judul ?? '',
        ]);
    }
}
