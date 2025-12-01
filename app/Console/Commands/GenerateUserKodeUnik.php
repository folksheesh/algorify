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
    protected $signature = 'users:show-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tampilkan semua user dengan ID berdasarkan role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Daftar User dengan ID:');
        $this->newLine();
        
        $this->table(
            ['ID', 'Name', 'Email', 'Role'],
            User::all()->map(function($u) {
                $roles = $u->getRoleNames()->implode(', ');
                return [$u->id, $u->name, $u->email, $roles];
            })
        );

        return 0;
    }
}
