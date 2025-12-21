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

            // Server-side date filter (range)
            if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('tanggal_transaksi', [
                    $request->tanggal_mulai . ' 00:00:00',
                    $request->tanggal_akhir . ' 23:59:59'
                ]);
            } elseif ($request->filled('tanggal_mulai')) {
                $query->where('tanggal_transaksi', '>=', $request->tanggal_mulai . ' 00:00:00');
            } elseif ($request->filled('tanggal_akhir')) {
                $query->where('tanggal_transaksi', '<=', $request->tanggal_akhir . ' 23:59:59');
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
     * Export transaksi to Excel with styling
     */
    public function exportCsv(Request $request)
    {
        try {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\TransaksiExport($request), 
                'transaksi_' . date('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('Error exporting transactions: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengexport data transaksi');
        }
    }
}
