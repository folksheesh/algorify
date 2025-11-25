<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PilihanJawaban;
use App\Models\Soal;

class PilihanJawabanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $soals = Soal::whereHas('ujian.modul.kursus', function($q) {
            $q->where('judul', 'Web Development');
        })->orderBy('id')->get();

        if ($soals->isEmpty()) {
            $this->command->error('Soal Web Development tidak ditemukan.');
            return;
        }

        $pilihanData = [];

        // Jawaban untuk soal HTML
        // Soal 1: Kepanjangan HTML
        if (isset($soals[0])) {
            $pilihanData[] = ['soal_id' => $soals[0]->id, 'pilihan' => 'HyperText Markup Language', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[0]->id, 'pilihan' => 'High Tech Modern Language', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[0]->id, 'pilihan' => 'Home Tool Markup Language', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[0]->id, 'pilihan' => 'Hyperlinks and Text Markup Language', 'is_correct' => false];
        }

        // Soal 2: Heading terbesar
        if (isset($soals[1])) {
            $pilihanData[] = ['soal_id' => $soals[1]->id, 'pilihan' => '<h1>', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[1]->id, 'pilihan' => '<h6>', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[1]->id, 'pilihan' => '<head>', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[1]->id, 'pilihan' => '<header>', 'is_correct' => false];
        }

        // Soal 3: Alt attribute
        if (isset($soals[2])) {
            $pilihanData[] = ['soal_id' => $soals[2]->id, 'pilihan' => 'alt', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[2]->id, 'pilihan' => 'title', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[2]->id, 'pilihan' => 'src', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[2]->id, 'pilihan' => 'href', 'is_correct' => false];
        }

        // Soal 4: Main tag
        if (isset($soals[3])) {
            $pilihanData[] = ['soal_id' => $soals[3]->id, 'pilihan' => '<main>', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[3]->id, 'pilihan' => '<article>', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[3]->id, 'pilihan' => '<section>', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[3]->id, 'pilihan' => '<div>', 'is_correct' => false];
        }

        // Soal 5: Form elements (multi-answer)
        if (isset($soals[4])) {
            $pilihanData[] = ['soal_id' => $soals[4]->id, 'pilihan' => '<input>', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[4]->id, 'pilihan' => '<textarea>', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[4]->id, 'pilihan' => '<select>', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[4]->id, 'pilihan' => '<button>', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[4]->id, 'pilihan' => '<div>', 'is_correct' => false];
        }

        // Jawaban untuk soal CSS
        // Soal 6: Fungsi CSS
        if (isset($soals[5])) {
            $pilihanData[] = ['soal_id' => $soals[5]->id, 'pilihan' => 'Mengatur tampilan dan layout halaman web', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[5]->id, 'pilihan' => 'Membuat logika program', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[5]->id, 'pilihan' => 'Membuat struktur halaman', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[5]->id, 'pilihan' => 'Mengelola database', 'is_correct' => false];
        }

        // Soal 7: Color property
        if (isset($soals[6])) {
            $pilihanData[] = ['soal_id' => $soals[6]->id, 'pilihan' => 'color', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[6]->id, 'pilihan' => 'text-color', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[6]->id, 'pilihan' => 'font-color', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[6]->id, 'pilihan' => 'background-color', 'is_correct' => false];
        }

        // Soal 8: Box model
        if (isset($soals[7])) {
            $pilihanData[] = ['soal_id' => $soals[7]->id, 'pilihan' => 'Konsep yang menjelaskan bagaimana elemen HTML dibuat sebagai boxes dengan content, padding, border, dan margin', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[7]->id, 'pilihan' => 'Cara membuat kotak di HTML', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[7]->id, 'pilihan' => 'Model untuk database', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[7]->id, 'pilihan' => 'Framework CSS', 'is_correct' => false];
        }

        // Soal 9: Flexbox
        if (isset($soals[8])) {
            $pilihanData[] = ['soal_id' => $soals[8]->id, 'pilihan' => 'flex', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[8]->id, 'pilihan' => 'flexbox', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[8]->id, 'pilihan' => 'grid', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[8]->id, 'pilihan' => 'inline-flex', 'is_correct' => false];
        }

        // Jawaban untuk soal JavaScript
        // Soal 10: let, const, var
        if (isset($soals[9])) {
            $pilihanData[] = ['soal_id' => $soals[9]->id, 'pilihan' => 'let dan const memiliki block scope, const tidak bisa di-reassign, var memiliki function scope', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[9]->id, 'pilihan' => 'Semuanya sama saja', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[9]->id, 'pilihan' => 'var adalah yang paling modern', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[9]->id, 'pilihan' => 'let tidak bisa digunakan dalam loop', 'is_correct' => false];
        }

        // Soal 11: Array push
        if (isset($soals[10])) {
            $pilihanData[] = ['soal_id' => $soals[10]->id, 'pilihan' => 'push()', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[10]->id, 'pilihan' => 'pop()', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[10]->id, 'pilihan' => 'shift()', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[10]->id, 'pilihan' => 'unshift()', 'is_correct' => false];
        }

        // Soal 12: getElementById
        if (isset($soals[11])) {
            $pilihanData[] = ['soal_id' => $soals[11]->id, 'pilihan' => 'document.getElementById("myElement")', 'is_correct' => true];
            $pilihanData[] = ['soal_id' => $soals[11]->id, 'pilihan' => 'document.getElement("myElement")', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[11]->id, 'pilihan' => 'document.querySelector("myElement")', 'is_correct' => false];
            $pilihanData[] = ['soal_id' => $soals[11]->id, 'pilihan' => 'document.getElementByClass("myElement")', 'is_correct' => false];
        }

        foreach ($pilihanData as $pilihan) {
            PilihanJawaban::create($pilihan);
        }

        $this->command->info('Pilihan Jawaban Web Development berhasil dibuat!');
    }
}
