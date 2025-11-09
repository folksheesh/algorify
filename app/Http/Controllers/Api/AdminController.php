<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Enrollment;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function getDashboardStats()
    {
        // Total stats
        $totalPeserta = User::count();
        $totalPengajar = User::whereHas('roles', function($q) {
            $q->where('name', 'pengajar');
        })->count();
        
        // If no roles, just count some users as pengajar
        if ($totalPengajar === 0) {
            $totalPengajar = User::whereIn('id', [2, 3, 4, 5])->count();
        }
        
        $totalKursus = Kursus::count();
        
        // Transaction stats
        $transactions = Transaksi::selectRaw('metode_pembayaran, COUNT(*) as count, SUM(jumlah) as total')
            ->groupBy('metode_pembayaran')
            ->get();
        
        $transactionStats = [
            'transfer_bank' => [
                'percentage' => 77,
                'count' => 0,
                'total' => 0
            ],
            'e_wallet' => [
                'percentage' => 50,
                'count' => 0,
                'total' => 0
            ],
            'kartu_kredit' => [
                'percentage' => 48,
                'count' => 0,
                'total' => 0
            ]
        ];
        
        foreach ($transactions as $trans) {
            if (isset($transactionStats[$trans->metode_pembayaran])) {
                $transactionStats[$trans->metode_pembayaran]['count'] = $trans->count;
                $transactionStats[$trans->metode_pembayaran]['total'] = $trans->total;
            }
        }
        
        // Growth data (dummy data for now - you can implement real data)
        $growthData = [
            'S' => ['students' => 60, 'revenue' => 40],
            'S' => ['students' => 100, 'revenue' => 70],
            'R' => ['students' => 70, 'revenue' => 80],
            'K' => ['students' => 130, 'revenue' => 90],
            'J' => ['students' => 90, 'revenue' => 80],
            'S' => ['students' => 120, 'revenue' => 100],
            'M' => ['students' => 100, 'revenue' => 70],
        ];
        
        return response()->json([
            'total_peserta' => $totalPeserta,
            'total_pengajar' => $totalPengajar,
            'total_kursus' => $totalKursus,
            'transaction_stats' => $transactionStats,
            'growth_data' => $growthData
        ]);
    }
    
    public function getRecentEnrollments()
    {
        $enrollments = Enrollment::with(['user', 'kursus'])
            ->latest()
            ->take(10)
            ->get();
            
        return response()->json($enrollments);
    }
}
