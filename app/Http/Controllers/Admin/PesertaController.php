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
        $query = User::role('peserta')
            ->withCount('enrollments as kursus_count')
            ->with(['enrollments.kursus']);

        // Server-side search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Server-side status filter
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'Aktif') {
                $query->where(function($q) {
                    $q->where('status', 'active')
                      ->orWhereNull('status');
                });
            } elseif ($status === 'Nonaktif') {
                $query->where('status', 'inactive');
            }
        }

        $peserta = $query->orderBy('id', 'asc')->paginate(10);
        
        // Transform untuk menambahkan nama kursus yang diikuti
        $peserta->getCollection()->transform(function($item) {
            $kursusNames = $item->enrollments->map(function($e) {
                return $e->kursus->judul ?? '';
            })->filter()->implode(', ');
            $item->kursus_names = $kursusNames ?: 'Belum mengikuti kursus';
            return $item;
        });

        return response()->json($peserta);
    }

    public function show($id)
    {
        $user = User::role('peserta')
            ->with(['enrollments.kursus'])
            ->withCount('enrollments as kursus_count')
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $user = User::role('peserta')->findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Status peserta berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $user = User::role('peserta')->findOrFail($id);
        
        // Cek apakah status peserta adalah inactive
        if ($user->status !== 'inactive') {
            return response()->json([
                'success' => false,
                'message' => 'Peserta harus dinonaktifkan terlebih dahulu sebelum dapat dihapus'
            ], 400);
        }

        // Hapus enrollments peserta terlebih dahulu
        $user->enrollments()->delete();
        
        // Hapus peserta
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil dihapus dari sistem'
        ]);
    }
}
