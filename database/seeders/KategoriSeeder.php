<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriPelatihan;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama_kategori' => 'Programming',
                'slug' => 'programming',
                'deskripsi' => 'Kategori untuk kursus pemrograman dan pengembangan software'
            ],
            [
                'nama_kategori' => 'Data Science',
                'slug' => 'data-science',
                'deskripsi' => 'Kategori untuk kursus data science, analisis data, dan machine learning'
            ],
            [
                'nama_kategori' => 'Design',
                'slug' => 'design',
                'deskripsi' => 'Kategori untuk kursus desain grafis, UI/UX, dan desain produk'
            ],
            [
                'nama_kategori' => 'Marketing',
                'slug' => 'marketing',
                'deskripsi' => 'Kategori untuk kursus digital marketing, SEO, dan strategi pemasaran'
            ],
            [
                'nama_kategori' => 'AI & ML',
                'slug' => 'ai-ml',
                'deskripsi' => 'Kategori untuk kursus Artificial Intelligence dan Machine Learning'
            ],
            [
                'nama_kategori' => 'Web Development',
                'slug' => 'web-development',
                'deskripsi' => 'Kategori untuk kursus pengembangan website dan aplikasi web'
            ],
            [
                'nama_kategori' => 'Mobile Development',
                'slug' => 'mobile-development',
                'deskripsi' => 'Kategori untuk kursus pengembangan aplikasi mobile Android dan iOS'
            ],
            [
                'nama_kategori' => 'Security',
                'slug' => 'security',
                'deskripsi' => 'Kategori untuk kursus cybersecurity dan keamanan informasi'
            ],
            [
                'nama_kategori' => 'Blockchain',
                'slug' => 'blockchain',
                'deskripsi' => 'Kategori untuk kursus blockchain, cryptocurrency, dan Web3'
            ],
            [
                'nama_kategori' => 'Cloud Computing',
                'slug' => 'cloud-computing',
                'deskripsi' => 'Kategori untuk kursus cloud computing, AWS, Azure, dan GCP'
            ],
        ];

        foreach ($categories as $category) {
            KategoriPelatihan::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
