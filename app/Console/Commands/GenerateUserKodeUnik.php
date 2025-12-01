<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateUserKodeUnik extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate-kode-unik';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate kode unik untuk user yang belum memiliki kode unik';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull('kode_unik')->orWhere('kode_unik', '')->get();

        if ($users->isEmpty()) {
            $this->info('Semua user sudah memiliki kode unik.');
            return 0;
        }

        $this->info("Menemukan {$users->count()} user tanpa kode unik.");

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            // Determine role
            $role = 'peserta'; // default
            
            if ($user->hasRole('super admin') || $user->hasRole('admin')) {
                $role = 'admin';
            } elseif ($user->hasRole('pengajar')) {
                $role = 'pengajar';
            }

            $user->kode_unik = User::generateKodeUnik($role);
            $user->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Selesai! Semua user sekarang memiliki kode unik.');

        return 0;
    }
}
