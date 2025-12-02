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
     */
    public function run(): void
    {
        // Get peserta users
        $pesertaUsers = User::role('peserta')->get();
        
        // Get all kursus
        $kursusList = Kursus::all();
        
        if ($pesertaUsers->isEmpty() || $kursusList->isEmpty()) {
            $this->command->error('⚠ EnrollmentSeeder membutuhkan data User (peserta) dan Kursus.');
            return;
        }

        $enrollmentCount = 0;
        $statusOptions = ['active', 'active', 'active', 'completed', 'dropped']; // weighted towards active

        // Each peserta enrolls in 1-3 random kursus
        foreach ($pesertaUsers as $user) {
            // Random number of enrollments per user (1-3)
            $numEnrollments = rand(1, 3);
            
            // Get random kursus for this user
            $randomKursus = $kursusList->random(min($numEnrollments, $kursusList->count()));
            
            foreach ($randomKursus as $kursus) {
                // Check if enrollment already exists
                $existingEnrollment = Enrollment::where('user_id', $user->id)
                    ->where('kursus_id', $kursus->id)
                    ->first();
                
                if (!$existingEnrollment) {
                    $status = $statusOptions[array_rand($statusOptions)];
                    $progress = $status === 'completed' ? 100 : rand(0, 95);
                    
                    Enrollment::create([
                        'kode' => 'ENR-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                        'user_id' => $user->id,
                        'kursus_id' => $kursus->id,
                        'status' => $status,
                        'progress' => $progress,
                        'tanggal_daftar' => Carbon::now()->subDays(rand(1, 90)),
                        'nilai_akhir' => $status === 'completed' ? rand(70, 100) : null,
                    ]);
                    
                    $enrollmentCount++;
                }
            }
        }

        $this->command->info("✓ EnrollmentSeeder berhasil! Total: {$enrollmentCount} enrollment");
    }
}
