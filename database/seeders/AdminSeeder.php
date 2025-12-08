<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $superAdminRole = Role::firstOrCreate(['name' => 'super admin', 'guard_name' => 'web']);

        // Create main admin
        $admin = User::create([
            'id' => User::generateId('admin'),
            'name' => 'Anton Alam',
            'email' => 'admin@algorify.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
            'profesi' => 'Administrator',
            'address' => 'Jakarta, Indonesia',
            'pendidikan' => 'S2 Teknologi Informasi',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create super admin
        $superAdmin = User::create([
            'id' => User::generateId('admin'),
            'name' => 'Super Admin',
            'email' => 'superadmin@algorify.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567800',
            'profesi' => 'Super Administrator',
            'address' => 'Jakarta, Indonesia',
            'pendidikan' => 'S2 Computer Science',
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super admin');
    }
}
