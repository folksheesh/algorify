<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Modul;
use App\Models\Materi;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PelatihanDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Membuat dummy data pelatihan...');

        // 1. Buat User Peserta
        $this->command->info('👤 Membuat user peserta...');
        $user = User::firstOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'tanggal_lahir' => '1995-05-15',
                'jenis_kelamin' => 'L',
                'profesi' => 'Mahasiswa',
                'pendidikan' => 'S1 Teknik Informatika',
            ]
        );

        // Assign role peserta
        $rolePeserta = Role::where('name', 'peserta')->first();
        if ($rolePeserta) {
            $user->assignRole($rolePeserta);
            $this->command->info('✅ User peserta berhasil dibuat dengan role');
        }

        // 2. Buat Kursus "Desain UI/UX"
        $this->command->info('📚 Membuat kursus Desain UI/UX...');
        $kursus = Kursus::create([
            'judul' => 'Desain UI/UX',
            'deskripsi' => 'Pelajar Fundamental UI/UX Design dari nol hingga dapat merancang dan mengaplikasikan analisis user research untuk melakukan prinsip design, wireframing, prototyping, dan user research.',
            'deskripsi_singkat' => 'Pelajar Fundamental UI/UX Design dari nol hingga dapat merancang',
            'kategori' => 'design',
            'user_id' => 1, // Pengajar
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonths(3),
            'status' => 'published',
            'harga' => 600000,
            'thumbnail' => 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=900&q=80',
        ]);

        // 3. Enroll user ke kursus
        $this->command->info('📝 Mendaftarkan user ke kursus...');
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'kursus_id' => $kursus->id,
            'tanggal_daftar' => now(),
            'status' => 'active',
            'progress' => 35,
            'nilai_akhir' => null,
        ]);
        $this->command->info('✅ Enrollment berhasil dibuat: ' . $enrollment->kode);

        // 4. Buat Modul-modul
        $this->command->info('📖 Membuat modul-modul...');
        
        $modul1 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Pengenalan UI/UX Design',
            'deskripsi' => 'Memahami konsep dasar UI/UX, perbedaan UI dan UX, serta peran designer dalam product development',
            'urutan' => 1,
        ]);

        $modul2 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Prinsip Dasar Design',
            'deskripsi' => 'Mempelajari prinsip-prinsip design seperti typography, color theory, layout, dan visual hierarchy',
            'urutan' => 2,
        ]);

        $modul3 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Panduan Design System',
            'deskripsi' => 'Membangun design system yang konsisten, component library, dan style guide',
            'urutan' => 3,
        ]);

        $modul4 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Quiz Dasar UI/UX',
            'deskripsi' => 'Evaluasi pemahaman tentang konsep dasar UI/UX design',
            'urutan' => 4,
        ]);

        $modul5 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Wireframing & Prototyping',
            'deskripsi' => 'Membuat wireframe dan prototype interaktif menggunakan tools modern',
            'urutan' => 5,
        ]);

        $modul6 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'User Research Methods',
            'deskripsi' => 'Teknik riset pengguna, interview, survey, dan usability testing',
            'urutan' => 6,
        ]);

        $modul7 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Usability Testing',
            'deskripsi' => 'Melakukan usability testing dan menganalisis hasil feedback pengguna',
            'urutan' => 7,
        ]);

        $modul8 = Modul::create([
            'kursus_id' => $kursus->id,
            'judul' => 'Quiz Final: UI/UX Design',
            'deskripsi' => 'Evaluasi akhir pemahaman komprehensif tentang UI/UX Design',
            'urutan' => 8,
        ]);

        $this->command->info('✅ 8 Modul berhasil dibuat');

        // 5. Buat Materi untuk beberapa modul
        $this->command->info('📄 Membuat materi pembelajaran...');

        Materi::create([
            'kursus_id' => $kursus->id,
            'tipe' => 'pdf',
            'judul' => 'Modul 1.pdf',
            'file_path' => 'materi/modul-1-pengenalan-uiux.pdf',
        ]);

        Materi::create([
            'kursus_id' => $kursus->id,
            'tipe' => 'pdf',
            'judul' => 'Modul 2.pdf',
            'file_path' => 'materi/modul-2-prinsip-dasar-design.pdf',
        ]);

        Materi::create([
            'kursus_id' => $kursus->id,
            'tipe' => 'video',
            'judul' => 'Video Tutorial: Pengenalan Figma',
            'file_path' => 'materi/video-figma-intro.mp4',
        ]);

        $this->command->info('✅ 3 Materi berhasil dibuat');

        // 6. Buat Quiz 1: Quiz Dasar UI/UX (Modul 4)
        $this->command->info('❓ Membuat Quiz Dasar UI/UX...');
        
        $quiz1 = Ujian::create([
            'kursus_id' => $kursus->id,
            'modul_id' => $modul4->id,
            'judul' => 'Quiz: Dasar UI/UX',
            'deskripsi' => 'Jawab semua soal dengan benar untuk unlock next module dan dapatkan sertifikat.',
            'waktu_mulai' => now(),
            'waktu_selesai' => now()->addDays(30),
            'status' => 'active',
            'tipe' => 'practice',
        ]);

        // Buat soal untuk Quiz 1
        $soal1_1 = Soal::create([
            'kuis_id' => $quiz1->id,
            'kursus_id' => $kursus->id,
            'pertanyaan' => 'Apa kepanjangan dari UI?',
            'kunci_jawaban' => 'User Interface',
        ]);

        PilihanJawaban::create(['soal_id' => $soal1_1->id, 'pilihan' => 'User Interface', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal1_1->id, 'pilihan' => 'Universal Interface', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_1->id, 'pilihan' => 'Unified Interaction', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_1->id, 'pilihan' => 'User Interaction', 'is_correct' => false]);

        $soal1_2 = Soal::create([
            'kuis_id' => $quiz1->id,
            'kursus_id' => $kursus->id,
            'pertanyaan' => 'Apa tujuan utama dari UX Design?',
            'kunci_jawaban' => 'Membuat product mudah dan menyenangkan digunakan',
        ]);

        PilihanJawaban::create(['soal_id' => $soal1_2->id, 'pilihan' => 'Membuat tampilan yang menarik', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_2->id, 'pilihan' => 'Membuat product mudah dan menyenangkan digunakan', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal1_2->id, 'pilihan' => 'Mengikuti trend desain terkini', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_2->id, 'pilihan' => 'Membuat website yang cepat', 'is_correct' => false]);

        $soal1_3 = Soal::create([
            'kuis_id' => $quiz1->id,
            'kursus_id' => $kursus->id,
            'pertanyaan' => 'Apa yang dimaksud dengan wireframe?',
            'kunci_jawaban' => 'Sketsa layout dasar sebelum design visual',
        ]);

        PilihanJawaban::create(['soal_id' => $soal1_3->id, 'pilihan' => 'Design final yang siap diproduksi', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_3->id, 'pilihan' => 'Sketsa layout dasar sebelum design visual', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal1_3->id, 'pilihan' => 'Animasi interaktif', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_3->id, 'pilihan' => 'Kode program website', 'is_correct' => false]);

        $soal1_4 = Soal::create([
            'kuis_id' => $quiz1->id,
            'kursus_id' => $kursus->id,
            'pertanyaan' => 'Manakah yang termasuk prinsip design yang baik?',
            'kunci_jawaban' => 'Konsistensi',
        ]);

        PilihanJawaban::create(['soal_id' => $soal1_4->id, 'pilihan' => 'Konsistensi', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal1_4->id, 'pilihan' => 'Banyak warna', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_4->id, 'pilihan' => 'Font yang beragam', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_4->id, 'pilihan' => 'Animasi yang kompleks', 'is_correct' => false]);

        $soal1_5 = Soal::create([
            'kuis_id' => $quiz1->id,
            'kursus_id' => $kursus->id,
            'pertanyaan' => 'Apa yang dimaksud dengan prototyping?',
            'kunci_jawaban' => 'Membuat model interaktif dari design',
        ]);

        PilihanJawaban::create(['soal_id' => $soal1_5->id, 'pilihan' => 'Menulis kode program', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_5->id, 'pilihan' => 'Membuat model interaktif dari design', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal1_5->id, 'pilihan' => 'Testing bug pada aplikasi', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal1_5->id, 'pilihan' => 'Membuat dokumentasi', 'is_correct' => false]);

        $this->command->info('✅ Quiz 1 dengan 5 soal dan 20 pilihan jawaban');

        // 7. Buat Quiz 2: Quiz Final UI/UX (Modul 8)
        $this->command->info('❓ Membuat Quiz Final UI/UX...');
        
        $quiz2 = Ujian::create([
            'kursus_id' => $kursus->id,
            'modul_id' => $modul8->id,
            'judul' => 'Quiz Final: UI/UX Design',
            'deskripsi' => 'Evaluasi akhir untuk mengukur pemahaman komprehensif tentang UI/UX Design. Kerjakan dengan teliti!',
            'waktu_mulai' => now(),
            'waktu_selesai' => now()->addDays(30),
            'status' => 'active',
            'tipe' => 'exam',
        ]);

        // Buat soal untuk Quiz 2
        $soal2_1 = Soal::create([
            'kuis_id' => $quiz2->id,
            'kursus_id' => $kursus->id,
            'pertanyaan' => 'Apa yang dimaksud dengan Design System?',
            'kunci_jawaban' => 'Kumpulan komponen dan guideline design',
        ]);

        PilihanJawaban::create(['soal_id' => $soal2_1->id, 'pilihan' => 'Software untuk design', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal2_1->id, 'pilihan' => 'Kumpulan komponen dan guideline design', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal2_1->id, 'pilihan' => 'Template website siap pakai', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal2_1->id, 'pilihan' => 'Framework programming', 'is_correct' => false]);

        $soal2_2 = Soal::create([
            'kuis_id' => $quiz2->id,
            'kursus_id' => $kursus->id,
            'pertanyaan' => 'Metode research yang paling efektif untuk memahami user behavior?',
            'kunci_jawaban' => 'User interview dan observation',
        ]);

        PilihanJawaban::create(['soal_id' => $soal2_2->id, 'pilihan' => 'Membaca artikel', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal2_2->id, 'pilihan' => 'User interview dan observation', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal2_2->id, 'pilihan' => 'Asumsi pribadi', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal2_2->id, 'pilihan' => 'Melihat kompetitor', 'is_correct' => false]);

        $soal2_3 = Soal::create([
            'kuis_id' => $quiz2->id,
            'kursus_id' => $kursus->id,
            'pertanyaan' => 'Apa tujuan dari Usability Testing?',
            'kunci_jawaban' => 'Menguji kemudahan penggunaan product',
        ]);

        PilihanJawaban::create(['soal_id' => $soal2_3->id, 'pilihan' => 'Menguji kemudahan penggunaan product', 'is_correct' => true]);
        PilihanJawaban::create(['soal_id' => $soal2_3->id, 'pilihan' => 'Menguji kecepatan loading', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal2_3->id, 'pilihan' => 'Menguji keamanan data', 'is_correct' => false]);
        PilihanJawaban::create(['soal_id' => $soal2_3->id, 'pilihan' => 'Menguji SEO website', 'is_correct' => false]);

        $this->command->info('✅ Quiz 2 dengan 3 soal dan 12 pilihan jawaban');

        // Summary
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('✅ DUMMY DATA BERHASIL DIBUAT!');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('👤 User Peserta:');
        $this->command->info('   - Nama: ' . $user->name);
        $this->command->info('   - Email: ' . $user->email);
        $this->command->info('   - Password: password123');
        $this->command->info('');
        $this->command->info('📚 Kursus: ' . $kursus->judul);
        $this->command->info('   - 8 Modul');
        $this->command->info('   - 3 Materi (PDF & Video)');
        $this->command->info('   - 2 Quiz dengan total 8 soal');
        $this->command->info('   - 32 Pilihan Jawaban');
        $this->command->info('   - Enrollment: ' . $enrollment->kode);
        $this->command->info('   - Progress: ' . $enrollment->progress . '%');
        $this->command->info('═══════════════════════════════════════════');
    }
}
