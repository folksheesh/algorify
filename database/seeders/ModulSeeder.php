<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Modul;
use App\Models\Kursus;

class ModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $webDevCourse = Kursus::where('judul', 'Web Development')->first();
        
        if (!$webDevCourse) {
            $this->command->error('Kursus Web Development tidak ditemukan. Jalankan KursusSeeder terlebih dahulu.');
            return;
        }

        $modulData = [
            [
                'kursus_id' => $webDevCourse->id,
                'judul' => 'Pengenalan Web Development',
                'deskripsi' => 'Modul pengenalan tentang dunia web development, arsitektur web, dan tools yang digunakan',
                'urutan' => 1,
            ],
            [
                'kursus_id' => $webDevCourse->id,
                'judul' => 'HTML Fundamentals',
                'deskripsi' => 'Pelajari dasar-dasar HTML, struktur dokumen, semantic HTML, dan best practices',
                'urutan' => 2,
            ],
            [
                'kursus_id' => $webDevCourse->id,
                'judul' => 'CSS Styling & Layouts',
                'deskripsi' => 'Menguasai CSS untuk styling, flexbox, grid, responsive design, dan animations',
                'urutan' => 3,
            ],
            [
                'kursus_id' => $webDevCourse->id,
                'judul' => 'JavaScript Basics',
                'deskripsi' => 'Fundamental JavaScript: variables, functions, DOM manipulation, dan event handling',
                'urutan' => 4,
            ],
            [
                'kursus_id' => $webDevCourse->id,
                'judul' => 'JavaScript Advanced',
                'deskripsi' => 'Konsep advanced: async/await, promises, ES6+, dan modern JavaScript patterns',
                'urutan' => 5,
            ],
            [
                'kursus_id' => $webDevCourse->id,
                'judul' => 'Backend Development dengan Laravel',
                'deskripsi' => 'Membangun backend aplikasi web menggunakan Laravel framework',
                'urutan' => 6,
            ],
            [
                'kursus_id' => $webDevCourse->id,
                'judul' => 'Database & MySQL',
                'deskripsi' => 'Desain database, query optimization, dan integrasi dengan aplikasi web',
                'urutan' => 7,
            ],
            [
                'kursus_id' => $webDevCourse->id,
                'judul' => 'Final Project',
                'deskripsi' => 'Membangun project web full-stack sebagai portfolio',
                'urutan' => 8,
            ],
        ];

        foreach ($modulData as $modul) {
            Modul::create($modul);
        }

        $this->command->info('Modul Web Development berhasil dibuat!');
    }
}
