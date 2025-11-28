<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get transaction data for chart
     */
    public function getTransaksiData(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $query = Transaksi::query();
        
        switch ($filter) {
            case 'current_month':
                $query->whereDate('created_at', Carbon::today());
                break;
            case '7_hari':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case 'bulan_ini':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'bulan_lalu':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                      ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;
            case 'tahun_ini':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'all':
            default:
                // No filter
                break;
        }
        
        // Group by payment method
        $data = $query->select('metode_pembayaran', DB::raw('count(*) as total'))
                     ->groupBy('metode_pembayaran')
                     ->get();
        
        return response()->json([
            'labels' => $data->pluck('metode_pembayaran')->toArray(),
            'values' => $data->pluck('total')->toArray()
        ]);
    }
    
    /**
     * Get student growth data for chart (based on enrollment date, not registration)
     */
    public function getPertumbuhanData(Request $request)
    {
        try {
            $year = $request->get('year', 'all');
            
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $data = array_fill(0, 12, 0);
            
            // Check if Enrollment model exists
            if (!class_exists('App\Models\Enrollment')) {
                \Log::warning('Enrollment model not found, using empty data');
                return response()->json([
                    'labels' => $months,
                    'values' => $data
                ]);
            }
            
            // Get enrollments data instead of user registrations
            $query = \App\Models\Enrollment::query();
            
            // Filter by year (using tanggal_daftar, not created_at)
            if ($year !== 'all') {
                $query->whereYear('tanggal_daftar', $year);
            }
            
            // Group by month (based on enrollment date) - PostgreSQL compatible
            $enrollments = $query->select(DB::raw('EXTRACT(MONTH FROM tanggal_daftar) as month'), DB::raw('count(*) as total'))
                ->groupBy(DB::raw('EXTRACT(MONTH FROM tanggal_daftar)'))
                ->get();
            
            foreach ($enrollments as $enrollment) {
                if ($year === 'all') {
                    // For all years, accumulate
                    $data[$enrollment->month - 1] += $enrollment->total;
                } else {
                    // For specific year, just assign
                    $data[$enrollment->month - 1] = $enrollment->total;
                }
            }
            
            return response()->json([
                'labels' => $months,
                'values' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting pertumbuhan data: ' . $e->getMessage());
            
            // Return empty data on error
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            return response()->json([
                'labels' => $months,
                'values' => array_fill(0, 12, 0)
            ]);
        }
    }
}
