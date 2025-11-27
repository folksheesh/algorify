<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;

class ExpireOldPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:expire-old {--hours=24 : Hours before expiring pending payments}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark old pending payments as expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        
        $this->info("Expiring pending payments older than {$hours} hours...");
        
        // Get pending transactions older than specified hours
        $transactions = Transaksi::where('status', 'pending')
            ->where('created_at', '<=', now()->subHours($hours))
            ->get();
        
        if ($transactions->isEmpty()) {
            $this->info('No pending transactions to expire.');
            return 0;
        }
        
        $this->info("Found {$transactions->count()} pending transaction(s) to expire");
        
        $expiredCount = 0;
        
        foreach ($transactions as $transaksi) {
            $transaksi->status = 'expired';
            $transaksi->save();
            
            $this->line("  ✓ Expired: {$transaksi->kode_transaksi} (created " . $transaksi->created_at->diffForHumans() . ")");
            $expiredCount++;
        }
        
        $this->newLine();
        $this->info("Successfully expired {$expiredCount} transaction(s)");
        
        return 0;
    }
}
