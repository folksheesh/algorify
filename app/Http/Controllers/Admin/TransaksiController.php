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
}
