<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Transaksi::with(['user', 'kursus'])
                ->orderBy('tanggal_transaksi', 'desc');

            // Server-side search
            if ($request->filled('search')) {
                $search = $request->search;
                
                // Map nama bulan Indonesia & Inggris ke nomor bulan (partial match)
                $bulanList = [
                    1 => ['januari', 'jan', 'january'],
                    2 => ['februari', 'feb', 'february'],
                    3 => ['maret', 'mar', 'march'],
                    4 => ['april', 'apr'],
                    5 => ['mei', 'may'],
                    6 => ['juni', 'jun', 'june'],
                    7 => ['juli', 'jul', 'july'],
                    8 => ['agustus', 'agu', 'ags', 'august', 'aug'],
                    9 => ['september', 'sep', 'sept'],
                    10 => ['oktober', 'okt', 'october', 'oct'],
                    11 => ['november', 'nov'],
                    12 => ['desember', 'des', 'december', 'dec'],
                ];
                
                $searchLower = strtolower(trim($search));
                $searchMonth = null;
                
                // Cari partial match untuk nama bulan
                foreach ($bulanList as $monthNum => $names) {
                    foreach ($names as $name) {
                        // Cek jika search term ada di dalam nama bulan (partial match)
                        if (str_contains($name, $searchLower) || str_contains($searchLower, $name)) {
                            $searchMonth = $monthNum;
                            break 2;
                        }
                    }
                }
                
                $query->where(function($q) use ($search, $searchMonth) {
                    $q->where('kode_transaksi', 'like', "%{$search}%")
                      ->orWhereHas('user', function($uq) use ($search) {
                          $uq->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('kursus', function($kq) use ($search) {
                          $kq->where('judul', 'like', "%{$search}%");
                      });
                    
                    // Jika search term cocok dengan nama bulan, cari berdasarkan bulan
                    if ($searchMonth) {
                        $q->orWhereMonth('tanggal_transaksi', $searchMonth);
                    }
                });
            }

            // Server-side status filter
            if ($request->filled('status')) {
                $status = $request->status;
                if ($status === 'lunas') {
                    $query->where('status', 'success');
                } elseif ($status === 'pending') {
                    $query->where('status', 'pending');
                } elseif ($status === 'gagal') {
                    $query->whereIn('status', ['expired', 'failed']);
                }
            }

            // Server-side metode filter
            if ($request->filled('metode')) {
                $metodeMap = [
                    'transfer bank' => 'bank_transfer',
                    'e-wallet' => 'e_wallet',
                    'kartu kredit' => 'credit_card',
                    'qris' => 'qris',
                    'mini market' => 'mini_market',
                    'kartu debit' => 'kartu_debit',
                ];
                $metode = $metodeMap[strtolower($request->metode)] ?? $request->metode;
                $query->where('metode_pembayaran', $metode);
            }

            // Server-side periode filter
            if ($request->filled('periode')) {
                $now = now();
                switch ($request->periode) {
                    case 'hari_ini':
                        $query->whereDate('tanggal_transaksi', $now->toDateString());
                        break;
                    case '7_hari':
                        $query->where('tanggal_transaksi', '>=', $now->copy()->subDays(7));
                        break;
                    case 'bulan_ini':
                        $query->whereMonth('tanggal_transaksi', $now->month)
                              ->whereYear('tanggal_transaksi', $now->year);
                        break;
                    case 'bulan_lalu':
                        $lastMonth = $now->copy()->subMonth();
                        $query->whereMonth('tanggal_transaksi', $lastMonth->month)
                              ->whereYear('tanggal_transaksi', $lastMonth->year);
                        break;
                    case 'tahun_ini':
                        $query->whereYear('tanggal_transaksi', $now->year);
                        break;
                }
            }

            $transaksi = $query->paginate(10)->withQueryString();

            // Hitung statistik - Total pendapatan hanya dari status 'success' di database
            $totalPendapatan = Transaksi::where('status', 'success')->sum('jumlah');
            $totalTransaksi = Transaksi::count();
            $lunasCount = Transaksi::where('status', 'success')->count();
            $pendingCount = Transaksi::where('status', 'pending')->count();
            $failedCount = Transaksi::whereIn('status', ['expired', 'failed'])->count();
            $tingkatKeberhasilan = $totalTransaksi > 0 ? round(($lunasCount / $totalTransaksi) * 100, 1) : 0;

            return view('admin.transaksi.index', compact('transaksi', 'totalPendapatan', 'totalTransaksi', 'tingkatKeberhasilan', 'lunasCount', 'pendingCount', 'failedCount'));
        } catch (\Exception $e) {
            Log::error('Error fetching transactions: ' . $e->getMessage());
            
            $transaksi = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1);
            $totalPendapatan = 0;
            $totalTransaksi = 0;
            $lunasCount = 0;
            $pendingCount = 0;
            $failedCount = 0;
            $tingkatKeberhasilan = 0;
            
            return view('admin.transaksi.index', compact('transaksi', 'totalPendapatan', 'totalTransaksi', 'tingkatKeberhasilan', 'lunasCount', 'pendingCount', 'failedCount'));
        }
    }

    /**
     * Get transaksi data for JavaScript pagination
     */
    public function getData(Request $request)
    {
        try {
            $query = Transaksi::with(['user', 'kursus'])
                ->orderBy('tanggal_transaksi', 'desc');

            $transaksi = $query->paginate(10);

            // Transform data for frontend
            $data = $transaksi->getCollection()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_transaksi' => $item->kode_transaksi,
                    'tanggal_transaksi' => $item->tanggal_transaksi ? $item->tanggal_transaksi->format('d M Y, H:i') : '-',
                    'tanggal_raw' => $item->tanggal_transaksi ? $item->tanggal_transaksi->toISOString() : null,
                    'user_name' => $item->user->name ?? 'N/A',
                    'user_email' => $item->user->email ?? 'N/A',
                    'kursus_judul' => $item->kursus->judul ?? 'N/A',
                    'jumlah' => $item->jumlah,
                    'jumlah_formatted' => 'Rp ' . number_format($item->jumlah, 0, ',', '.'),
                    'metode_pembayaran' => $item->metode_pembayaran,
                    'status' => $item->status,
                ];
            });

            return response()->json([
                'data' => $data,
                'current_page' => $transaksi->currentPage(),
                'last_page' => $transaksi->lastPage(),
                'per_page' => $transaksi->perPage(),
                'total' => $transaksi->total(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching transactions data: ' . $e->getMessage());
            return response()->json([
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 10,
                'total' => 0,
            ]);
        }
    }

    /**
     * Export transaksi to CSV
     */
    public function exportCsv(Request $request)
    {
        try {
            $query = Transaksi::with(['user', 'kursus'])
                ->orderBy('tanggal_transaksi', 'desc');

            // Apply filters same as index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('kode_transaksi', 'like', "%{$search}%")
                      ->orWhereHas('user', function($uq) use ($search) {
                          $uq->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('kursus', function($kq) use ($search) {
                          $kq->where('judul', 'like', "%{$search}%");
                      });
                });
            }

            if ($request->filled('status')) {
                $status = $request->status;
                if ($status === 'lunas') {
                    $query->where('status', 'success');
                } elseif ($status === 'pending') {
                    $query->where('status', 'pending');
                } elseif ($status === 'gagal') {
                    $query->whereIn('status', ['expired', 'failed']);
                }
            }

            if ($request->filled('metode')) {
                $metodeMap = [
                    'transfer bank' => 'bank_transfer',
                    'e-wallet' => 'e_wallet',
                    'kartu kredit' => 'credit_card',
                    'qris' => 'qris',
                    'mini market' => 'mini_market',
                    'kartu debit' => 'kartu_debit',
                ];
                $metode = $metodeMap[strtolower($request->metode)] ?? $request->metode;
                $query->where('metode_pembayaran', $metode);
            }

            if ($request->filled('periode')) {
                $now = now();
                switch ($request->periode) {
                    case 'hari_ini':
                        $query->whereDate('tanggal_transaksi', $now->toDateString());
                        break;
                    case '7_hari':
                        $query->where('tanggal_transaksi', '>=', $now->copy()->subDays(7));
                        break;
                    case 'bulan_ini':
                        $query->whereMonth('tanggal_transaksi', $now->month)
                              ->whereYear('tanggal_transaksi', $now->year);
                        break;
                    case 'bulan_lalu':
                        $lastMonth = $now->copy()->subMonth();
                        $query->whereMonth('tanggal_transaksi', $lastMonth->month)
                              ->whereYear('tanggal_transaksi', $lastMonth->year);
                        break;
                    case 'tahun_ini':
                        $query->whereYear('tanggal_transaksi', $now->year);
                        break;
                }
            }

            $transaksi = $query->get();
            
            // Calculate totals for header
            $totalJumlah = $transaksi->sum('jumlah');
            $totalLunas = $transaksi->where('status', 'success')->count();
            $totalPending = $transaksi->where('status', 'pending')->count();

            $headers = [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="transaksi_' . date('Y-m-d_His') . '.csv"',
            ];

            $callback = function() use ($transaksi, $totalJumlah, $totalLunas, $totalPending) {
                $file = fopen('php://output', 'w');
                // Add BOM for UTF-8 Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Report Header
                fputcsv($file, ['LAPORAN DATA TRANSAKSI - ALGORIFY'], ';');
                fputcsv($file, ['Tanggal Export: ' . date('d/m/Y H:i:s')], ';');
                fputcsv($file, [''], ';');
                fputcsv($file, ['RINGKASAN'], ';');
                fputcsv($file, ['Total Transaksi', count($transaksi)], ';');
                fputcsv($file, ['Total Pendapatan', 'Rp ' . number_format($totalJumlah, 0, ',', '.')], ';');
                fputcsv($file, ['Transaksi Lunas', $totalLunas], ';');
                fputcsv($file, ['Transaksi Pending', $totalPending], ';');
                fputcsv($file, [''], ';');
                fputcsv($file, ['DETAIL TRANSAKSI'], ';');
                
                // Column headers
                fputcsv($file, [
                    'No',
                    'Kode Transaksi',
                    'Tanggal',
                    'Nama Peserta',
                    'Email',
                    'Kursus',
                    'Jumlah (Rp)',
                    'Metode Pembayaran',
                    'Status'
                ], ';');
                
                foreach ($transaksi as $index => $item) {
                    // Format metode pembayaran
                    $metodeMap = [
                        'bank_transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet',
                        'credit_card' => 'Kartu Kredit',
                        'qris' => 'QRIS',
                        'mini_market' => 'Mini Market',
                        'kartu_debit' => 'Kartu Debit',
                    ];
                    $metode = $metodeMap[$item->metode_pembayaran] ?? ucfirst(str_replace('_', ' ', $item->metode_pembayaran ?? '-'));
                    
                    // Format status
                    $statusMap = [
                        'success' => 'Lunas',
                        'pending' => 'Pending',
                        'expired' => 'Kadaluarsa',
                        'failed' => 'Gagal',
                    ];
                    $status = $statusMap[$item->status] ?? ucfirst($item->status ?? '-');
                    
                    fputcsv($file, [
                        $index + 1,
                        $item->kode_transaksi,
                        $item->tanggal_transaksi ? $item->tanggal_transaksi->format('d/m/Y H:i') : '-',
                        $item->user->name ?? 'N/A',
                        $item->user->email ?? 'N/A',
                        $item->kursus->judul ?? 'N/A',
                        number_format($item->jumlah, 0, ',', '.'),
                        $metode,
                        $status
                    ], ';');
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error exporting transactions: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengexport data transaksi');
        }
    }
}
