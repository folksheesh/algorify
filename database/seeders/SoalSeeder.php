<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Soal;
use App\Models\Ujian;

class SoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ujians = Ujian::whereHas('modul.kursus', function($q) {
            $q->where('judul', 'Web Development');
        })->get();

        if ($ujians->isEmpty()) {
            $this->command->error('Ujian Web Development tidak ditemukan.');
            return;
        }

        // Quiz HTML
        $htmlQuiz = $ujians->where('judul', 'Quiz HTML Fundamentals')->first();
        if ($htmlQuiz) {
            $kursusId = $htmlQuiz->kursus_id;
            $soalData = [
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $htmlQuiz->id,
                    'pertanyaan' => 'Apa kepanjangan dari HTML?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => 'HyperText Markup Language',
                    'pembahasan' => 'HTML adalah singkatan dari HyperText Markup Language, bahasa markup standar untuk membuat halaman web.',
                ],
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $htmlQuiz->id,
                    'pertanyaan' => 'Tag HTML mana yang digunakan untuk membuat heading terbesar?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => '<h1>',
                    'pembahasan' => 'Tag &lt;h1&gt; digunakan untuk heading level 1 yang merupakan heading terbesar. Semakin besar angkanya (h2, h3, dst), semakin kecil ukuran heading.',
                ],
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $htmlQuiz->id,
                    'pertanyaan' => 'Attribute mana yang digunakan untuk memberikan teks alternatif pada gambar?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => 'alt',
                    'pembahasan' => 'Attribute "alt" digunakan untuk memberikan teks alternatif pada gambar, sangat penting untuk accessibility dan SEO.',
                ],
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $htmlQuiz->id,
                    'pertanyaan' => 'Tag semantic HTML mana yang digunakan untuk konten utama halaman?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => '<main>',
                    'pembahasan' => 'Tag &lt;main&gt; digunakan untuk menandai konten utama halaman. Hanya boleh ada satu &lt;main&gt; per halaman.',
                ],
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $htmlQuiz->id,
                    'pertanyaan' => 'Pilih tag-tag yang termasuk dalam kategori form elements (bisa lebih dari 1):',
                    'tipe_soal' => 'multiple',
                    'kunci_jawaban' => '<input>,<textarea>,<select>,<button>',
                    'pembahasan' => 'Form elements mencakup &lt;input&gt;, &lt;textarea&gt;, &lt;select&gt;, dan &lt;button&gt;. Semua tag ini digunakan untuk membuat form interaktif.',
                ],
            ];

            foreach ($soalData as $soal) {
                Soal::create($soal);
            }
        }

        // Quiz CSS
        $cssQuiz = $ujians->where('judul', 'Quiz CSS Styling')->first();
        if ($cssQuiz) {
            $kursusId = $cssQuiz->kursus_id;
            $soalData = [
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $cssQuiz->id,
                    'pertanyaan' => 'Apa fungsi dari CSS?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => 'Mengatur tampilan dan layout halaman web',
                    'pembahasan' => 'CSS (Cascading Style Sheets) digunakan untuk mengatur tampilan dan layout halaman web.',
                ],
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $cssQuiz->id,
                    'pertanyaan' => 'Property CSS mana yang digunakan untuk mengubah warna teks?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => 'color',
                    'pembahasan' => 'Property "color" digunakan untuk mengubah warna teks. Contoh: color: red; atau color: #FF0000;',
                ],
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $cssQuiz->id,
                    'pertanyaan' => 'Apa yang dimaksud dengan box model dalam CSS?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => 'Konsep yang menjelaskan bagaimana elemen HTML dibuat sebagai boxes dengan content, padding, border, dan margin',
                    'pembahasan' => 'Box model adalah konsep yang menjelaskan bagaimana elemen HTML dibuat sebagai boxes dengan content, padding, border, dan margin.',
                ],
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $cssQuiz->id,
                    'pertanyaan' => 'Display property mana yang digunakan untuk Flexbox?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => 'flex',
                    'pembahasan' => 'display: flex; digunakan untuk mengaktifkan Flexbox layout pada sebuah container.',
                ],
            ];

            foreach ($soalData as $soal) {
                Soal::create($soal);
            }
        }

        // Quiz JavaScript Basics
        $jsBasicQuiz = $ujians->where('judul', 'Quiz JavaScript Basics')->first();
        if ($jsBasicQuiz) {
            $kursusId = $jsBasicQuiz->kursus_id;
            $soalData = [
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $jsBasicQuiz->id,
                    'pertanyaan' => 'Apa perbedaan antara let, const, dan var dalam JavaScript?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => 'let dan const memiliki block scope, const tidak bisa di-reassign, var memiliki function scope',
                    'pembahasan' => 'let dan const memiliki block scope dan tidak bisa di-redeclare. const tidak bisa di-reassign. var memiliki function scope dan bisa di-redeclare.',
                ],
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $jsBasicQuiz->id,
                    'pertanyaan' => 'Method mana yang digunakan untuk menambahkan element ke akhir array?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => 'push()',
                    'pembahasan' => 'Method push() menambahkan element ke akhir array dan return length baru array.',
                ],
                [
                    'kursus_id' => $kursusId,
                    'ujian_id' => $jsBasicQuiz->id,
                    'pertanyaan' => 'Bagaimana cara mengakses element dengan id "myElement" menggunakan JavaScript?',
                    'tipe_soal' => 'single',
                    'kunci_jawaban' => 'document.getElementById("myElement")',
                    'pembahasan' => 'document.getElementById("myElement") adalah cara standard untuk mengakses element by ID.',
                ],
            ];

            foreach ($soalData as $soal) {
                Soal::create($soal);
            }
        }

        $this->command->info('Soal Web Development berhasil dibuat!');
    }
}
