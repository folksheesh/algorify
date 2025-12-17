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
                'thumbnail' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=900&q=80',
            ],
            [
                'judul' => 'Analisis Keamanan Siber',
                'deskripsi' => 'Menguasai konsep keamanan jaringan, ethical hacking, dan cybersecurity. Pelajari cara melindungi sistem dari serangan cyber dan vulnerability assessment.',
                'deskripsi_singkat' => 'Menguasai konsep keamanan jaringan, ethical hacking',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 750000,
                'thumbnail' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=900&q=80',
            ],
            [
                'judul' => 'Desainer UI/UX',
                'deskripsi' => 'Belajar prinsip desain antarmuka, pengalaman pengguna, prototyping, dan user research. Gunakan tools seperti Figma, Adobe XD, dan Sketch.',
                'deskripsi_singkat' => 'Belajar prinsip desain antarmuka, pengalaman pengguna',
                'kategori' => 'design',
                'status' => 'published',
                'harga' => 600000,
                'thumbnail' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=900&q=80',
            ],
            [
                'judul' => 'IT Support',
                'deskripsi' => 'Dapatkan keterampilan troubleshooting, manajemen sistem, network administration, dan customer service untuk menjadi IT Support professional.',
                'deskripsi_singkat' => 'Dapatkan keterampilan troubleshooting, manajemen',
                'kategori' => 'other',
                'status' => 'published',
                'harga' => 400000,
                'thumbnail' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=900&q=80',
            ],
            [
                'judul' => 'Web Development',
                'deskripsi' => 'Pelajari HTML, CSS, JavaScript, dan framework modern seperti React, Vue, atau Laravel. Bangun website profesional dari nol hingga deployment.',
                'deskripsi_singkat' => 'Pelajari HTML, CSS, JavaScript dan framework modern',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 800000,
                'thumbnail' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=900&q=80',
            ],
            [
                'judul' => 'Mobile Development',
                'deskripsi' => 'Kuasai pembuatan aplikasi mobile untuk Android dan iOS menggunakan Flutter atau React Native. Dari UI hingga integrasi API.',
                'deskripsi_singkat' => 'Kuasai pembuatan aplikasi mobile Android dan iOS',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 850000,
                'thumbnail' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=900&q=80',
            ],
            [
                'judul' => 'Digital Marketing',
                'deskripsi' => 'Pelajari strategi digital marketing, SEO, SEM, social media marketing, content marketing, dan analytics untuk meningkatkan bisnis online.',
                'deskripsi_singkat' => 'Pelajari strategi digital marketing dan SEO',
                'kategori' => 'marketing',
                'status' => 'published',
                'harga' => 450000,
                'thumbnail' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=900&q=80',
            ],
            [
                'judul' => 'AI & Machine Learning',
                'deskripsi' => 'Eksplorasi dunia Artificial Intelligence dan Machine Learning. Pelajari algoritma ML, deep learning, dan implementasi AI dalam berbagai kasus.',
                'deskripsi_singkat' => 'Eksplorasi AI dan Machine Learning',
                'kategori' => 'data_science',
                'status' => 'published',
                'harga' => 1000000,
                'thumbnail' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=900&q=80',
            ],
            [
                'judul' => 'Cloud Computing',
                'deskripsi' => 'Menguasai teknologi cloud seperti AWS, Azure, atau Google Cloud. Pelajari deployment, scaling, dan manajemen infrastructure as code.',
                'deskripsi_singkat' => 'Menguasai teknologi cloud AWS, Azure, GCP',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 700000,
                'thumbnail' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=900&q=80',
            ],
            [
                'judul' => 'Blockchain Development',
                'deskripsi' => 'Pelajari teknologi blockchain, smart contracts, cryptocurrency, dan decentralized applications (DApps). Gunakan Ethereum dan Solidity.',
                'deskripsi_singkat' => 'Pelajari blockchain dan smart contracts',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 900000,
                'thumbnail' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=900&q=80',
            ],
            // 10 kursus tambahan untuk mencapai 20
            [
                'judul' => 'DevOps Engineering',
                'deskripsi' => 'Pelajari praktik DevOps modern termasuk CI/CD, containerization dengan Docker, orchestration dengan Kubernetes, dan infrastructure automation.',
                'deskripsi_singkat' => 'Pelajari CI/CD, Docker, dan Kubernetes',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 850000,
                'thumbnail' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=900&q=80',
            ],
            [
                'judul' => 'Data Visualization',
                'deskripsi' => 'Kuasai teknik visualisasi data menggunakan tools seperti Tableau, Power BI, dan D3.js untuk menyajikan data yang informatif dan menarik.',
                'deskripsi_singkat' => 'Kuasai visualisasi data dengan Tableau & Power BI',
                'kategori' => 'data_science',
                'status' => 'published',
                'harga' => 550000,
                'thumbnail' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=900&q=80',
            ],
            [
                'judul' => 'Python Programming',
                'deskripsi' => 'Kursus lengkap Python dari dasar hingga advanced. Pelajari syntax, OOP, libraries populer, dan buat berbagai project nyata.',
                'deskripsi_singkat' => 'Belajar Python dari dasar hingga advanced',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 650000,
                'thumbnail' => 'https://images.unsplash.com/photo-1526379095098-d400fd0bf935?w=900&q=80',
            ],
            [
                'judul' => 'JavaScript Mastery',
                'deskripsi' => 'Kuasai JavaScript modern termasuk ES6+, async programming, Node.js, dan berbagai framework populer seperti React dan Vue.',
                'deskripsi_singkat' => 'Kuasai JavaScript modern dan Node.js',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 750000,
                'thumbnail' => 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?w=900&q=80',
            ],
            [
                'judul' => 'Database Management',
                'deskripsi' => 'Pelajari manajemen database relasional dan NoSQL. Kuasai MySQL, PostgreSQL, MongoDB, dan Redis untuk berbagai kebutuhan aplikasi.',
                'deskripsi_singkat' => 'Pelajari MySQL, PostgreSQL, dan MongoDB',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 600000,
                'thumbnail' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=900&q=80',
            ],
            [
                'judul' => 'Game Development',
                'deskripsi' => 'Buat game 2D dan 3D menggunakan Unity atau Unreal Engine. Pelajari game design, physics, animation, dan publishing.',
                'deskripsi_singkat' => 'Buat game dengan Unity atau Unreal Engine',
                'kategori' => 'design',
                'status' => 'published',
                'harga' => 950000,
                'thumbnail' => 'https://images.unsplash.com/photo-1556438064-2d7646166914?w=900&q=80',
            ],
            [
                'judul' => 'Project Management',
                'deskripsi' => 'Kuasai metodologi project management seperti Agile, Scrum, dan Kanban. Pelajari tools seperti Jira dan Trello untuk manage project IT.',
                'deskripsi_singkat' => 'Kuasai Agile, Scrum, dan project management tools',
                'kategori' => 'other',
                'status' => 'published',
                'harga' => 500000,
                'thumbnail' => 'https://images.unsplash.com/photo-1507925921958-8a62f3d1a50d?w=900&q=80',
            ],
            [
                'judul' => 'Graphic Design',
                'deskripsi' => 'Pelajari prinsip desain grafis, typography, color theory, dan kuasai tools seperti Adobe Photoshop, Illustrator, dan InDesign.',
                'deskripsi_singkat' => 'Kuasai Adobe Photoshop dan Illustrator',
                'kategori' => 'design',
                'status' => 'published',
                'harga' => 550000,
                'thumbnail' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=900&q=80',
            ],
            [
                'judul' => 'API Development',
                'deskripsi' => 'Belajar membangun RESTful API dan GraphQL. Pelajari authentication, authorization, dokumentasi API, dan best practices.',
                'deskripsi_singkat' => 'Belajar RESTful API dan GraphQL',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 700000,
                'thumbnail' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=900&q=80',
            ],
            [
                'judul' => 'Internet of Things (IoT)',
                'deskripsi' => 'Eksplorasi dunia IoT dengan Arduino, Raspberry Pi, dan sensor. Buat project smart home dan industrial IoT applications.',
                'deskripsi_singkat' => 'Belajar IoT dengan Arduino dan Raspberry Pi',
                'kategori' => 'programming',
                'status' => 'published',
                'harga' => 800000,
                'thumbnail' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=900&q=80',
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
