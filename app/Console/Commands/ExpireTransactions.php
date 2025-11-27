<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use Carbon\Carbon;

class ExpireTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending transactions yang sudah lebih dari 10 menit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Cari semua transaksi pending yang updated_at nya lebih dari 10 menit yang lalu
        $expiredTime = Carbon::now()->subMinutes(10);
        
        // Update status transaksi yang sudah expired
        $expiredCount = Transaksi::where('status', 'pending')
            ->where('updated_at', '<', $expiredTime)
            ->update(['status' => 'expired']);
        
        // Log hasil
        if ($expiredCount > 0) {
            $this->info("Successfully expired {$expiredCount} pending transaction(s)");
            \Log::info("Expired {$expiredCount} pending transactions");
        } else {
            $this->info('No pending transactions to expire');
        }
        
        return Command::SUCCESS;
    }
}
