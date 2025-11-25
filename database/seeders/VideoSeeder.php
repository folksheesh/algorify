<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Video;
use App\Models\Modul;

class VideoSeeder extends Seeder
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
            $this->command->error('Modul Web Development tidak ditemukan. Jalankan ModulSeeder terlebih dahulu.');
            return;
        }

        $videoData = [];

        // Modul 1: Pengenalan Web Development
        $videoData[] = [
            'modul_id' => $moduls[0]->id,
            'judul' => 'Apa itu Web Development?',
            'deskripsi' => 'Pengenalan konsep web development, frontend vs backend, dan career path',
            'file_video' => 'https://www.youtube.com/watch?v=example1',
            'urutan' => 1,
        ];
        $videoData[] = [
            'modul_id' => $moduls[0]->id,
            'judul' => 'Tools dan Software yang Dibutuhkan',
            'deskripsi' => 'Setup development environment: VS Code, browser, Git, dan extensions',
            'file_video' => 'https://www.youtube.com/watch?v=example2',
            'urutan' => 2,
        ];

        // Modul 2: HTML Fundamentals
        $videoData[] = [
            'modul_id' => $moduls[1]->id,
            'judul' => 'Struktur Dasar HTML',
            'deskripsi' => 'Memahami struktur dokumen HTML, tags, elements, dan attributes',
            'file_video' => 'https://www.youtube.com/watch?v=example3',
            'urutan' => 1,
        ];
        $videoData[] = [
            'modul_id' => $moduls[1]->id,
            'judul' => 'Semantic HTML',
            'deskripsi' => 'Penggunaan semantic tags: header, nav, main, article, section, footer',
            'file_video' => 'https://www.youtube.com/watch?v=example4',
            'urutan' => 2,
        ];
        $videoData[] = [
            'modul_id' => $moduls[1]->id,
            'judul' => 'Forms dan Input',
            'deskripsi' => 'Membuat form, berbagai tipe input, validation, dan accessibility',
            'file_video' => 'https://www.youtube.com/watch?v=example5',
            'urutan' => 3,
        ];

        // Modul 3: CSS Styling & Layouts
        $videoData[] = [
            'modul_id' => $moduls[2]->id,
            'judul' => 'CSS Selectors dan Properties',
            'deskripsi' => 'Menguasai CSS selectors, specificity, dan commonly used properties',
            'file_video' => 'https://www.youtube.com/watch?v=example6',
            'urutan' => 1,
        ];
        $videoData[] = [
            'modul_id' => $moduls[2]->id,
            'judul' => 'Flexbox Layout',
            'deskripsi' => 'Membangun layout modern dengan CSS Flexbox',
            'file_video' => 'https://www.youtube.com/watch?v=example7',
            'urutan' => 2,
        ];
        $videoData[] = [
            'modul_id' => $moduls[2]->id,
            'judul' => 'CSS Grid',
            'deskripsi' => 'Membuat complex layouts dengan CSS Grid',
            'file_video' => 'https://www.youtube.com/watch?v=example8',
            'urutan' => 3,
        ];
        $videoData[] = [
            'modul_id' => $moduls[2]->id,
            'judul' => 'Responsive Design',
            'deskripsi' => 'Media queries, mobile-first approach, dan breakpoints',
            'file_video' => 'https://www.youtube.com/watch?v=example9',
            'urutan' => 4,
        ];

        // Modul 4: JavaScript Basics
        $videoData[] = [
            'modul_id' => $moduls[3]->id,
            'judul' => 'Variables dan Data Types',
            'deskripsi' => 'Memahami var, let, const, dan berbagai tipe data di JavaScript',
            'file_video' => 'https://www.youtube.com/watch?v=example10',
            'urutan' => 1,
        ];
        $videoData[] = [
            'modul_id' => $moduls[3]->id,
            'judul' => 'Functions dan Scope',
            'deskripsi' => 'Function declaration, expression, arrow functions, dan scope',
            'file_video' => 'https://www.youtube.com/watch?v=example11',
            'urutan' => 2,
        ];
        $videoData[] = [
            'modul_id' => $moduls[3]->id,
            'judul' => 'DOM Manipulation',
            'deskripsi' => 'Mengakses dan memanipulasi elemen HTML dengan JavaScript',
            'file_video' => 'https://www.youtube.com/watch?v=example12',
            'urutan' => 3,
        ];
        $videoData[] = [
            'modul_id' => $moduls[3]->id,
            'judul' => 'Event Handling',
            'deskripsi' => 'Menangani user interactions: click, submit, keyboard events',
            'file_video' => 'https://www.youtube.com/watch?v=example13',
            'urutan' => 4,
        ];

        // Modul 5: JavaScript Advanced
        $videoData[] = [
            'modul_id' => $moduls[4]->id,
            'judul' => 'Asynchronous JavaScript',
            'deskripsi' => 'Callbacks, promises, dan async/await pattern',
            'file_video' => 'https://www.youtube.com/watch?v=example14',
            'urutan' => 1,
        ];
        $videoData[] = [
            'modul_id' => $moduls[4]->id,
            'judul' => 'Fetch API dan AJAX',
            'deskripsi' => 'Mengambil data dari API dan handle responses',
            'file_video' => 'https://www.youtube.com/watch?v=example15',
            'urutan' => 2,
        ];
        $videoData[] = [
            'modul_id' => $moduls[4]->id,
            'judul' => 'ES6+ Features',
            'deskripsi' => 'Destructuring, spread operator, template literals, dan modules',
            'file_video' => 'https://www.youtube.com/watch?v=example16',
            'urutan' => 3,
        ];

        foreach ($videoData as $video) {
            Video::create($video);
        }

        $this->command->info('Video Web Development berhasil dibuat!');
    }
}
