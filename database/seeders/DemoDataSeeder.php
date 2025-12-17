<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Modul;
use App\Models\Video;
use App\Models\Materi;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use App\Models\Enrollment;
use App\Models\Transaksi;
use App\Models\UserProgress;
use App\Models\Nilai;
use App\Models\Jawaban;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Buat pengajar demo memiliki 3 kursus
     * Peserta demo enroll 2 kursus: 1 selesai (progress 100%), 1 belum mulai (progress 0%)
     */
    public function run(): void
    {
        // Cari pengajar demo
        $pengajarDemo = User::where('email', 'pengajar@example.com')->first();
        
        // Cari peserta demo
        $pesertaDemo = User::where('email', 'peserta@example.com')->first();
        
        if (!$pengajarDemo || !$pesertaDemo) {
            echo "⚠ DemoDataSeeder membutuhkan Pengajar Demo dan Peserta Demo. Jalankan UserSeeder terlebih dahulu.\n";
            return;
        }

        // Hapus data lama peserta demo
        $this->cleanupOldData($pesertaDemo);

        // =====================================================
        // KURSUS 1: LARAVEL FUNDAMENTAL (SELESAI LENGKAP)
        // =====================================================
        $kursus1 = $this->createKursusLengkap($pengajarDemo);
        
        // =====================================================
        // KURSUS 2 & 3: KURSUS TAMBAHAN TANPA MODUL DETAIL
        // =====================================================
        $kursus2 = $this->createKursusTambahan($pengajarDemo, 'React.js Modern Web Development', 
            'Kuasai React.js untuk membangun aplikasi web modern. Termasuk hooks, context API, dan state management.',
            349000, 'https://images.unsplash.com/photo-1633356122102-3fe601e05bd2?w=900&q=80');
        
        $kursus3 = $this->createKursusTambahan($pengajarDemo, 'Full Stack JavaScript dengan Node.js',
            'Menjadi full stack developer dengan JavaScript. Belajar Node.js, Express, MongoDB, dan integrasi dengan frontend.',
            449000, 'https://images.unsplash.com/photo-1627398242454-45a1465c2479?w=900&q=80');

        echo "✓ 3 Kursus untuk Pengajar Demo berhasil dibuat\n";

        // =====================================================
        // PESERTA DEMO: ENROLL DI 2 KURSUS
        // =====================================================

        // Kursus 1: Selesai (progress 100%, status completed, nilai akhir ada)
        $enrollment1 = Enrollment::create([
            'kode' => 'ENR-DEMO0001',
            'user_id' => $pesertaDemo->id,
            'kursus_id' => $kursus1->id,
            'tanggal_daftar' => Carbon::now()->subMonths(2),
            'status' => 'completed',
            'progress' => 100,
            'nilai_akhir' => 92,
        ]);

        // Buat transaksi untuk enrollment 1 (lunas)
        Transaksi::create([
            'kode_transaksi' => 'TRX-DEMO00001',
            'enrollment_id' => $enrollment1->id,
            'user_id' => $pesertaDemo->id,
            'kursus_id' => $kursus1->id,
            'tanggal_transaksi' => Carbon::now()->subMonths(2)->day(15),
            'nominal_pembayaran' => $kursus1->harga,
            'jumlah' => $kursus1->harga,
            'status' => 'success',
            'metode_pembayaran' => 'bank_transfer',
            'tanggal_verifikasi' => Carbon::now()->subMonths(2)->day(15)->addHours(2),
        ]);

        // Buat data progress lengkap untuk kursus 1 (SELESAI)
        $this->createCompletedProgress($pesertaDemo, $kursus1);

        echo "✓ Progress lengkap untuk Peserta Demo di kursus Laravel berhasil dibuat\n";

        // Kursus 2: Belum mulai (progress 0%, status active)
        $enrollment2 = Enrollment::create([
            'kode' => 'ENR-DEMO0002',
            'user_id' => $pesertaDemo->id,
            'kursus_id' => $kursus2->id,
            'tanggal_daftar' => Carbon::now()->subDays(3),
            'status' => 'active',
            'progress' => 0,
            'nilai_akhir' => null,
        ]);

        // Buat transaksi untuk enrollment 2 (lunas)
        Transaksi::create([
            'kode_transaksi' => 'TRX-DEMO00002',
            'enrollment_id' => $enrollment2->id,
            'user_id' => $pesertaDemo->id,
            'kursus_id' => $kursus2->id,
            'tanggal_transaksi' => Carbon::now()->subDays(3)->setTime(14, 30),
            'nominal_pembayaran' => $kursus2->harga,
            'jumlah' => $kursus2->harga,
            'status' => 'success',
            'metode_pembayaran' => 'qris',
            'tanggal_verifikasi' => Carbon::now()->subDays(3)->setTime(14, 45),
        ]);

        echo "✓ Peserta Demo ({$pesertaDemo->email}) enrolled di 2 kursus:\n";
        echo "  - {$kursus1->judul}: 100% (Selesai, Nilai: 92) - DENGAN DATA PROGRESS LENGKAP\n";
        echo "  - {$kursus2->judul}: 0% (Belum mulai)\n";
    }

    /**
     * Hapus data lama peserta demo
     */
    private function cleanupOldData(User $peserta): void
    {
        // Hapus user progress
        UserProgress::where('user_id', $peserta->id)->delete();
        
        // Hapus nilai
        Nilai::where('user_id', $peserta->id)->delete();
        
        // Hapus jawaban
        Jawaban::where('user_id', $peserta->id)->delete();
        
        // Hapus enrollment dan transaksi
        $oldEnrollments = Enrollment::where('user_id', $peserta->id)->get();
        foreach ($oldEnrollments as $enrollment) {
            Transaksi::where('enrollment_id', $enrollment->id)->delete();
        }
        Enrollment::where('user_id', $peserta->id)->delete();
    }

    /**
     * Buat kursus lengkap dengan modul, video, materi, quiz, dan ujian
     */
    private function createKursusLengkap(User $pengajar): Kursus
    {
        // Cek apakah kursus sudah ada
        $existingKursus = Kursus::where('judul', 'Laravel Fundamental untuk Pemula')->first();
        if ($existingKursus) {
            return $existingKursus;
        }

        $kursus = Kursus::create([
            'judul' => 'Laravel Fundamental untuk Pemula',
            'deskripsi' => 'Belajar framework Laravel dari dasar hingga mahir. Kursus ini mencakup routing, controller, model, view, dan fitur-fitur utama Laravel.',
            'deskripsi_singkat' => 'Belajar Laravel dari dasar hingga mahir',
            'harga' => 299000,
            'durasi' => '20 Jam',
            'tipe_kursus' => 'online',
            'thumbnail' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=900&q=80',
            'user_id' => $pengajar->id,
            'status' => 'published',
            'kategori' => 'programming',
        ]);

        // MODUL 1: Pengenalan Laravel
        $modul1 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Pengenalan Laravel',
            'deskripsi' => 'Memahami dasar-dasar Laravel dan ekosistemnya',
            'urutan' => 1,
        ]);

        Video::create([
            'modul_id' => $modul1->id,
            'judul' => 'Apa itu Laravel?',
            'deskripsi' => 'Pengenalan framework Laravel dan keunggulannya',
            'file_video' => 'https://www.youtube.com/watch?v=ImtZ5yENzgE',
            'urutan' => 1,
        ]);

        Video::create([
            'modul_id' => $modul1->id,
            'judul' => 'Instalasi Laravel',
            'deskripsi' => 'Cara install Laravel menggunakan Composer',
            'file_video' => 'https://www.youtube.com/watch?v=MFh0Fd7BsjE',
            'urutan' => 2,
        ]);

        Materi::create([
            'modul_id' => $modul1->id,
            'judul' => 'Struktur Folder Laravel',
            'deskripsi' => 'Memahami struktur folder dalam project Laravel',
            'konten' => '<h1>Struktur Folder Laravel</h1>
<p>Laravel memiliki struktur folder yang terorganisir dengan baik untuk memudahkan pengembangan aplikasi.</p>
<h2>Folder Utama</h2>
<ul>
<li><strong>app/</strong> - Berisi kode aplikasi</li>
<li><strong>config/</strong> - File konfigurasi</li>
<li><strong>database/</strong> - Migrations dan seeders</li>
<li><strong>resources/</strong> - Views dan assets</li>
<li><strong>routes/</strong> - Definisi routes</li>
</ul>',
            'urutan' => 1,
        ]);

        // MODUL 2: Routing & Controller  
        $modul2 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Routing & Controller',
            'deskripsi' => 'Memahami sistem routing dan controller di Laravel',
            'urutan' => 2,
        ]);

        Video::create([
            'modul_id' => $modul2->id,
            'judul' => 'Dasar Routing Laravel',
            'deskripsi' => 'Cara mendefinisikan routes di Laravel',
            'file_video' => 'https://www.youtube.com/watch?v=routing1',
            'urutan' => 1,
        ]);

        Materi::create([
            'modul_id' => $modul2->id,
            'judul' => 'Panduan Routing Laravel',
            'deskripsi' => 'Referensi lengkap routing Laravel',
            'konten' => '<h1>Routing di Laravel</h1>
<p>Routes adalah penghubung antara URL dan logic aplikasi.</p>
<h2>Basic Routing</h2>
<pre><code>Route::get("/", function () {
    return view("welcome");
});</code></pre>',
            'urutan' => 1,
        ]);

        // MODUL 3: Quiz
        $modul3 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Quiz: Laravel Dasar',
            'deskripsi' => 'Evaluasi pemahaman Laravel dasar',
            'urutan' => 3,
        ]);

        $quiz = Ujian::create([
            'kursus_id' => $kursus->id,
            'modul_id' => $modul3->id,
            'judul' => 'Quiz: Laravel Dasar',
            'deskripsi' => 'Test pemahaman dasar Laravel',
            'tipe' => 'practice',
            'waktu_pengerjaan' => 15,
            'minimum_score' => 70,
        ]);

        // Soal 1
        $soal1 = Soal::create([
            'kursus_id' => $kursus->id,
            'ujian_id' => $quiz->id,
            'pertanyaan' => 'Laravel adalah framework untuk bahasa pemrograman apa?',
            'tipe_soal' => 'single',
            'kunci_jawaban' => 'PHP',
        ]);
        PilihanJawaban::create(['soal_id' => $soal1->id, 'pilihan' => 'Python', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1->id, 'pilihan' => 'PHP', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal1->id, 'pilihan' => 'JavaScript', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1->id, 'pilihan' => 'Ruby', 'is_correct' => false]);

        // Soal 2
        $soal2 = Soal::create([
            'kursus_id' => $kursus->id,
            'ujian_id' => $quiz->id,
            'pertanyaan' => 'Perintah untuk menjalankan server development Laravel adalah?',
            'tipe_soal' => 'single',
            'kunci_jawaban' => 'php artisan serve',
        ]);
        PilihanJawaban::create(['soal_id' => $soal2->id, 'pilihan' => 'php artisan start', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal2->id, 'pilihan' => 'php artisan serve', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal2->id, 'pilihan' => 'php artisan run', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal2->id, 'pilihan' => 'laravel serve', 'is_correct' => false]);

        // Soal 3
        $soal3 = Soal::create([
            'kursus_id' => $kursus->id,
            'ujian_id' => $quiz->id,
            'pertanyaan' => 'Folder mana yang berisi file views/template?',
            'tipe_soal' => 'single',
            'kunci_jawaban' => 'resources/',
        ]);
        PilihanJawaban::create(['soal_id' => $soal3->id, 'pilihan' => 'app/', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal3->id, 'pilihan' => 'public/', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal3->id, 'pilihan' => 'resources/', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal3->id, 'pilihan' => 'storage/', 'is_correct' => false]);

        // MODUL 4: Eloquent ORM
        $modul4 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Eloquent ORM',
            'deskripsi' => 'Bekerja dengan database menggunakan Eloquent',
            'urutan' => 4,
        ]);

        Video::create([
            'modul_id' => $modul4->id,
            'judul' => 'Pengenalan Eloquent',
            'deskripsi' => 'Dasar-dasar Eloquent ORM',
            'file_video' => 'https://www.youtube.com/watch?v=eloquent1',
            'urutan' => 1,
        ]);

        Materi::create([
            'modul_id' => $modul4->id,
            'judul' => 'Cheatsheet Eloquent',
            'deskripsi' => 'Referensi cepat Eloquent queries',
            'konten' => '<h1>Eloquent ORM Cheatsheet</h1>
<h2>Basic Queries</h2>
<pre><code>// Get all
$users = User::all();

// Find by ID
$user = User::find(1);

// First matching
$user = User::where("email", "test@test.com")->first();</code></pre>',
            'urutan' => 1,
        ]);

        // MODUL 5: Ujian Akhir
        $modul5 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Ujian Akhir',
            'deskripsi' => 'Evaluasi akhir kursus Laravel',
            'urutan' => 5,
        ]);

        $ujianAkhir = Ujian::create([
            'kursus_id' => $kursus->id,
            'modul_id' => $modul5->id,
            'judul' => 'Ujian Akhir: Laravel Fundamental',
            'deskripsi' => 'Ujian komprehensif mencakup semua materi',
            'tipe' => 'exam',
            'waktu_pengerjaan' => 45,
            'minimum_score' => 75,
        ]);

        // Soal Ujian 1
        $ujianSoal1 = Soal::create([
            'kursus_id' => $kursus->id,
            'ujian_id' => $ujianAkhir->id,
            'pertanyaan' => 'Artisan adalah?',
            'tipe_soal' => 'single',
            'kunci_jawaban' => 'Command line interface Laravel',
        ]);
        PilihanJawaban::create(['soal_id' => $ujianSoal1->id, 'pilihan' => 'Database driver', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal1->id, 'pilihan' => 'Command line interface Laravel', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $ujianSoal1->id, 'pilihan' => 'Template engine', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal1->id, 'pilihan' => 'ORM', 'is_correct' => false]);

        // Soal Ujian 2
        $ujianSoal2 = Soal::create([
            'kursus_id' => $kursus->id,
            'ujian_id' => $ujianAkhir->id,
            'pertanyaan' => 'File .env digunakan untuk?',
            'tipe_soal' => 'single',
            'kunci_jawaban' => 'Menyimpan environment variables',
        ]);
        PilihanJawaban::create(['soal_id' => $ujianSoal2->id, 'pilihan' => 'Menyimpan routes', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal2->id, 'pilihan' => 'Menyimpan environment variables', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $ujianSoal2->id, 'pilihan' => 'Menyimpan views', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal2->id, 'pilihan' => 'Menyimpan models', 'is_correct' => false]);

        // Soal Ujian 3
        $ujianSoal3 = Soal::create([
            'kursus_id' => $kursus->id,
            'ujian_id' => $ujianAkhir->id,
            'pertanyaan' => 'Middleware berfungsi untuk?',
            'tipe_soal' => 'single',
            'kunci_jawaban' => 'Filter HTTP requests',
        ]);
        PilihanJawaban::create(['soal_id' => $ujianSoal3->id, 'pilihan' => 'Membuat database', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal3->id, 'pilihan' => 'Filter HTTP requests', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $ujianSoal3->id, 'pilihan' => 'Render views', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal3->id, 'pilihan' => 'Handle errors', 'is_correct' => false]);

        // Soal Ujian 4
        $ujianSoal4 = Soal::create([
            'kursus_id' => $kursus->id,
            'ujian_id' => $ujianAkhir->id,
            'pertanyaan' => 'Perintah untuk membuat controller adalah?',
            'tipe_soal' => 'single',
            'kunci_jawaban' => 'php artisan make:controller',
        ]);
        PilihanJawaban::create(['soal_id' => $ujianSoal4->id, 'pilihan' => 'php artisan create:controller', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal4->id, 'pilihan' => 'php artisan make:controller', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $ujianSoal4->id, 'pilihan' => 'php artisan new:controller', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal4->id, 'pilihan' => 'laravel make:controller', 'is_correct' => false]);

        // Soal Ujian 5
        $ujianSoal5 = Soal::create([
            'kursus_id' => $kursus->id,
            'ujian_id' => $ujianAkhir->id,
            'pertanyaan' => 'Laravel menggunakan pattern arsitektur?',
            'tipe_soal' => 'single',
            'kunci_jawaban' => 'MVC',
        ]);
        PilihanJawaban::create(['soal_id' => $ujianSoal5->id, 'pilihan' => 'MVP', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal5->id, 'pilihan' => 'MVVM', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $ujianSoal5->id, 'pilihan' => 'MVC', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $ujianSoal5->id, 'pilihan' => 'Microservices', 'is_correct' => false]);

        return $kursus;
    }

    /**
     * Buat kursus tambahan tanpa modul detail
     */
    private function createKursusTambahan(User $pengajar, string $judul, string $deskripsi, int $harga, string $thumbnail): Kursus
    {
        $existingKursus = Kursus::where('judul', $judul)->first();
        if ($existingKursus) {
            return $existingKursus;
        }

        return Kursus::create([
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'deskripsi_singkat' => substr($deskripsi, 0, 50) . '...',
            'harga' => $harga,
            'durasi' => '25 Jam',
            'tipe_kursus' => 'online',
            'thumbnail' => $thumbnail,
            'user_id' => $pengajar->id,
            'status' => 'published',
            'kategori' => 'programming',
        ]);
    }

    /**
     * Buat data progress lengkap untuk kursus yang sudah selesai
     */
    private function createCompletedProgress(User $peserta, Kursus $kursus): void
    {
        $completedAt = Carbon::now()->subWeeks(1);

        // Get all videos in this kursus
        $videos = Video::whereHas('modul', function($q) use ($kursus) {
            $q->where('kursus_id', $kursus->id);
        })->get();

        foreach ($videos as $video) {
            UserProgress::create([
                'user_id' => $peserta->id,
                'kursus_id' => $kursus->id,
                'item_type' => 'video',
                'item_id' => $video->id,
                'status' => 'completed',
                'watch_time' => 1800, // 30 minutes
                'total_duration' => 1800,
                'completed_at' => $completedAt->copy()->subDays(rand(7, 14)),
            ]);
        }

        // Get all materi in this kursus
        $materis = Materi::whereHas('modul', function($q) use ($kursus) {
            $q->where('kursus_id', $kursus->id);
        })->get();

        foreach ($materis as $materi) {
            UserProgress::create([
                'user_id' => $peserta->id,
                'kursus_id' => $kursus->id,
                'item_type' => 'materi',
                'item_id' => $materi->id,
                'status' => 'completed',
                'completed_at' => $completedAt->copy()->subDays(rand(5, 10)),
            ]);
        }

        // Get all ujian/quiz in this kursus
        $ujians = Ujian::where('kursus_id', $kursus->id)->get();

        foreach ($ujians as $ujian) {
            $score = $ujian->tipe === 'exam' ? 92 : 95;
            
            // Create user progress for quiz/ujian
            UserProgress::create([
                'user_id' => $peserta->id,
                'kursus_id' => $kursus->id,
                'item_type' => $ujian->tipe === 'exam' ? 'ujian' : 'quiz',
                'item_id' => $ujian->id,
                'status' => 'completed',
                'score' => $score,
                'passed' => true,
                'completed_at' => $completedAt->copy()->subDays($ujian->tipe === 'exam' ? 1 : rand(2, 5)),
            ]);

            // Create nilai record
            Nilai::create([
                'user_id' => $peserta->id,
                'ujian_id' => $ujian->id,
                'nilai' => $score,
                'status' => 'lulus',
                'tanggal_penilaian' => $completedAt->copy()->subDays($ujian->tipe === 'exam' ? 1 : rand(2, 5)),
            ]);

            // Create jawaban for each soal in this ujian
            $soals = Soal::where('ujian_id', $ujian->id)->get();
            foreach ($soals as $soal) {
                // Get the correct answer
                $correctPilihan = PilihanJawaban::where('soal_id', $soal->id)
                    ->where('is_correct', true)
                    ->first();

                if ($correctPilihan) {
                    Jawaban::create([
                        'soal_id' => $soal->id,
                        'user_id' => $peserta->id,
                        'jawaban' => $correctPilihan->pilihan,
                        'status' => 'benar',
                    ]);
                }
            }
        }
    }
}
