<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankSoal;
use App\Models\Kursus;
use App\Models\User;

class BankSoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil admin pertama sebagai pembuat soal
        $admin = User::role('admin')->first();
        if (!$admin) {
            $admin = User::first();
        }

        // Ambil beberapa kursus sebagai kategori
        $programming = Kursus::where('kategori', 'programming')->first();
        $dataScience = Kursus::where('kategori', 'data_science')->first();
        $design = Kursus::where('kategori', 'design')->first();

        $soalData = [
            // Soal Programming - Pilihan Ganda
            [
                'pertanyaan' => 'Apa kepanjangan dari HTML?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'Hyper Text Markup Language',
                    'High Tech Modern Language',
                    'Home Tool Markup Language',
                    'Hyperlinks and Text Markup Language'
                ],
                'jawaban_benar' => [0],
                'kategori_id' => $programming?->id,
                'poin' => 2,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Bahasa pemrograman mana yang berjalan di browser?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'Python',
                    'Java',
                    'JavaScript',
                    'C++'
                ],
                'jawaban_benar' => [2],
                'kategori_id' => $programming?->id,
                'poin' => 1,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Apa fungsi dari CSS dalam web development?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'Untuk styling dan layout halaman web',
                    'Untuk membuat database',
                    'Untuk menjalankan server',
                    'Untuk membuat animasi 3D'
                ],
                'jawaban_benar' => [0],
                'kategori_id' => $programming?->id,
                'poin' => 2,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Metode HTTP mana yang digunakan untuk mengirim data form?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'GET',
                    'POST',
                    'PUT',
                    'DELETE'
                ],
                'jawaban_benar' => [1],
                'kategori_id' => $programming?->id,
                'poin' => 3,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Framework PHP yang populer untuk web development adalah?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'Django',
                    'Laravel',
                    'Spring',
                    'Express'
                ],
                'jawaban_benar' => [1],
                'kategori_id' => $programming?->id,
                'poin' => 2,
                'created_by' => $admin->id
            ],

            // Soal Data Science - Pilihan Ganda
            [
                'pertanyaan' => 'Library Python yang paling populer untuk data analysis adalah?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'NumPy',
                    'Pandas',
                    'Matplotlib',
                    'Scikit-learn'
                ],
                'jawaban_benar' => [1],
                'kategori_id' => $dataScience?->id,
                'poin' => 3,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Apa yang dimaksud dengan overfitting dalam machine learning?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'Model terlalu sederhana',
                    'Model terlalu kompleks dan menghafal data training',
                    'Model tidak bisa training',
                    'Model terlalu cepat training'
                ],
                'jawaban_benar' => [1],
                'kategori_id' => $dataScience?->id,
                'poin' => 4,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Algoritma supervised learning untuk klasifikasi adalah?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'K-Means',
                    'Decision Tree',
                    'PCA',
                    'DBSCAN'
                ],
                'jawaban_benar' => [1],
                'kategori_id' => $dataScience?->id,
                'poin' => 3,
                'created_by' => $admin->id
            ],

            // Soal Multi Jawaban
            [
                'pertanyaan' => 'Pilih semua yang termasuk frontend framework JavaScript:',
                'tipe_soal' => 'multi_jawaban',
                'opsi_jawaban' => [
                    'React',
                    'Laravel',
                    'Vue.js',
                    'Django',
                    'Angular',
                    'Flask'
                ],
                'jawaban_benar' => [0, 2, 4],
                'kategori_id' => $programming?->id,
                'poin' => 5,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Manakah yang termasuk database NoSQL? (Pilih semua yang benar)',
                'tipe_soal' => 'multi_jawaban',
                'opsi_jawaban' => [
                    'MySQL',
                    'MongoDB',
                    'PostgreSQL',
                    'Redis',
                    'Cassandra'
                ],
                'jawaban_benar' => [1, 3, 4],
                'kategori_id' => $programming?->id,
                'poin' => 4,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Pilih prinsip-prinsip OOP (Object Oriented Programming):',
                'tipe_soal' => 'multi_jawaban',
                'opsi_jawaban' => [
                    'Encapsulation',
                    'Compilation',
                    'Inheritance',
                    'Debugging',
                    'Polymorphism',
                    'Abstraction'
                ],
                'jawaban_benar' => [0, 2, 4, 5],
                'kategori_id' => $programming?->id,
                'poin' => 5,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Tools mana saja yang digunakan untuk version control?',
                'tipe_soal' => 'multi_jawaban',
                'opsi_jawaban' => [
                    'Git',
                    'Docker',
                    'SVN',
                    'Kubernetes',
                    'Mercurial'
                ],
                'jawaban_benar' => [0, 2, 4],
                'kategori_id' => $programming?->id,
                'poin' => 3,
                'created_by' => $admin->id
            ],

            // Soal Design
            [
                'pertanyaan' => 'Software Adobe yang digunakan untuk vector graphic adalah?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'Photoshop',
                    'Illustrator',
                    'Premiere',
                    'After Effects'
                ],
                'jawaban_benar' => [1],
                'kategori_id' => $design?->id,
                'poin' => 2,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Apa kepanjangan dari UI/UX?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'User Interface / User Experience',
                    'Universal Interface / User Extension',
                    'Uniform Interface / Unified Experience',
                    'User Input / User Export'
                ],
                'jawaban_benar' => [0],
                'kategori_id' => $design?->id,
                'poin' => 1,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Pilih prinsip-prinsip desain yang baik:',
                'tipe_soal' => 'multi_jawaban',
                'opsi_jawaban' => [
                    'Contrast',
                    'Complexity',
                    'Balance',
                    'Randomness',
                    'Hierarchy',
                    'Alignment'
                ],
                'jawaban_benar' => [0, 2, 4, 5],
                'kategori_id' => $design?->id,
                'poin' => 4,
                'created_by' => $admin->id
            ],

            // Soal tambahan
            [
                'pertanyaan' => 'Apa yang dimaksud dengan responsive design?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'Design yang cepat loading',
                    'Design yang menyesuaikan dengan ukuran layar',
                    'Design dengan banyak animasi',
                    'Design dengan warna cerah'
                ],
                'jawaban_benar' => [1],
                'kategori_id' => $programming?->id,
                'poin' => 2,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'API adalah singkatan dari?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'Advanced Programming Interface',
                    'Application Programming Interface',
                    'Automated Program Interaction',
                    'Application Process Integration'
                ],
                'jawaban_benar' => [1],
                'kategori_id' => $programming?->id,
                'poin' => 1,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Manakah yang termasuk tipe data primitif di JavaScript?',
                'tipe_soal' => 'multi_jawaban',
                'opsi_jawaban' => [
                    'String',
                    'Array',
                    'Number',
                    'Object',
                    'Boolean',
                    'Undefined'
                ],
                'jawaban_benar' => [0, 2, 4, 5],
                'kategori_id' => $programming?->id,
                'poin' => 4,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Apa fungsi dari SQL JOIN?',
                'tipe_soal' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    'Menghapus data',
                    'Menggabungkan data dari dua atau lebih tabel',
                    'Membuat tabel baru',
                    'Mengupdate data'
                ],
                'jawaban_benar' => [1],
                'kategori_id' => $programming?->id,
                'poin' => 3,
                'created_by' => $admin->id
            ],
            [
                'pertanyaan' => 'Library Python untuk visualisasi data adalah?',
                'tipe_soal' => 'multi_jawaban',
                'opsi_jawaban' => [
                    'Matplotlib',
                    'Django',
                    'Seaborn',
                    'Flask',
                    'Plotly',
                    'FastAPI'
                ],
                'jawaban_benar' => [0, 2, 4],
                'kategori_id' => $dataScience?->id,
                'poin' => 4,
                'created_by' => $admin->id
            ]
        ];

        // Insert semua data soal
        foreach ($soalData as $soal) {
            BankSoal::create($soal);
        }

        $this->command->info('✓ Bank Soal seeder berhasil! Total: ' . count($soalData) . ' soal');
    }
}
