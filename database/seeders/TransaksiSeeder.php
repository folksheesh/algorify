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
     */
    public function run(): void
    {
        // Get peserta users
        $pesertaUsers = User::role('peserta')->get();
        
        // Get all kursus
        $kursusList = Kursus::all();
        
        if ($pesertaUsers->isEmpty() || $kursusList->isEmpty()) {
            echo "⚠ TransaksiSeeder membutuhkan data User (peserta) dan Kursus. Jalankan UserSeeder dan KursusSeeder terlebih dahulu.\n";
            return;
        }

        // Valid enum values: 'bank_transfer', 'e_wallet', 'credit_card', 'qris', 'virtual_account'
        $metodePembayaran = ['bank_transfer', 'e_wallet', 'credit_card', 'qris', 'virtual_account'];
        $statusTransaksi = ['pending', 'success', 'failed', 'expired'];
        
        $transaksiCount = 0;

        // Create transaksi tersebar di berbagai bulan (6 bulan terakhir)
        $months = [
            Carbon::now(),                    // Desember 2025
            Carbon::now()->subMonth(1),       // November 2025
            Carbon::now()->subMonths(2),      // Oktober 2025
            Carbon::now()->subMonths(3),      // September 2025
            Carbon::now()->subMonths(4),      // Agustus 2025
            Carbon::now()->subMonths(5),      // Juli 2025
        ];

        foreach ($pesertaUsers->take(18) as $index => $user) {
            // Each user can have 1-2 transactions
            $numTransaksi = rand(1, 2);
            
            for ($i = 0; $i < $numTransaksi; $i++) {
                $kursus = $kursusList->random();
                
                // Check if enrollment exists
                $enrollment = Enrollment::where('user_id', $user->id)
                    ->where('kursus_id', $kursus->id)
                    ->first();
                
                // Create enrollment if not exists
                if (!$enrollment) {
                    $enrollment = Enrollment::create([
                        'kode' => 'ENR-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                        'user_id' => $user->id,
                        'kursus_id' => $kursus->id,
                        'tanggal_daftar' => Carbon::now()->subDays(rand(1, 180)),
                        'status' => 'active',
                        'progress' => rand(0, 100),
                    ]);
                }

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

                // Pilih bulan random dari daftar
                $baseMonth = $months[array_rand($months)];
                $tanggalTransaksi = $baseMonth->copy()->day(rand(1, 28))->setTime(rand(8, 22), rand(0, 59), rand(0, 59));
                $tanggalVerifikasi = $status === 'success' ? $tanggalTransaksi->copy()->addHours(rand(1, 24)) : null;

                Transaksi::create([
                    'kode_transaksi' => 'TRX-' . strtoupper(substr(md5(uniqid() . $index . $i), 0, 10)),
                    'enrollment_id' => $enrollment->id,
                    'user_id' => $user->id,
                    'kursus_id' => $kursus->id,
                    'tanggal_transaksi' => $tanggalTransaksi,
                    'nominal_pembayaran' => $kursus->harga ?? rand(100000, 500000),
                    'jumlah' => $kursus->harga ?? rand(100000, 500000),
                    'status' => $status,
                    'metode_pembayaran' => $metodePembayaran[array_rand($metodePembayaran)],
                    'tanggal_verifikasi' => $tanggalVerifikasi,
                ]);

                $transaksiCount++;
            }
        }

        // Add specific transaksi for each month to ensure distribution
        $specificTransaksi = [
            // Desember 2025
            ['status' => 'success', 'metode' => 'bank_transfer', 'month' => Carbon::now()->day(5)],
            ['status' => 'success', 'metode' => 'qris', 'month' => Carbon::now()->day(10)],
            ['status' => 'pending', 'metode' => 'e_wallet', 'month' => Carbon::now()->day(1)],
            // November 2025
            ['status' => 'success', 'metode' => 'credit_card', 'month' => Carbon::now()->subMonth(1)->day(15)],
            ['status' => 'success', 'metode' => 'virtual_account', 'month' => Carbon::now()->subMonth(1)->day(20)],
            // Oktober 2025
            ['status' => 'success', 'metode' => 'e_wallet', 'month' => Carbon::now()->subMonths(2)->day(8)],
            ['status' => 'failed', 'metode' => 'credit_card', 'month' => Carbon::now()->subMonths(2)->day(22)],
            // September 2025
            ['status' => 'success', 'metode' => 'qris', 'month' => Carbon::now()->subMonths(3)->day(12)],
            ['status' => 'success', 'metode' => 'bank_transfer', 'month' => Carbon::now()->subMonths(3)->day(25)],
            // Agustus 2025
            ['status' => 'success', 'metode' => 'virtual_account', 'month' => Carbon::now()->subMonths(4)->day(5)],
            // Juli 2025
            ['status' => 'success', 'metode' => 'e_wallet', 'month' => Carbon::now()->subMonths(5)->day(18)],
            ['status' => 'expired', 'metode' => 'bank_transfer', 'month' => Carbon::now()->subMonths(5)->day(28)],
        ];

        foreach ($specificTransaksi as $data) {
            $user = $pesertaUsers->random();
            $kursus = $kursusList->random();
            
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('kursus_id', $kursus->id)
                ->first();
            
            if (!$enrollment) {
                $enrollment = Enrollment::create([
                    'kode' => 'ENR-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                    'user_id' => $user->id,
                    'kursus_id' => $kursus->id,
                    'tanggal_daftar' => $data['month']->copy()->subDays(rand(1, 7)),
                    'status' => 'active',
                    'progress' => rand(0, 100),
                ]);
            }

            $tanggalTransaksi = $data['month']->copy()->setTime(rand(9, 20), rand(0, 59), rand(0, 59));
            
            Transaksi::create([
                'kode_transaksi' => 'TRX-' . strtoupper(substr(md5(uniqid()), 0, 10)),
                'enrollment_id' => $enrollment->id,
                'user_id' => $user->id,
                'kursus_id' => $kursus->id,
                'tanggal_transaksi' => $tanggalTransaksi,
                'nominal_pembayaran' => $kursus->harga ?? rand(150000, 450000),
                'jumlah' => $kursus->harga ?? rand(150000, 450000),
                'status' => $data['status'],
                'metode_pembayaran' => $data['metode'],
                'tanggal_verifikasi' => $data['status'] === 'success' ? $tanggalTransaksi->copy()->addHours(rand(1, 12)) : null,
            ]);

            $transaksiCount++;
        }

        echo "✓ TransaksiSeeder berhasil! Total: {$transaksiCount} transaksi (tersebar di 6 bulan terakhir)\n";
    }
}
