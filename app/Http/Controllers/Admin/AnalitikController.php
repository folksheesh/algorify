<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;

class AnalitikController extends Controller
{
    public function index()
    {
        try {
            // Statistics
            $totalPendapatan = Transaksi::where('status', 'lunas')->sum('jumlah');
            $totalTransaksi = Transaksi::count();
            $lunasCount = Transaksi::where('status', 'lunas')->count();
            $pendingCount = Transaksi::where('status', 'pending')->count();
            $failedCount = Transaksi::where('status', 'gagal')->count();
            $tingkatKeberhasilan = $totalTransaksi > 0 ? round(($lunasCount / $totalTransaksi) * 100, 1) : 0;

            // Top Kursus berdasarkan pendapatan
            $topKursus = \App\Models\Kursus::withCount('enrollments')
                ->with(['enrollments' => function($query) {
                    $query->whereHas('transaksi', function($q) {
                        $q->where('status', 'lunas');
                    });
                }])
                ->get()
                ->map(function($kursus, $index) {
                    $pendapatan = $kursus->enrollments->sum(function($enrollment) {
                        return $enrollment->transaksi->where('status', 'lunas')->sum('jumlah');
                    });
                    
                    $fillRate = $kursus->kapasitas > 0 ? round(($kursus->enrollments_count / $kursus->kapasitas) * 100, 0) : 0;
                    
                    return (object)[
                        'no' => $index + 1,
                        'nama' => $kursus->nama,
                        'peserta' => $kursus->enrollments_count,
                        'kapasitas' => $kursus->kapasitas ?? 0,
                        'fill_rate' => $fillRate,
                        'pendapatan' => $pendapatan
                    ];
                })
                ->sortByDesc('pendapatan')
                ->take(5)
                ->values();

            // Distribusi Profesi (dari data user yang punya enrollment)
            $distribusiProfesi = \App\Models\User::role('peserta')
                ->whereHas('enrollments')
                ->whereNotNull('profesi')
                ->selectRaw('profesi, COUNT(*) as jumlah')
                ->groupBy('profesi')
                ->get()
                ->map(function($item) use ($lunasCount) {
                    return (object)[
                        'profesi' => $item->profesi,
                        'jumlah' => $item->jumlah,
                        'percentage' => $lunasCount > 0 ? round(($item->jumlah / $lunasCount) * 100, 1) : 0
                    ];
                });

            // Distribusi Lokasi (dari kolom address user)
            $distribusiLokasi = \App\Models\User::role('peserta')
                ->whereHas('enrollments')
                ->whereNotNull('address')
                ->selectRaw('address as lokasi, COUNT(*) as jumlah')
                ->groupBy('address')
                ->get()
                ->map(function($item) use ($lunasCount) {
                    return (object)[
                        'lokasi' => $item->lokasi,
                        'jumlah' => $item->jumlah,
                        'percentage' => $lunasCount > 0 ? round(($item->jumlah / $lunasCount) * 100, 1) : 0
                    ];
                })
                ->sortByDesc('jumlah')
                ->take(6);

            // Data Nilai Peserta
            $students = \App\Models\Enrollment::with(['user', 'kursus'])
                ->get()
                ->map(function($enrollment) {
                    return (object)[
                        'id' => 'PST' . str_pad($enrollment->user->id, 3, '0', STR_PAD_LEFT),
                        'nama' => $enrollment->user->name,
                        'email' => $enrollment->user->email,
                        'pelatihan' => $enrollment->kursus->nama,
                        'tanggal_mulai' => $enrollment->created_at,
                        'status' => $enrollment->status == 'selesai' ? 'Selesai' : 'Berlangsung',
                        'progress' => $enrollment->progress ?? 0,
                        'nilai' => $enrollment->nilai_akhir
                    ];
                })
                ->take(20);

            // Grafik Pendapatan per Bulan (tahun berjalan)
            $revenueByMonth = [];
            for ($i = 1; $i <= 12; $i++) {
                $revenue = Transaksi::where('status', 'lunas')
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', $i)
                    ->sum('jumlah');
                $revenueByMonth[] = round($revenue / 1000000, 1); // Konversi ke juta
            }

            return view('admin.analitik.index', compact(
                'totalPendapatan',
                'totalTransaksi',
                'tingkatKeberhasilan',
                'pendingCount',
                'failedCount',
                'topKursus',
                'distribusiProfesi',
                'distribusiLokasi',
                'students',
                'revenueByMonth'
            ));
        } catch (\Exception $e) {
            Log::error('Error fetching analytics: ' . $e->getMessage());
            
            // Dummy data
            $totalPendapatan = 31530000;
            $totalTransaksi = 15;
            $tingkatKeberhasilan = 60.0;
            
            $topKursus = [
                (object)['no' => 1, 'nama' => 'Digital Marketing Masterclass', 'peserta' => 28, 'kapasitas' => 30, 'fill_rate' => 93, 'pendapatan' => 140000000],
                (object)['no' => 2, 'nama' => 'Agile Project Management', 'peserta' => 25, 'kapasitas' => 25, 'fill_rate' => 100, 'pendapatan' => 200000000],
                (object)['no' => 3, 'nama' => 'Leadership Excellence Program', 'peserta' => 20, 'kapasitas' => 25, 'fill_rate' => 80, 'pendapatan' => 150000000],
                (object)['no' => 4, 'nama' => 'Full-Stack Web Development', 'peserta' => 18, 'kapasitas' => 20, 'fill_rate' => 90, 'pendapatan' => 216000000],
                (object)['no' => 5, 'nama' => 'Desain UI/UX', 'peserta' => 15, 'kapasitas' => 20, 'fill_rate' => 75, 'pendapatan' => 90000000],
            ];
            
            $distribusiProfesi = [
                (object)['profesi' => 'Manager IT', 'jumlah' => 1, 'percentage' => 33.3],
                (object)['profesi' => 'Product Manager', 'jumlah' => 1, 'percentage' => 33.3],
                (object)['profesi' => 'Developer', 'jumlah' => 1, 'percentage' => 33.3],
            ];
            
            $distribusiLokasi = [
                (object)['lokasi' => 'Jakarta', 'jumlah' => 1, 'percentage' => 35],
                (object)['lokasi' => 'Bandung', 'jumlah' => 0, 'percentage' => 20],
                (object)['lokasi' => 'Surabaya', 'jumlah' => 0, 'percentage' => 15],
                (object)['lokasi' => 'Yogyakarta', 'jumlah' => 0, 'percentage' => 12],
                (object)['lokasi' => 'Medan', 'jumlah' => 0, 'percentage' => 10],
                (object)['lokasi' => 'Lainnya', 'jumlah' => 0, 'percentage' => 8],
            ];
            
            $students = [
                (object)['id' => 'PST001', 'nama' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@email.com', 'pelatihan' => 'Web Development Fundamentals', 'tanggal_mulai' => '2025-01-15', 'status' => 'Selesai', 'progress' => 100, 'nilai' => 92],
                (object)['id' => 'PST002', 'nama' => 'Siti Nurhaliza', 'email' => 'siti.nur@email.com', 'pelatihan' => 'Data Analytics with Python', 'tanggal_mulai' => '2025-02-01', 'status' => 'Berlangsung', 'progress' => 65, 'nilai' => null],
                (object)['id' => 'PST003', 'nama' => 'Budi Santoso', 'email' => 'budi.santoso@email.com', 'pelatihan' => 'Cyber Security Basics', 'tanggal_mulai' => '2025-01-20', 'status' => 'Selesai', 'progress' => 100, 'nilai' => 88],
                (object)['id' => 'PST004', 'nama' => 'Dewi Lestari', 'email' => 'dewi.lestari@email.com', 'pelatihan' => 'Mobile App Development', 'tanggal_mulai' => '2025-02-10', 'status' => 'Berlangsung', 'progress' => 45, 'nilai' => null],
                (object)['id' => 'PST005', 'nama' => 'Eko Prasetyo', 'email' => 'eko.prasetyo@email.com', 'pelatihan' => 'Cloud Computing AWS', 'tanggal_mulai' => '2025-01-10', 'status' => 'Selesai', 'progress' => 100, 'nilai' => 95],
                (object)['id' => 'PST006', 'nama' => 'Fitri Handayani', 'email' => 'fitri.h@email.com', 'pelatihan' => 'UI/UX Design', 'tanggal_mulai' => '2025-03-01', 'status' => 'Berlangsung', 'progress' => 30, 'nilai' => null],
                (object)['id' => 'PST007', 'nama' => 'Gunawan Wijaya', 'email' => 'gunawan.w@email.com', 'pelatihan' => 'Web Development Fundamentals', 'tanggal_mulai' => '2025-01-15', 'status' => 'Selesai', 'progress' => 100, 'nilai' => 78],
                (object)['id' => 'PST008', 'nama' => 'Hana Permata', 'email' => 'hana.permata@email.com', 'pelatihan' => 'Data Analytics with Python', 'tanggal_mulai' => '2025-02-01', 'status' => 'Berlangsung', 'progress' => 70, 'nilai' => null],
            ];

            $revenueByMonth = [28, 24, 20, 18, 16, 14, 12, 10, 8, 6, 4, 2];
            $pendingCount = 3;
            $failedCount = 0;

            return view('admin.analitik.index', compact(
                'totalPendapatan',
                'totalTransaksi',
                'tingkatKeberhasilan',
                'topKursus',
                'distribusiProfesi',
                'distribusiLokasi',
                'students',
                'revenueByMonth',
                'pendingCount',
                'failedCount'
            ));
        }
    }
}
