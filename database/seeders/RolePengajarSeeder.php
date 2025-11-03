<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePengajarSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'pengajar', 'guard_name' => 'web']);
    }
}
