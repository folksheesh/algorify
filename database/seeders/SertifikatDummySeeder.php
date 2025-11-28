<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sertifikat;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Enrollment;

class SertifikatDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user peserta pertama
        $user = User::role('peserta')->first();
        
        if (!$user) {
            $this->command->warn('Tidak ada user dengan role peserta. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Cari kursus pertama
        $kursus = Kursus::first();
        
        if (!$kursus) {
            $this->command->warn('Tidak ada kursus. Jalankan KursusSeeder terlebih dahulu.');
            return;
        }

        // Buat enrollment dummy dengan progress 100% dan nilai 85
        $enrollment = Enrollment::firstOrCreate(
            [
                'user_id' => $user->id,
                'kursus_id' => $kursus->id,
            ],
            [
                'tanggal_daftar' => now(),
                'status' => 'active',
                'progress' => 100,
                'nilai_akhir' => 85,
            ]
        );

        // Buat sertifikat dummy
        $sertifikat = Sertifikat::firstOrCreate(
            [
                'user_id' => $user->id,
                'kursus_id' => $kursus->id,
            ],
            [
                'judul' => $kursus->judul,
                'deskripsi' => 'Telah menyelesaikan pelatihan ' . $kursus->judul,
                'tanggal_terbit' => now(),
                'status_sertifikat' => 'active',
            ]
        );

        $this->command->info('✓ Sertifikat dummy berhasil dibuat!');
        $this->command->info('  User: ' . $user->name . ' (' . $user->email . ')');
        $this->command->info('  Kursus: ' . $kursus->judul);
        $this->command->info('  Nomor Sertifikat: ' . $sertifikat->nomor_sertifikat);
        $this->command->info('  Progress: 100%');
        $this->command->info('  Nilai: 85/100');
    }
}
