<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Models\Enrollment;
use App\Providers\DokuSignatureService;

class CheckDokuPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doku:check-status {--kode= : Specific transaction code to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check DOKU payment status for pending transactions and update accordingly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking DOKU payment status...');
        
        // Get pending transactions
        $query = Transaksi::where('status', 'pending');
        
        // If specific code provided, check only that transaction
        if ($this->option('kode')) {
            $query->where('kode_transaksi', $this->option('kode'));
        } else {
            // Only check transactions from last 24 hours
            $query->where('created_at', '>=', now()->subHours(24));
        }
        
        $transactions = $query->get();
        
        if ($transactions->isEmpty()) {
            $this->info('No pending transactions to check.');
            return 0;
        }
        
        $this->info("Found {$transactions->count()} pending transaction(s)");
        
        $successCount = 0;
        $failedCount = 0;
        
        foreach ($transactions as $transaksi) {
            $this->line("Checking: {$transaksi->kode_transaksi}");
            
            try {
                $status = $this->checkTransactionStatus($transaksi->kode_transaksi);
                
                if ($status === 'SUCCESS') {
                    $transaksi->status = 'success';
                    $transaksi->save();
                    
                    // Activate enrollment
                    $this->activateEnrollment($transaksi);
                    
                    $this->info("  ✓ Transaction {$transaksi->kode_transaksi} marked as SUCCESS");
                    $successCount++;
                    
                } elseif (in_array($status, ['FAILED', 'CANCELLED', 'EXPIRED'])) {
                    $transaksi->status = strtolower($status);
                    $transaksi->save();
                    
                    $this->warn("  ✗ Transaction {$transaksi->kode_transaksi} marked as {$status}");
                    $failedCount++;
                    
                } else {
                    $this->line("  - Still PENDING");
                }
                
            } catch (\Exception $e) {
                $this->error("  Error checking {$transaksi->kode_transaksi}: " . $e->getMessage());
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("  Success: {$successCount}");
        $this->info("  Failed: {$failedCount}");
        $this->info("  Still Pending: " . ($transactions->count() - $successCount - $failedCount));
        
        return 0;
    }
    
    /**
     * Check transaction status from DOKU API
     */
    private function checkTransactionStatus($invoiceNumber)
    {
        $baseUrl = config('doku.base_url');
        $path = "/checkout/v1/payment/{$invoiceNumber}/status";
        $endpoint = $baseUrl . $path;
        
        // Generate signature for GET request
        $clientId = config('doku.client_id');
        $secretKey = config('doku.secret_key');
        $requestId = (string) \Str::uuid();
        $requestTimestamp = now('UTC')->format('Y-m-d\TH:i:s\Z');
        
        // For GET request, no body
        $stringToSign = "Client-Id:{$clientId}\n"
            ."Request-Id:{$requestId}\n"
            ."Request-Timestamp:{$requestTimestamp}\n"
            ."Request-Target:{$path}";
        
        $hmac = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $signature = "HMACSHA256={$hmac}";
        
        // Call DOKU API
        $response = \Http::withHeaders([
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requestTimestamp,
            'Signature' => $signature,
        ])
        ->withOptions(['verify' => config('doku.disable_ssl_verify', false) ? false : true])
        ->get($endpoint);
        
        if ($response->successful()) {
            $data = $response->json();
            // DOKU returns status in different paths, check common ones
            return $data['transaction']['status'] 
                ?? $data['response']['transaction']['status'] 
                ?? $data['order']['status'] 
                ?? 'PENDING';
        }
        
        // If 404, transaction might not exist yet or expired
        if ($response->status() === 404) {
            return 'PENDING';
        }
        
        throw new \Exception("API Error: " . $response->body());
    }
    
    /**
     * Activate enrollment for successful payment
     */
    private function activateEnrollment($transaksi)
    {
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
    }
}