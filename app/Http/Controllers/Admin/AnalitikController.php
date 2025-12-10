<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AnalitikController extends Controller
{
    public function index(Request $request)
    {
        try {
            $sortBy = $request->get('sort', 'pendapatan'); // pendapatan, peserta, fill_rate
            $search = $request->get('search', '');
            $statusFilter = $request->get('status', '');
            $year = $request->get('year', date('Y')); // Default to current year
            
            // Statistics - ambil dari transaksi dengan status success
            $totalPendapatan = Transaksi::where('status', 'success')->sum('jumlah');
            $totalTransaksi = Transaksi::count();
            $lunasCount = Transaksi::where('status', 'success')->count();
            $pendingCount = Transaksi::where('status', 'pending')->count();
            $gagalCount = Transaksi::where('status', 'expired')->count();
            $tingkatKeberhasilan = $totalTransaksi > 0 ? round(($lunasCount / $totalTransaksi) * 100, 1) : 0;

            // Top Kursus berdasarkan sort parameter
            $kursusSort = $request->get('kursus_sort', 'pendapatan_desc'); // default: pendapatan tertinggi
            $kursusPage = $request->get('kursus_page', 1);
            
            $topKursusData = \App\Models\Kursus::withCount('enrollments')
                ->get()
                ->map(function($kursus, $index) {
                    // Hitung pendapatan dari transaksi yang success untuk kursus ini
                    $pendapatan = \App\Models\Transaksi::whereHas('enrollment', function($query) use ($kursus) {
                        $query->where('kursus_id', $kursus->id);
                    })->where('status', 'success')->sum('jumlah');
                    
                    return (object)[
                        'nama' => $kursus->judul, // Field nama kursus adalah 'judul'
                        'peserta' => $kursus->enrollments_count,
                        'pendapatan' => $pendapatan
                    ];
                });
            
            // Sort based on parameter (support asc/desc)
            switch($kursusSort) {
                case 'peserta_desc':
                    $topKursusData = $topKursusData->sortByDesc('peserta');
                    break;
                case 'peserta_asc':
                    $topKursusData = $topKursusData->sortBy('peserta');
                    break;
                case 'pendapatan_asc':
                    $topKursusData = $topKursusData->sortBy('pendapatan');
                    break;
                default: // pendapatan_desc
                    $topKursusData = $topKursusData->sortByDesc('pendapatan');
            }
            
            // Add numbering dan pagination manual
            $topKursusData = $topKursusData->values()->map(function($item, $index) {
                $item->no = $index + 1;
                return $item;
            });
            
            $kursusPerPage = 5;
            $kursusTotal = $topKursusData->count();
            $topKursus = new \Illuminate\Pagination\LengthAwarePaginator(
                $topKursusData->forPage($kursusPage, $kursusPerPage),
                $kursusTotal,
                $kursusPerPage,
                $kursusPage,
                ['path' => $request->url(), 'query' => $request->except('kursus_page'), 'pageName' => 'kursus_page']
            );

            // Distribusi Profesi - dari tabel users dengan role peserta (Top 5 + Others)
            $distribusiProfesiRaw = \App\Models\User::role('peserta')
                ->whereNotNull('profesi')
                ->where('profesi', '!=', '')
                ->selectRaw('profesi, COUNT(*) as jumlah')
                ->groupBy('profesi')
                ->orderByRaw('COUNT(*) DESC')
                ->get();
            
            $totalProfesi = $distribusiProfesiRaw->sum('jumlah');
            
            // Data lengkap untuk popup (tanpa "Lainnya")
            $distribusiProfesiFull = $distribusiProfesiRaw->map(function($item) use ($totalProfesi) {
                return (object)[
                    'profesi' => $item->profesi,
                    'jumlah' => $item->jumlah,
                    'percentage' => $totalProfesi > 0 ? round(($item->jumlah / $totalProfesi) * 100, 1) : 0
                ];
            });
            
            // Take top 5 and group the rest as "Others" for chart
            $top5Profesi = $distribusiProfesiRaw->take(5);
            $othersCount = $distribusiProfesiRaw->skip(5)->sum('jumlah');
            
            $distribusiProfesi = $top5Profesi->map(function($item) use ($totalProfesi) {
                return (object)[
                    'profesi' => $item->profesi,
                    'jumlah' => $item->jumlah,
                    'percentage' => $totalProfesi > 0 ? round(($item->jumlah / $totalProfesi) * 100, 1) : 0
                ];
            });
            
            // Add "Others" if there are more than 5 categories
            if ($othersCount > 0) {
                $distribusiProfesi->push((object)[
                    'profesi' => 'Lainnya',
                    'jumlah' => $othersCount,
                    'percentage' => $totalProfesi > 0 ? round(($othersCount / $totalProfesi) * 100, 1) : 0
                ]);
            }

            // Distribusi Lokasi - dari address di tabel users (Top 5 + Others)
            $distribusiLokasiRaw = \App\Models\User::role('peserta')
                ->whereNotNull('address')
                ->where('address', '!=', '')
                ->selectRaw('address as lokasi, COUNT(*) as jumlah')
                ->groupBy('address')
                ->orderByRaw('COUNT(*) DESC')
                ->get();
                
            $totalLokasi = $distribusiLokasiRaw->sum('jumlah');
            
            // Data lengkap untuk popup (tanpa "Lainnya")
            $distribusiLokasiFull = $distribusiLokasiRaw->map(function($item) use ($totalLokasi) {
                return (object)[
                    'lokasi' => $item->lokasi,
                    'jumlah' => $item->jumlah,
                    'percentage' => $totalLokasi > 0 ? round(($item->jumlah / $totalLokasi) * 100, 1) : 0
                ];
            });
            
            // Take top 5 and group the rest as "Others" for chart
            $top5Lokasi = $distribusiLokasiRaw->take(5);
            $othersCountLokasi = $distribusiLokasiRaw->skip(5)->sum('jumlah');
            
            $distribusiLokasi = $top5Lokasi->map(function($item) use ($totalLokasi) {
                return (object)[
                    'lokasi' => $item->lokasi,
                    'jumlah' => $item->jumlah,
                    'percentage' => $totalLokasi > 0 ? round(($item->jumlah / $totalLokasi) * 100, 1) : 0
                ];
            });
            
            // Add "Others" if there are more than 5 categories
            if ($othersCountLokasi > 0) {
                $distribusiLokasi->push((object)[
                    'lokasi' => 'Lainnya',
                    'jumlah' => $othersCountLokasi,
                    'percentage' => $totalLokasi > 0 ? round(($othersCountLokasi / $totalLokasi) * 100, 1) : 0
                ]);
            }
            
            $distribusiLokasi = $distribusiLokasi->values();

            // Distribusi Umur - dari tanggal_lahir tabel users role peserta
            $distribusiUmurRaw = \App\Models\User::role('peserta')
                ->whereNotNull('tanggal_lahir')
                ->get()
                ->map(function($user) {
                    $umur = \Carbon\Carbon::parse($user->tanggal_lahir)->age;
                    if ($umur >= 17 && $umur <= 25) return '17-25 tahun';
                    if ($umur >= 26 && $umur <= 35) return '26-35 tahun';
                    if ($umur >= 36 && $umur <= 45) return '36-45 tahun';
                    if ($umur >= 46) return '46+ tahun';
                    return null;
                })
                ->filter()
                ->groupBy(function($item) { return $item; })
                ->map(function($group) { return count($group); });
            
            $totalUmur = $distribusiUmurRaw->sum();
            $distribusiUmur = $distribusiUmurRaw->map(function($jumlah, $kelompok) use ($totalUmur) {
                return (object)[
                    'kelompok' => $kelompok,
                    'jumlah' => $jumlah,
                    'percentage' => $totalUmur > 0 ? round(($jumlah / $totalUmur) * 100, 1) : 0
                ];
            })->values();

            // Data Nilai Peserta dengan pagination dan search
            $studentsQuery = \App\Models\Enrollment::with(['user', 'kursus']);
            
            // Apply search filter - case insensitive, partial match
            if ($search) {
                $searchLower = strtolower(trim($search));
                
                // Map nama bulan Indonesia ke nomor bulan
                $bulanMap = [
                    'januari' => 1, 'jan' => 1,
                    'februari' => 2, 'feb' => 2,
                    'maret' => 3, 'mar' => 3,
                    'april' => 4, 'apr' => 4,
                    'mei' => 5,
                    'juni' => 6, 'jun' => 6,
                    'juli' => 7, 'jul' => 7,
                    'agustus' => 8, 'agu' => 8, 'aug' => 8,
                    'september' => 9, 'sep' => 9,
                    'oktober' => 10, 'okt' => 10, 'oct' => 10,
                    'november' => 11, 'nov' => 11,
                    'desember' => 12, 'des' => 12, 'dec' => 12
                ];
                
                // Cek apakah search term adalah nama bulan (exact match)
                $searchMonth = null;
                if (isset($bulanMap[$searchLower])) {
                    $searchMonth = $bulanMap[$searchLower];
                }
                
                // Jika search term adalah nama bulan, filter berdasarkan bulan saja
                if ($searchMonth) {
                    $studentsQuery->where(function($query) use ($searchMonth) {
                        // Filter by month dari tanggal_daftar atau created_at
                        $query->where(function($q) use ($searchMonth) {
                            $q->whereNotNull('tanggal_daftar')
                              ->whereMonth('tanggal_daftar', $searchMonth);
                        })->orWhere(function($q) use ($searchMonth) {
                            $q->whereNull('tanggal_daftar')
                              ->whereMonth('created_at', $searchMonth);
                        });
                    });
                } else {
                    // Jika bukan nama bulan, search berdasarkan text biasa
                    $studentsQuery->where(function($query) use ($searchLower) {
                        $query->whereHas('user', function($q) use ($searchLower) {
                            $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchLower . '%'])
                              ->orWhereRaw('LOWER(email) LIKE ?', ['%' . $searchLower . '%'])
                              ->orWhereRaw('LOWER(CAST(id AS CHAR)) LIKE ?', ['%' . $searchLower . '%']);
                        })
                        ->orWhereHas('kursus', function($q) use ($searchLower) {
                            $q->whereRaw('LOWER(judul) LIKE ?', ['%' . $searchLower . '%']);
                        })
                        ->orWhereRaw('LOWER(CAST(tanggal_daftar AS CHAR)) LIKE ?', ['%' . $searchLower . '%'])
                        ->orWhereRaw('LOWER(CAST(created_at AS CHAR)) LIKE ?', ['%' . $searchLower . '%']);
                    });
                }
            }
            
            // Apply status filter - handle both Indonesian and English status
            if ($statusFilter) {
                // Map Indonesian to English status if needed
                $statusMap = [
                    'selesai' => ['selesai', 'completed'],
                    'berlangsung' => ['berlangsung', 'active'],
                    'dropped' => ['dropped'],
                    'expired' => ['expired']
                ];
                
                if (isset($statusMap[$statusFilter])) {
                    $studentsQuery->whereIn('status', $statusMap[$statusFilter]);
                } else {
                    $studentsQuery->where('status', $statusFilter);
                }
            }
            
            // Get data dan sort manual
            $students = $studentsQuery->get()
                ->sortBy(function($item) {
                    // Sort by nama (primary), then by tanggal (secondary)
                    $name = $item->user->name ?? 'zzz';
                    $date = $item->tanggal_daftar ?? $item->created_at;
                    $dateStr = $date ? $date->format('Y-m-d H:i:s') : '9999-12-31';
                    return $name . '_' . $dateStr;
                })
                ->values();
            
            // Manual pagination
            $page = $request->get('page', 1);
            $perPage = 10;
            $total = $students->count();
            $students = new \Illuminate\Pagination\LengthAwarePaginator(
                $students->forPage($page, $perPage),
                $total,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            // Grafik Pendapatan per Bulan (tahun dipilih) - PostgreSQL compatible, status success
            $revenueByMonth = [];
            for ($i = 1; $i <= 12; $i++) {
                $revenue = Transaksi::where('status', 'success')
                    ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [$year])
                    ->whereRaw('EXTRACT(MONTH FROM created_at) = ?', [$i])
                    ->sum('jumlah');
                $revenueByMonth[] = round($revenue / 1000000, 1); // Konversi ke juta
            }
            
            // Generate available years (from 2024 to current year)
            $availableYears = range(2024, date('Y'));
            rsort($availableYears); // Sort descending (newest first)

            return view('admin.analitik.index', compact(
                'totalPendapatan',
                'totalTransaksi',
                'tingkatKeberhasilan',
                'lunasCount',
                'pendingCount',
                'gagalCount',
                'topKursus',
                'kursusSort',
                'distribusiProfesi',
                'distribusiProfesiFull',
                'distribusiLokasi',
                'distribusiLokasiFull',
                'distribusiUmur',
                'students',
                'revenueByMonth',
                'sortBy',
                'search',
                'statusFilter',
                'year',
                'availableYears'
            ));
        } catch (\Exception $e) {
            Log::error('Error fetching analytics: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Abort dengan error page, jangan redirect ke route yang sama
            abort(500, 'Terjadi kesalahan saat memuat data analitik: ' . $e->getMessage());
        }
    }
}
