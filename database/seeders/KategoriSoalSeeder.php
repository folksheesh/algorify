<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriSoal;

class KategoriSoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama' => 'Pemrograman Dasar',
                'kode' => 'PROG-DASAR',
                'deskripsi' => 'Soal tentang konsep dasar pemrograman',
                'warna' => '#667eea'
            ],
            [
                'nama' => 'Algoritma',
                'kode' => 'ALGORITMA',
                'deskripsi' => 'Soal tentang algoritma dan struktur data',
                'warna' => '#10B981'
            ],
            [
                'nama' => 'Web Development',
                'kode' => 'WEB-DEV',
                'deskripsi' => 'Soal tentang pengembangan web',
                'warna' => '#3B82F6'
            ],
            [
                'nama' => 'Database',
                'kode' => 'DATABASE',
                'deskripsi' => 'Soal tentang basis data',
                'warna' => '#F59E0B'
            ],
            [
                'nama' => 'Mobile Development',
                'kode' => 'MOBILE-DEV',
                'deskripsi' => 'Soal tentang pengembangan aplikasi mobile',
                'warna' => '#8B5CF6'
            ],
            [
                'nama' => 'Blockchain',
                'kode' => 'BLOCKCHAIN',
                'deskripsi' => 'Soal tentang teknologi blockchain',
                'warna' => '#EF4444'
            ],
            [
                'nama' => 'UI/UX Design',
                'kode' => 'UIUX',
                'deskripsi' => 'Soal tentang desain antarmuka dan pengalaman pengguna',
                'warna' => '#EC4899'
            ],
            [
                'nama' => 'Keamanan Siber',
                'kode' => 'CYBERSEC',
                'deskripsi' => 'Soal tentang keamanan cyber',
                'warna' => '#6366F1'
            ]
        ];

        foreach ($kategoris as $kategori) {
            KategoriSoal::create($kategori);
        }
    }
}
