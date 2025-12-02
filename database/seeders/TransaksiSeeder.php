<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Enrollment;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Enrollment hanya dibuat jika transaksi berhasil (success)
     * Tanggal transaksi tersebar di berbagai bulan dan tanggal yang berbeda
     * Enrollment tanggal_daftar sama dengan tanggal transaksi sukses
     */
    public function run(): void
    {
        // Get peserta users (exclude peserta demo karena sudah dihandle DemoDataSeeder)
        $pesertaUsers = User::role('peserta')
            ->where('email', '!=', 'peserta@example.com')
            ->get();
        
        // Get all kursus
        $kursusList = Kursus::all();
        
        if ($pesertaUsers->isEmpty() || $kursusList->isEmpty()) {
            echo "⚠ TransaksiSeeder membutuhkan data User (peserta) dan Kursus. Jalankan UserSeeder dan KursusSeeder terlebih dahulu.\n";
            return;
        }

        // Valid enum values setelah migration: 'bank_transfer', 'e_wallet', 'credit_card', 'qris', 'mini_market', 'kartu_debit'
        $metodePembayaran = ['bank_transfer', 'e_wallet', 'credit_card', 'qris', 'mini_market', 'kartu_debit'];
        
        $transaksiCount = 0;
        $enrollmentCount = 0;

        // Daftar tanggal spesifik tersebar di 6 bulan (Juli - Desember 2025)
        $specificDates = [
            // Juli 2025
            Carbon::create(2025, 7, 3, 9, 15, 0),
            Carbon::create(2025, 7, 8, 14, 30, 0),
            Carbon::create(2025, 7, 15, 10, 45, 0),
            Carbon::create(2025, 7, 22, 16, 20, 0),
            // Agustus 2025
            Carbon::create(2025, 8, 5, 11, 0, 0),
            Carbon::create(2025, 8, 12, 13, 30, 0),
            Carbon::create(2025, 8, 19, 9, 45, 0),
            Carbon::create(2025, 8, 28, 15, 15, 0),
            // September 2025
            Carbon::create(2025, 9, 2, 10, 30, 0),
            Carbon::create(2025, 9, 10, 14, 0, 0),
            Carbon::create(2025, 9, 18, 11, 45, 0),
            Carbon::create(2025, 9, 25, 16, 30, 0),
            // Oktober 2025
            Carbon::create(2025, 10, 3, 9, 0, 0),
            Carbon::create(2025, 10, 11, 13, 15, 0),
            Carbon::create(2025, 10, 20, 10, 30, 0),
            Carbon::create(2025, 10, 28, 15, 45, 0),
            // November 2025
            Carbon::create(2025, 11, 5, 11, 30, 0),
            Carbon::create(2025, 11, 12, 14, 45, 0),
            Carbon::create(2025, 11, 19, 9, 15, 0),
            Carbon::create(2025, 11, 26, 16, 0, 0),
            // Desember 2025
            Carbon::create(2025, 12, 1, 10, 0, 0),
            Carbon::create(2025, 12, 5, 13, 30, 0),
            Carbon::create(2025, 12, 10, 11, 15, 0),
            Carbon::create(2025, 12, 15, 14, 30, 0),
        ];

        $dateIndex = 0;
        $totalDates = count($specificDates);

        foreach ($pesertaUsers->take(16) as $index => $user) {
            // Each user can have 1-2 transactions
            $numTransaksi = rand(1, 2);
            
            for ($i = 0; $i < $numTransaksi; $i++) {
                $kursus = $kursusList->random();
                
                // Ambil tanggal dari daftar secara berurutan (round-robin)
                $tanggalTransaksi = $specificDates[$dateIndex % $totalDates]->copy();
                $dateIndex++;

                // Random status weighted towards success
                $statusWeight = rand(1, 10);
                if ($statusWeight <= 6) {
                    $status = 'success';
                } elseif ($statusWeight <= 8) {
                    $status = 'pending';
                } elseif ($statusWeight <= 9) {
                    $status = 'failed';
                } else {
                    $status = 'expired';
                }

                $enrollment = null;
                
                // HANYA buat enrollment jika transaksi SUCCESS
                if ($status === 'success') {
                    // Check if enrollment already exists
                    $existingEnrollment = Enrollment::where('user_id', $user->id)
                        ->where('kursus_id', $kursus->id)
                        ->first();
                    
                    if (!$existingEnrollment) {
                        $enrollmentStatus = rand(1, 10) <= 3 ? 'completed' : 'active';
                        $progress = $enrollmentStatus === 'completed' ? 100 : rand(0, 95);
                        
                        // tanggal_daftar enrollment SAMA dengan tanggal_transaksi
                        $enrollment = Enrollment::create([
                            'kode' => 'ENR-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                            'user_id' => $user->id,
                            'kursus_id' => $kursus->id,
                            'tanggal_daftar' => $tanggalTransaksi->copy(),
                            'status' => $enrollmentStatus,
                            'progress' => $progress,
                            'nilai_akhir' => $enrollmentStatus === 'completed' ? rand(70, 100) : null,
                            'created_at' => $tanggalTransaksi->copy(),
                            'updated_at' => $tanggalTransaksi->copy(),
                        ]);
                        $enrollmentCount++;
                    } else {
                        $enrollment = $existingEnrollment;
                    }
                }

                $tanggalVerifikasi = $status === 'success' ? $tanggalTransaksi->copy()->addHours(rand(1, 24)) : null;

                // Set created_at sama dengan tanggal_transaksi untuk konsistensi
                Transaksi::create([
                    'kode_transaksi' => 'TRX-' . strtoupper(substr(md5(uniqid() . $index . $i), 0, 10)),
                    'enrollment_id' => $enrollment?->id,
                    'user_id' => $user->id,
                    'kursus_id' => $kursus->id,
                    'tanggal_transaksi' => $tanggalTransaksi,
                    'nominal_pembayaran' => $kursus->harga ?? rand(100000, 500000),
                    'jumlah' => $kursus->harga ?? rand(100000, 500000),
                    'status' => $status,
                    'metode_pembayaran' => $metodePembayaran[array_rand($metodePembayaran)],
                    'tanggal_verifikasi' => $tanggalVerifikasi,
                    'created_at' => $tanggalTransaksi->copy(),
                    'updated_at' => $tanggalTransaksi->copy(),
                ]);

                $transaksiCount++;
            }
        }

        // Add specific transaksi untuk memastikan distribusi merata di semua metode pembayaran
        $specificTransaksi = [
            // Desember 2025 - berbagai tanggal
            ['status' => 'success', 'metode' => 'bank_transfer', 'date' => Carbon::create(2025, 12, 2, 9, 15, 0)],
            ['status' => 'success', 'metode' => 'qris', 'date' => Carbon::create(2025, 12, 8, 14, 30, 0)],
            ['status' => 'pending', 'metode' => 'e_wallet', 'date' => Carbon::create(2025, 12, 12, 11, 45, 0)],
            ['status' => 'success', 'metode' => 'mini_market', 'date' => Carbon::create(2025, 12, 18, 10, 0, 0)],
            ['status' => 'success', 'metode' => 'kartu_debit', 'date' => Carbon::create(2025, 12, 22, 15, 30, 0)],
            // November 2025
            ['status' => 'success', 'metode' => 'credit_card', 'date' => Carbon::create(2025, 11, 8, 10, 20, 0)],
            ['status' => 'success', 'metode' => 'mini_market', 'date' => Carbon::create(2025, 11, 15, 16, 0, 0)],
            ['status' => 'success', 'metode' => 'qris', 'date' => Carbon::create(2025, 11, 22, 13, 30, 0)],
            // Oktober 2025
            ['status' => 'success', 'metode' => 'e_wallet', 'date' => Carbon::create(2025, 10, 7, 11, 15, 0)],
            ['status' => 'failed', 'metode' => 'credit_card', 'date' => Carbon::create(2025, 10, 15, 9, 45, 0)],
            ['status' => 'success', 'metode' => 'kartu_debit', 'date' => Carbon::create(2025, 10, 23, 15, 30, 0)],
            // September 2025
            ['status' => 'success', 'metode' => 'qris', 'date' => Carbon::create(2025, 9, 5, 10, 0, 0)],
            ['status' => 'success', 'metode' => 'bank_transfer', 'date' => Carbon::create(2025, 9, 15, 14, 15, 0)],
            // Agustus 2025
            ['status' => 'success', 'metode' => 'mini_market', 'date' => Carbon::create(2025, 8, 8, 11, 30, 0)],
            ['status' => 'success', 'metode' => 'e_wallet', 'date' => Carbon::create(2025, 8, 22, 16, 45, 0)],
            // Juli 2025
            ['status' => 'success', 'metode' => 'credit_card', 'date' => Carbon::create(2025, 7, 10, 9, 0, 0)],
            ['status' => 'expired', 'metode' => 'kartu_debit', 'date' => Carbon::create(2025, 7, 25, 14, 20, 0)],
        ];

        foreach ($specificTransaksi as $data) {
            $user = $pesertaUsers->random();
            $kursus = $kursusList->random();
            
            $enrollment = null;
            
            // HANYA buat enrollment jika transaksi SUCCESS
            if ($data['status'] === 'success') {
                $existingEnrollment = Enrollment::where('user_id', $user->id)
                    ->where('kursus_id', $kursus->id)
                    ->first();
                
                if (!$existingEnrollment) {
                    $enrollmentStatus = rand(1, 10) <= 3 ? 'completed' : 'active';
                    $progress = $enrollmentStatus === 'completed' ? 100 : rand(0, 95);
                    
                    // tanggal_daftar enrollment SAMA dengan tanggal_transaksi
                    $enrollment = Enrollment::create([
                        'kode' => 'ENR-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                        'user_id' => $user->id,
                        'kursus_id' => $kursus->id,
                        'tanggal_daftar' => $data['date']->copy(),
                        'status' => $enrollmentStatus,
                        'progress' => $progress,
                        'nilai_akhir' => $enrollmentStatus === 'completed' ? rand(70, 100) : null,
                        'created_at' => $data['date']->copy(),
                        'updated_at' => $data['date']->copy(),
                    ]);
                    $enrollmentCount++;
                } else {
                    $enrollment = $existingEnrollment;
                }
            }
            
            // Set created_at sama dengan tanggal_transaksi
            Transaksi::create([
                'kode_transaksi' => 'TRX-' . strtoupper(substr(md5(uniqid()), 0, 10)),
                'enrollment_id' => $enrollment?->id,
                'user_id' => $user->id,
                'kursus_id' => $kursus->id,
                'tanggal_transaksi' => $data['date'],
                'nominal_pembayaran' => $kursus->harga ?? rand(150000, 450000),
                'jumlah' => $kursus->harga ?? rand(150000, 450000),
                'status' => $data['status'],
                'metode_pembayaran' => $data['metode'],
                'tanggal_verifikasi' => $data['status'] === 'success' ? $data['date']->copy()->addHours(rand(1, 12)) : null,
                'created_at' => $data['date']->copy(),
                'updated_at' => $data['date']->copy(),
            ]);

            $transaksiCount++;
        }

        echo "✓ TransaksiSeeder berhasil!\n";
        echo "  - Total transaksi: {$transaksiCount} (tersebar di 6 bulan: Jul-Des 2025)\n";
        echo "  - Total enrollment baru: {$enrollmentCount} (hanya dari transaksi sukses)\n";
        echo "  - Metode pembayaran: bank_transfer, e_wallet, credit_card, qris, mini_market, kartu_debit\n";
    }
}
