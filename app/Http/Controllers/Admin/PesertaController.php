<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.peserta.index');
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $query = User::role('peserta')
            ->withCount('enrollments as kursus_count')
            ->with(['enrollments.kursus', 'enrollments.transaksi']);

        // Sort by oldest first (created_at ASC)
        $peserta = $query->orderBy('created_at', 'asc')->paginate($perPage);

        // Map each peserta to include kursus names and status transaksi
        $peserta->getCollection()->transform(function ($user) {
            $kursusNames = $user->enrollments->pluck('kursus.judul')->filter()->toArray();
            $user->kursus_names = implode(', ', $kursusNames);
            
            // Determine transaction status
            $statusTransaksi = 'Belum Lunas';
            foreach ($user->enrollments as $enrollment) {
                if ($enrollment->transaksi && $enrollment->transaksi->count() > 0) {
                    foreach ($enrollment->transaksi as $transaksi) {
                        if ($transaksi->status === 'success') {
                            $statusTransaksi = 'Lunas';
                            break 2;
                        } elseif ($transaksi->status === 'pending') {
                            $statusTransaksi = 'Pending';
                        }
                    }
                }
            }
            $user->status_transaksi = $statusTransaksi;
            
            return $user;
        });

        return response()->json($peserta);
    }

    public function show($id)
    {
        $user = User::role('peserta')
            ->with(['enrollments.kursus', 'enrollments.transaksi'])
            ->withCount('enrollments as kursus_count')
            ->findOrFail($id);
        
        // Get course names
        $kursusNames = $user->enrollments->pluck('kursus.judul')->filter()->toArray();
        $user->kursus_names = implode(', ', $kursusNames);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
