<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Models\Enrollment;

class AutoApprovePendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:auto-approve {--minutes=5 : Minutes to wait before auto-approving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-approve pending payments older than specified minutes (for testing/sandbox)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = $this->option('minutes');
        
        $this->info("Auto-approving pending payments older than {$minutes} minutes...");
        
        // Get pending transactions older than specified minutes
        $transactions = Transaksi::where('status', 'pending')
            ->where('created_at', '<=', now()->subMinutes($minutes))
            ->get();
        
        if ($transactions->isEmpty()) {
            $this->info('No pending transactions to approve.');
            return 0;
        }
        
        $this->info("Found {$transactions->count()} pending transaction(s) to approve");
        
        $bar = $this->output->createProgressBar($transactions->count());
        $bar->start();
        
        $successCount = 0;
        
        foreach ($transactions as $transaksi) {
            // Update transaction to success
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
            
            $this->line("\n  ✓ Approved: {$transaksi->kode_transaksi}");
            $successCount++;
            
            $bar->advance();
        }
        
        $bar->finish();
        
        $this->newLine(2);
        $this->info("Successfully approved {$successCount} transaction(s)");
        
        return 0;
    }
}
