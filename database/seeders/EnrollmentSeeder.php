<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Enrollment;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get user with email zein@student.com
        $zein = User::where('email', 'zein@student.com')->first();
        
        // Get Blockchain Development course
        $blockchainCourse = Kursus::where('judul', 'Blockchain Development')->first();
        $analisisDataCourse = Kursus::where('judul', 'Analisis Data')->first();
        $fullstackLaravelDevelopmentCourse = Kursus::where('judul', 'Fullstack Laravel Development')->first();


         Enrollment::create([
                    'user_id' => $zein->id,
                    'kursus_id' => $analisisDataCourse->id,
                    'status' => 'active',
                    'progress' => 0,
                    'tanggal_daftar' => now(),
                ]);

                 Enrollment::create([
                    'user_id' => $zein->id,
                    'kursus_id' => $fullstackLaravelDevelopmentCourse->id,
                    'status' => 'active',
                    'progress' => 0,
                    'tanggal_daftar' => now(),
                ]);
        
        if ($zein && $blockchainCourse) {
            // Check if enrollment already exists
            $existingEnrollment = Enrollment::where('user_id', $zein->id)
                ->where('kursus_id', $blockchainCourse->id)
                ->first();
            
            if (!$existingEnrollment) {
                Enrollment::create([
                    'user_id' => $zein->id,
                    'kursus_id' => $blockchainCourse->id,
                    'status' => 'active',
                    'progress' => 0,
                    'tanggal_daftar' => now(),
                ]);
                
                $this->command->info("✅ Enrollment created: {$zein->name} enrolled in {$blockchainCourse->judul}");
            } else {
                $this->command->info("ℹ️ Enrollment already exists for {$zein->name} in {$blockchainCourse->judul}");
            }
        } else {
            if (!$zein) {
                $this->command->error('❌ User with email zein@student.com not found!');
            }
            if (!$blockchainCourse) {
                $this->command->error('❌ Blockchain Development course not found!');
            }
        }
    }
}
