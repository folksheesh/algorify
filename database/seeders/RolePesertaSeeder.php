<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePesertaSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'peserta', 'guard_name' => 'web']);
    }
}
