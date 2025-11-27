<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        $pengajarRole = Role::firstOrCreate(['name' => 'pengajar', 'guard_name' => 'web']);
        $pesertaRole = Role::firstOrCreate(['name' => 'peserta', 'guard_name' => 'web']);

        // Create instructor users
        $budi = User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'budi@algorify.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'profesi' => 'Data Scientist',
            'address' => 'Bandung, Indonesia',
            'pendidikan' => 'S3 Computer Science',
            'email_verified_at' => now(),
            'kode_unik' => User::generateKodeUnik('pengajar'),
        ]);
        $budi->assignRole('pengajar');

        $sarah = User::create([
            'name' => 'Sarah Wijaya, M.Kom',
            'email' => 'sarah@algorify.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'profesi' => 'Cyber Security Expert',
            'address' => 'Surabaya, Indonesia',
            'pendidikan' => 'S2 Cyber Security',
            'email_verified_at' => now(),
            'kode_unik' => User::generateKodeUnik('pengajar'),
        ]);
        $sarah->assignRole('pengajar');

        $andi = User::create([
            'name' => 'Andi Prasetyo',
            'email' => 'andi@algorify.com',
            'password' => Hash::make('password'),
            'phone' => '081234567893',
            'profesi' => 'UI/UX Designer',
            'address' => 'Yogyakarta, Indonesia',
            'pendidikan' => 'S1 Desain Komunikasi Visual',
            'email_verified_at' => now(),
            'kode_unik' => User::generateKodeUnik('pengajar'),
        ]);
        $andi->assignRole('pengajar');

        $dewi = User::create([
            'name' => 'Dewi Kusuma',
            'email' => 'dewi@algorify.com',
            'password' => Hash::make('password'),
            'phone' => '081234567894',
            'profesi' => 'Full Stack Developer',
            'address' => 'Jakarta, Indonesia',
            'pendidikan' => 'S1 Teknik Informatika',
            'email_verified_at' => now(),
            'kode_unik' => User::generateKodeUnik('pengajar'),
        ]);
        $dewi->assignRole('pengajar');

        // Create regular student users
        $zein = User::create([
            'name' => 'Muhammad Zein',
            'email' => 'zein@student.com',
            'password' => Hash::make('password'),
            'phone' => '081234567895',
            'profesi' => 'Mahasiswa',
            'address' => 'Semarang, Indonesia',
            'pendidikan' => 'S1 Teknik Informatika',
            'email_verified_at' => now(),
            'kode_unik' => User::generateKodeUnik('peserta'),
        ]);
        $zein->assignRole('peserta');

        $siti = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@student.com',
            'password' => Hash::make('password'),
            'phone' => '081234567896',
            'profesi' => 'Fresh Graduate',
            'address' => 'Malang, Indonesia',
            'pendidikan' => 'S1 Sistem Informasi',
            'email_verified_at' => now(),
            'kode_unik' => User::generateKodeUnik('peserta'),
        ]);
        $siti->assignRole('peserta');

        $rudi = User::create([
            'name' => 'Rudi Hermawan',
            'email' => 'rudi@student.com',
            'password' => Hash::make('password'),
            'phone' => '081234567897',
            'profesi' => 'IT Support',
            'address' => 'Medan, Indonesia',
            'pendidikan' => 'D3 Teknik Komputer',
            'email_verified_at' => now(),
            'kode_unik' => User::generateKodeUnik('peserta'),
        ]);
        $rudi->assignRole('peserta');

        $linda = User::create([
            'name' => 'Linda Septiani',
            'email' => 'linda@student.com',
            'password' => Hash::make('password'),
            'phone' => '081234567898',
            'profesi' => 'Digital Marketer',
            'address' => 'Bali, Indonesia',
            'pendidikan' => 'S1 Marketing',
            'email_verified_at' => now(),
            'kode_unik' => User::generateKodeUnik('peserta'),
        ]);
        $linda->assignRole('peserta');

        $arief = User::create([
            'name' => 'Arief Rahman',
            'email' => 'arief@student.com',
            'password' => Hash::make('password'),
            'phone' => '081234567899',
            'profesi' => 'Web Developer',
            'address' => 'Jakarta, Indonesia',
            'pendidikan' => 'SMK Rekayasa Perangkat Lunak',
            'email_verified_at' => now(),
            'kode_unik' => User::generateKodeUnik('peserta'),
        ]);
        $arief->assignRole('peserta');
    }
}
