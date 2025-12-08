<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Enrollment;
use Carbon\Carbon;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * CATATAN: Peserta demo (peserta@example.com) tidak di-handle di sini
     * karena sudah dihandle oleh DemoDataSeeder
     */
    public function run(): void
    {
        // Get peserta users (exclude peserta demo)
        $pesertaUsers = User::role('peserta')
            ->where('email', '!=', 'peserta@example.com')
            ->get();
        
        // Get all kursus
        $kursusList = Kursus::all();
        
        if ($pesertaUsers->isEmpty() || $kursusList->isEmpty()) {
            $this->command->error('⚠ EnrollmentSeeder membutuhkan data User (peserta) dan Kursus.');
            return;
        }

        $enrollmentCount = 0;
        $statusOptions = ['active', 'active', 'active', 'completed', 'dropped']; // weighted towards active

        // Definisikan tanggal-tanggal untuk variasi
        $dateRanges = [
            Carbon::create(2025, 12, 1),
            Carbon::create(2025, 11, 10),
            Carbon::create(2025, 11, 20),
            Carbon::create(2025, 10, 5),
            Carbon::create(2025, 10, 15),
            Carbon::create(2025, 9, 8),
            Carbon::create(2025, 9, 25),
            Carbon::create(2025, 8, 12),
        ];

        // Each peserta enrolls in 1-3 random kursus
        foreach ($pesertaUsers as $userIndex => $user) {
            // Random number of enrollments per user (1-3)
            $numEnrollments = rand(1, 3);
            
            // Get random kursus for this user
            $randomKursus = $kursusList->random(min($numEnrollments, $kursusList->count()));
            
            foreach ($randomKursus as $kursusIndex => $kursus) {
                // Check if enrollment already exists
                $existingEnrollment = Enrollment::where('user_id', $user->id)
                    ->where('kursus_id', $kursus->id)
                    ->first();
                
                if (!$existingEnrollment) {
                    $status = $statusOptions[array_rand($statusOptions)];
                    $progress = $status === 'completed' ? 100 : rand(0, 95);
                    
                    // Pilih tanggal random dari daftar
                    $baseDate = $dateRanges[array_rand($dateRanges)];
                    $tanggalDaftar = $baseDate->copy()->addDays(rand(0, 5));
                    
                    Enrollment::create([
                        'kode' => 'ENR-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                        'user_id' => $user->id,
                        'kursus_id' => $kursus->id,
                        'status' => $status,
                        'progress' => $progress,
                        'tanggal_daftar' => $tanggalDaftar,
                        'nilai_akhir' => $status === 'completed' ? rand(70, 100) : null,
                    ]);
                    
                    $enrollmentCount++;
                }
            }
        }

        $this->command->info("✓ EnrollmentSeeder berhasil! Total: {$enrollmentCount} enrollment (peserta demo excluded)");
    }
}
