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
        // Get pengajar users
        $pengajarUsers = User::role('pengajar')->get();
        
        if ($pengajarUsers->isEmpty()) {
            $pengajar = User::first();
            if (!$pengajar) {
                $this->command->error('Tidak ada user di database. Jalankan seeder User terlebih dahulu.');
                return;
            }
            $pengajarUsers = collect([$pengajar]);
        }

        // Cari pengajar demo (pengajar@example.com) dan letakkan di awal
        $pengajarDemo = $pengajarUsers->firstWhere('email', 'pengajar@example.com');
        if ($pengajarDemo) {
            // Hapus dari koleksi dan taruh di awal
            $pengajarUsers = $pengajarUsers->reject(function($user) use ($pengajarDemo) {
                return $user->id === $pengajarDemo->id;
            })->prepend($pengajarDemo);
        }

        $kursusData = [
            [
                'judul' => 'Analisis Data',
                'deskripsi' => 'Pelajari teknik analisis data modern menggunakan Python, pandas, dan visualization tools. Kursus ini dirancang untuk pemula hingga intermediate yang ingin menguasai data analysis.',
                'deskripsi_singkat' => 'Pelajar teknik analisis data, statistik, dan visualisasi',
                'kategori' => 'data_science',
                'status' => 'published',
                'harga' => 500000,
                'thumbnail' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Analisis Keamanan Siber',
                'deskripsi' => 'Menguasai konsep keamanan jaringan, ethical hacking, dan cybersecurity. Pelajari cara melindungi sistem dari serangan cyber dan vulnerability assessment.',
                'deskripsi_singkat' => 'Menguasai konsep keamanan jaringan, ethical hacking',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 750000,
                'thumbnail' => 'https://images.unsplash.com/photo-1508385082359-fc9a1db96a31?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Desainer UI/UX',
                'deskripsi' => 'Belajar prinsip desain antarmuka, pengalaman pengguna, prototyping, dan user research. Gunakan tools seperti Figma, Adobe XD, dan Sketch.',
                'deskripsi_singkat' => 'Belajar prinsip desain antarmuka, pengalaman pengguna',
                'kategori' => 'design',
                'status' => 'published',
                'harga' => 600000,
                'thumbnail' => 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'IT Support',
                'deskripsi' => 'Dapatkan keterampilan troubleshooting, manajemen sistem, network administration, dan customer service untuk menjadi IT Support professional.',
                'deskripsi_singkat' => 'Dapatkan keterampilan troubleshooting, manajemen',
                'kategori' => 'other',
                'status' => 'published',
                'harga' => 400000,
                'thumbnail' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Web Development',
                'deskripsi' => 'Pelajari HTML, CSS, JavaScript, dan framework modern seperti React, Vue, atau Laravel. Bangun website profesional dari nol hingga deployment.',
                'deskripsi_singkat' => 'Pelajari HTML, CSS, JavaScript dan framework modern',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 800000,
                'thumbnail' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Mobile Development',
                'deskripsi' => 'Kuasai pembuatan aplikasi mobile untuk Android dan iOS menggunakan Flutter atau React Native. Dari UI hingga integrasi API.',
                'deskripsi_singkat' => 'Kuasai pembuatan aplikasi mobile Android dan iOS',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 850000,
                'thumbnail' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Digital Marketing',
                'deskripsi' => 'Pelajari strategi digital marketing, SEO, SEM, social media marketing, content marketing, dan analytics untuk meningkatkan bisnis online.',
                'deskripsi_singkat' => 'Pelajari strategi digital marketing dan SEO',
                'kategori' => 'marketing',
                'status' => 'published',
                'harga' => 450000,
                'thumbnail' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'AI & Machine Learning',
                'deskripsi' => 'Eksplorasi dunia Artificial Intelligence dan Machine Learning. Pelajari algoritma ML, deep learning, dan implementasi AI dalam berbagai kasus.',
                'deskripsi_singkat' => 'Eksplorasi AI dan Machine Learning',
                'kategori' => 'data_science',
                'status' => 'published',
                'harga' => 1000000,
                'thumbnail' => 'https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Cloud Computing',
                'deskripsi' => 'Menguasai teknologi cloud seperti AWS, Azure, atau Google Cloud. Pelajari deployment, scaling, dan manajemen infrastructure as code.',
                'deskripsi_singkat' => 'Menguasai teknologi cloud AWS, Azure, GCP',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 700000,
                'thumbnail' => 'https://images.unsplash.com/photo-1509395062183-67c5ad6faff9?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Blockchain Development',
                'deskripsi' => 'Pelajari teknologi blockchain, smart contracts, cryptocurrency, dan decentralized applications (DApps). Gunakan Ethereum dan Solidity.',
                'deskripsi_singkat' => 'Pelajari blockchain dan smart contracts',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 900000,
                'thumbnail' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=900&q=80',
            ],
            // 10 kursus tambahan untuk mencapai 20
            [
                'judul' => 'DevOps Engineering',
                'deskripsi' => 'Pelajari praktik DevOps modern termasuk CI/CD, containerization dengan Docker, orchestration dengan Kubernetes, dan infrastructure automation.',
                'deskripsi_singkat' => 'Pelajari CI/CD, Docker, dan Kubernetes',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 850000,
                'thumbnail' => 'https://images.unsplash.com/photo-1488161628813-04466f872be2?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Data Visualization',
                'deskripsi' => 'Kuasai teknik visualisasi data menggunakan tools seperti Tableau, Power BI, dan D3.js untuk menyajikan data yang informatif dan menarik.',
                'deskripsi_singkat' => 'Kuasai visualisasi data dengan Tableau & Power BI',
                'kategori' => 'data_science',
                'status' => 'published',
                'harga' => 550000,
                'thumbnail' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Python Programming',
                'deskripsi' => 'Kursus lengkap Python dari dasar hingga advanced. Pelajari syntax, OOP, libraries populer, dan buat berbagai project nyata.',
                'deskripsi_singkat' => 'Belajar Python dari dasar hingga advanced',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 650000,
                'thumbnail' => 'https://images.unsplash.com/photo-1517433456452-f9633a875f6f?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'JavaScript Mastery',
                'deskripsi' => 'Kuasai JavaScript modern termasuk ES6+, async programming, Node.js, dan berbagai framework populer seperti React dan Vue.',
                'deskripsi_singkat' => 'Kuasai JavaScript modern dan Node.js',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 750000,
                'thumbnail' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Database Management',
                'deskripsi' => 'Pelajari manajemen database relasional dan NoSQL. Kuasai MySQL, PostgreSQL, MongoDB, dan Redis untuk berbagai kebutuhan aplikasi.',
                'deskripsi_singkat' => 'Pelajari MySQL, PostgreSQL, dan MongoDB',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 600000,
                'thumbnail' => 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Game Development',
                'deskripsi' => 'Buat game 2D dan 3D menggunakan Unity atau Unreal Engine. Pelajari game design, physics, animation, dan publishing.',
                'deskripsi_singkat' => 'Buat game dengan Unity atau Unreal Engine',
                'kategori' => 'design',
                'status' => 'published',
                'harga' => 950000,
                'thumbnail' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Project Management',
                'deskripsi' => 'Kuasai metodologi project management seperti Agile, Scrum, dan Kanban. Pelajari tools seperti Jira dan Trello untuk manage project IT.',
                'deskripsi_singkat' => 'Kuasai Agile, Scrum, dan project management tools',
                'kategori' => 'other',
                'status' => 'published',
                'harga' => 500000,
                'thumbnail' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Graphic Design',
                'deskripsi' => 'Pelajari prinsip desain grafis, typography, color theory, dan kuasai tools seperti Adobe Photoshop, Illustrator, dan InDesign.',
                'deskripsi_singkat' => 'Kuasai Adobe Photoshop dan Illustrator',
                'kategori' => 'design',
                'status' => 'published',
                'harga' => 550000,
                'thumbnail' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'API Development',
                'deskripsi' => 'Belajar membangun RESTful API dan GraphQL. Pelajari authentication, authorization, dokumentasi API, dan best practices.',
                'deskripsi_singkat' => 'Belajar RESTful API dan GraphQL',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 700000,
                'thumbnail' => 'https://images.unsplash.com/photo-1508385082359-fc9a1db96a31?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'judul' => 'Internet of Things (IoT)',
                'deskripsi' => 'Eksplorasi dunia IoT dengan Arduino, Raspberry Pi, dan sensor. Buat project smart home dan industrial IoT applications.',
                'deskripsi_singkat' => 'Belajar IoT dengan Arduino dan Raspberry Pi',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 800000,
                'thumbnail' => 'https://images.unsplash.com/photo-1487058792275-0ad4aaf24ca7?auto=format&fit=crop&w=900&q=80',
            ],
        ];

        foreach ($kursusData as $index => $kursus) {
            // Assign different pengajar for variety
            $pengajar = $pengajarUsers[$index % $pengajarUsers->count()];
            
            Kursus::create([
                'judul' => $kursus['judul'],
                'deskripsi' => $kursus['deskripsi'],
                'deskripsi_singkat' => $kursus['deskripsi_singkat'],
                'kategori' => $kursus['kategori'],
                'user_id' => $pengajar->id,
                'tanggal_mulai' => now()->addDays(rand(0, 30)),
                'tanggal_selesai' => now()->addMonths(rand(2, 5)),
                'status' => $kursus['status'],
                'harga' => $kursus['harga'],
                'thumbnail' => $kursus['thumbnail'],
            ]);
        }

        $this->command->info('✓ KursusSeeder berhasil! Total: ' . count($kursusData) . ' kursus');
    }
}
