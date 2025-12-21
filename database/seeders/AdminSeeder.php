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
        $admin = User::firstOrCreate(
            ['email' => 'admin@algorify.com'],
            [
                'id' => User::generateId('admin'),
                'name' => 'Anton Alam',
                'password' => Hash::make('admin123'),
                'phone' => '081234567890',
                'profesi' => 'Administrator',
                'address' => 'Jakarta, Indonesia',
                'pendidikan' => 'S2 Teknologi Informasi',
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Create super admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@algorify.com'],
            [
                'id' => User::generateId('admin'),
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'phone' => '081234567800',
                'profesi' => 'Super Administrator',
                'address' => 'Jakarta, Indonesia',
                'pendidikan' => 'S2 Computer Science',
                'email_verified_at' => now(),
            ]
        );
        if (!$superAdmin->hasRole('super admin')) {
            $superAdmin->assignRole('super admin');
        }
    }
}
