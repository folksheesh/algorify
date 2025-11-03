<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kursus;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class KursusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user sebagai pengajar
        $pengajar = User::first();
        
        if (!$pengajar) {
            $this->command->error('Tidak ada user di database. Jalankan seeder User terlebih dahulu.');
            return;
        }

        $kursusData = [
            [
                'judul' => 'Analisis Data',
                'deskripsi' => 'Pelajari teknik analisis data modern menggunakan Python, pandas, dan visualization tools. Kursus ini dirancang untuk pemula hingga intermediate yang ingin menguasai data analysis.',
                'deskripsi_singkat' => 'Pelajar teknik analisis data, statistik, dan visualisasi',
                'kategori' => 'data_science',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(3),
                'status' => 'published',
                'harga' => 500000,
                'thumbnail' => 'thumbnails/analisis-data.jpg',
            ],
            [
                'judul' => 'Analisis Keamanan Siber',
                'deskripsi' => 'Menguasai konsep keamanan jaringan, ethical hacking, dan cybersecurity. Pelajari cara melindungi sistem dari serangan cyber dan vulnerability assessment.',
                'deskripsi_singkat' => 'Menguasai konsep keamanan jaringan, ethical hacking',
                'kategori' => 'programming',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(4),
                'status' => 'published',
                'harga' => 750000,
                'thumbnail' => 'thumbnails/keamanan-siber.jpg',
            ],
            [
                'judul' => 'Desainer UI/UX',
                'deskripsi' => 'Belajar prinsip desain antarmuka, pengalaman pengguna, prototyping, dan user research. Gunakan tools seperti Figma, Adobe XD, dan Sketch.',
                'deskripsi_singkat' => 'Belajar prinsip desain antarmuka, pengalaman pengguna',
                'kategori' => 'design',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(3),
                'status' => 'published',
                'harga' => 600000,
                'thumbnail' => 'thumbnails/uiux.jpg',
            ],
            [
                'judul' => 'IT Support',
                'deskripsi' => 'Dapatkan keterampilan troubleshooting, manajemen sistem, network administration, dan customer service untuk menjadi IT Support professional.',
                'deskripsi_singkat' => 'Dapatkan keterampilan troubleshooting, manajemen',
                'kategori' => 'other',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(2),
                'status' => 'published',
                'harga' => 400000,
                'thumbnail' => 'thumbnails/it-support.jpg',
            ],
            [
                'judul' => 'Web Development',
                'deskripsi' => 'Pelajari HTML, CSS, JavaScript, dan framework modern seperti React, Vue, atau Laravel. Bangun website profesional dari nol hingga deployment.',
                'deskripsi_singkat' => 'Pelajari HTML, CSS, JavaScript dan framework modern',
                'kategori' => 'programming',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(4),
                'status' => 'published',
                'harga' => 800000,
                'thumbnail' => 'thumbnails/web-dev.jpg',
            ],
            [
                'judul' => 'Mobile Development',
                'deskripsi' => 'Kuasai pembuatan aplikasi mobile untuk Android dan iOS menggunakan Flutter atau React Native. Dari UI hingga integrasi API.',
                'deskripsi_singkat' => 'Kuasai pembuatan aplikasi mobile Android dan iOS',
                'kategori' => 'programming',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(4),
                'status' => 'published',
                'harga' => 850000,
                'thumbnail' => 'thumbnails/mobile-dev.jpg',
            ],
            [
                'judul' => 'Digital Marketing',
                'deskripsi' => 'Pelajari strategi digital marketing, SEO, SEM, social media marketing, content marketing, dan analytics untuk meningkatkan bisnis online.',
                'deskripsi_singkat' => 'Pelajari strategi digital marketing dan SEO',
                'kategori' => 'marketing',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(2),
                'status' => 'published',
                'harga' => 450000,
                'thumbnail' => 'thumbnails/digital-marketing.jpg',
            ],
            [
                'judul' => 'AI & Machine Learning',
                'deskripsi' => 'Eksplorasi dunia Artificial Intelligence dan Machine Learning. Pelajari algoritma ML, deep learning, dan implementasi AI dalam berbagai kasus.',
                'deskripsi_singkat' => 'Eksplorasi AI dan Machine Learning',
                'kategori' => 'data_science',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(5),
                'status' => 'published',
                'harga' => 1000000,
                'thumbnail' => 'thumbnails/ai-ml.jpg',
            ],
            [
                'judul' => 'Cloud Computing',
                'deskripsi' => 'Menguasai teknologi cloud seperti AWS, Azure, atau Google Cloud. Pelajari deployment, scaling, dan manajemen infrastructure as code.',
                'deskripsi_singkat' => 'Menguasai teknologi cloud AWS, Azure, GCP',
                'kategori' => 'programming',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(3),
                'status' => 'published',
                'harga' => 700000,
                'thumbnail' => 'thumbnails/cloud.jpg',
            ],
            [
                'judul' => 'Blockchain Development',
                'deskripsi' => 'Pelajari teknologi blockchain, smart contracts, cryptocurrency, dan decentralized applications (DApps). Gunakan Ethereum dan Solidity.',
                'deskripsi_singkat' => 'Pelajari blockchain dan smart contracts',
                'kategori' => 'programming',
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(4),
                'status' => 'published',
                'harga' => 900000,
                'thumbnail' => 'thumbnails/blockchain.jpg',
            ],
        ];

        foreach ($kursusData as $kursus) {
            Kursus::create($kursus);
        }

        $this->command->info('Seeder kursus berhasil dijalankan!');
    }
}
