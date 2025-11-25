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

        // Create or get existing pending transaction
        $transaksi = Transaksi::where('user_id', $user->id)
            ->where('kursus_id', $id)
            ->whereIn('status', ['pending', 'success'])
            ->first();

        // If no transaction or transaction expired/failed, create new one
        if (!$transaksi || in_array($transaksi->status, ['failed', 'expired'])) {
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'kursus_id' => $id,
                'kode_transaksi' => 'TRX-' . strtoupper(Str::random(10)),
                'jumlah' => $kursus->harga,
                'nominal_pembayaran' => $kursus->harga,
                'status' => 'pending',
                'metode_pembayaran' => 'e_wallet',
            ]);
        }

        // If transaction is already success, redirect to courses
        if ($transaksi->status === 'success') {
            return redirect()->route('user.pelatihan-saya.index')
                ->with('success', 'Pembayaran sudah berhasil!');
        }

        // Generate DOKU Payment URL only for pending transactions
        [$paymentUrl, $snapError] = $this->getSnapToken($transaksi, $kursus);
        
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

            $msg = 'HTTP ' . $response->status() . ' Response: ' . $response->body();
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
