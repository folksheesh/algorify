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
        $filter = $request->get('filter', 'current_month');
        
        $query = Transaksi::query();
        
        switch ($filter) {
            case 'current_month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                      ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;
            case 'all':
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
     * Get student growth data for chart
     */
    public function getPertumbuhanData(Request $request)
    {
        $filter = $request->get('filter', 'this_year');
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = array_fill(0, 12, 0);
        
        // Get role_id for 'peserta'
        $pesertaRole = \Spatie\Permission\Models\Role::where('name', 'peserta')->first();
        
        if (!$pesertaRole) {
            return response()->json([
                'labels' => $months,
                'values' => $data
            ]);
        }
        
        if ($filter === 'this_year') {
            // Get data for current year only
            $students = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->where('model_has_roles.role_id', $pesertaRole->id)
                ->where('model_has_roles.model_type', User::class)
                ->whereYear('users.created_at', Carbon::now()->year)
                ->select(DB::raw('MONTH(users.created_at) as month'), DB::raw('count(*) as total'))
                ->groupBy('month')
                ->get();
            
            foreach ($students as $student) {
                $data[$student->month - 1] = $student->total;
            }
        } else {
            // Get all data grouped by month
            $students = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->where('model_has_roles.role_id', $pesertaRole->id)
                ->where('model_has_roles.model_type', User::class)
                ->select(
                    DB::raw('YEAR(users.created_at) as year'),
                    DB::raw('MONTH(users.created_at) as month'),
                    DB::raw('count(*) as total')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
            
            // For all time data, accumulate all students per month across all years
            $monthlyTotals = array_fill(0, 12, 0);
            foreach ($students as $student) {
                $monthlyTotals[$student->month - 1] += $student->total;
            }
            $data = $monthlyTotals;
        }
        
        return response()->json([
            'labels' => $months,
            'values' => $data
        ]);
    }
}
