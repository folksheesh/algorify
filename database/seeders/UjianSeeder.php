<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ujian;
use App\Models\Modul;

class UjianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moduls = Modul::whereHas('kursus', function($q) {
            $q->where('judul', 'Web Development');
        })->orderBy('urutan')->get();

        if ($moduls->isEmpty()) {
            $this->command->error('Modul Web Development tidak ditemukan.');
            return;
        }

        $ujianData = [];
        $kursusId = $moduls[0]->kursus_id;

        // Quiz untuk setiap modul
        $ujianData[] = [
            'kursus_id' => $kursusId,
            'modul_id' => $moduls[1]->id, // HTML Fundamentals
            'judul' => 'Quiz HTML Fundamentals',
            'deskripsi' => 'Test pemahaman dasar HTML',
            'tipe' => 'practice',
            'waktu_pengerjaan' => 15,
            'minimum_score' => 70,
        ];

        $ujianData[] = [
            'kursus_id' => $kursusId,
            'modul_id' => $moduls[2]->id, // CSS
            'judul' => 'Quiz CSS Styling',
            'deskripsi' => 'Test pemahaman CSS dan layouts',
            'tipe' => 'practice',
            'waktu_pengerjaan' => 20,
            'minimum_score' => 70,
        ];

        $ujianData[] = [
            'kursus_id' => $kursusId,
            'modul_id' => $moduls[3]->id, // JavaScript Basics
            'judul' => 'Quiz JavaScript Basics',
            'deskripsi' => 'Test pemahaman dasar JavaScript',
            'tipe' => 'practice',
            'waktu_pengerjaan' => 25,
            'minimum_score' => 70,
        ];

        $ujianData[] = [
            'kursus_id' => $kursusId,
            'modul_id' => $moduls[4]->id, // JavaScript Advanced
            'judul' => 'Quiz JavaScript Advanced',
            'deskripsi' => 'Test pemahaman JavaScript advanced concepts',
            'tipe' => 'practice',
            'waktu_pengerjaan' => 30,
            'minimum_score' => 70,
        ];

        // Ujian Akhir
        $ujianData[] = [
            'kursus_id' => $kursusId,
            'modul_id' => $moduls[7]->id, // Final Project
            'judul' => 'Ujian Akhir Web Development',
            'deskripsi' => 'Ujian komprehensif untuk semua materi yang telah dipelajari',
            'tipe' => 'exam',
            'waktu_pengerjaan' => 90,
            'minimum_score' => 75,
        ];

        foreach ($ujianData as $ujian) {
            Ujian::create($ujian);
        }

        $this->command->info('Ujian Web Development berhasil dibuat!');
    }
}
