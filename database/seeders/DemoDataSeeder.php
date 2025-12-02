<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Enrollment;
use App\Models\Transaksi;
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

        // Buat 3 kursus untuk pengajar demo
        $kursusPengajarDemo = [
            [
                'judul' => 'Laravel Fundamental untuk Pemula',
                'deskripsi' => 'Belajar framework Laravel dari dasar hingga mahir. Kursus ini mencakup routing, controller, model, view, dan fitur-fitur utama Laravel.',
                'deskripsi_singkat' => 'Belajar Laravel dari dasar hingga mahir',
                'harga' => 299000,
                'durasi' => '20 Jam',
                'tipe_kursus' => 'online',
            ],
            [
                'judul' => 'React.js Modern Web Development',
                'deskripsi' => 'Kuasai React.js untuk membangun aplikasi web modern. Termasuk hooks, context API, dan state management.',
                'deskripsi_singkat' => 'Kuasai React.js untuk aplikasi web modern',
                'harga' => 349000,
                'durasi' => '25 Jam',
                'tipe_kursus' => 'online',
            ],
            [
                'judul' => 'Full Stack JavaScript dengan Node.js',
                'deskripsi' => 'Menjadi full stack developer dengan JavaScript. Belajar Node.js, Express, MongoDB, dan integrasi dengan frontend.',
                'deskripsi_singkat' => 'Menjadi full stack developer dengan JavaScript',
                'harga' => 449000,
                'durasi' => '35 Jam',
                'tipe_kursus' => 'online',
            ],
        ];

        $createdKursus = [];
        foreach ($kursusPengajarDemo as $kursusData) {
            $kursus = Kursus::create([
                'judul' => $kursusData['judul'],
                'deskripsi' => $kursusData['deskripsi'],
                'deskripsi_singkat' => $kursusData['deskripsi_singkat'],
                'harga' => $kursusData['harga'],
                'durasi' => $kursusData['durasi'],
                'tipe_kursus' => $kursusData['tipe_kursus'],
                'user_id' => $pengajarDemo->id,
                'status' => 'published',
                'kategori' => 'programming',
            ]);
            $createdKursus[] = $kursus;
        }

        echo "✓ 3 Kursus untuk Pengajar Demo berhasil dibuat\n";

        // Enroll peserta demo ke 2 kursus
        // Kursus 1: Selesai (progress 100%, status completed)
        $enrollment1 = Enrollment::create([
            'kode' => 'ENR-DEMO0001',
            'user_id' => $pesertaDemo->id,
            'kursus_id' => $createdKursus[0]->id,
            'tanggal_daftar' => Carbon::now()->subMonths(2),
            'status' => 'completed',
            'progress' => 100,
        ]);

        // Buat transaksi untuk enrollment 1 (lunas)
        Transaksi::create([
            'kode_transaksi' => 'TRX-DEMO00001',
            'enrollment_id' => $enrollment1->id,
            'user_id' => $pesertaDemo->id,
            'kursus_id' => $createdKursus[0]->id,
            'tanggal_transaksi' => Carbon::now()->subMonths(2),
            'nominal_pembayaran' => $createdKursus[0]->harga,
            'jumlah' => $createdKursus[0]->harga,
            'status' => 'success',
            'metode_pembayaran' => 'bank_transfer',
            'tanggal_verifikasi' => Carbon::now()->subMonths(2)->addHours(2),
        ]);

        // Kursus 2: Belum mulai (progress 0%, status active)
        $enrollment2 = Enrollment::create([
            'kode' => 'ENR-DEMO0002',
            'user_id' => $pesertaDemo->id,
            'kursus_id' => $createdKursus[1]->id,
            'tanggal_daftar' => Carbon::now()->subDays(3),
            'status' => 'active',
            'progress' => 0,
        ]);

        // Buat transaksi untuk enrollment 2 (lunas)
        Transaksi::create([
            'kode_transaksi' => 'TRX-DEMO00002',
            'enrollment_id' => $enrollment2->id,
            'user_id' => $pesertaDemo->id,
            'kursus_id' => $createdKursus[1]->id,
            'tanggal_transaksi' => Carbon::now()->subDays(3),
            'nominal_pembayaran' => $createdKursus[1]->harga,
            'jumlah' => $createdKursus[1]->harga,
            'status' => 'success',
            'metode_pembayaran' => 'qris',
            'tanggal_verifikasi' => Carbon::now()->subDays(3)->addMinutes(30),
        ]);

        echo "✓ Peserta Demo sudah enroll 2 kursus (1 selesai, 1 progress 0%)\n";
        echo "  - {$createdKursus[0]->judul}: 100% (Selesai)\n";
        echo "  - {$createdKursus[1]->judul}: 0% (Belum mulai)\n";
    }
}
