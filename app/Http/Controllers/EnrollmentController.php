<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kursus;
use App\Models\Enrollment;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    // Show payment / enrollment page
    public function show(Request $request, $id)
    {
        try {
            $kursus = Kursus::with('pengajar')->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('EnrollmentController@show: '.$e->getMessage());
            abort(404);
        }

        return view('pembayaran.create', compact('kursus'));
    }

    // Handle enrollment and create transaction if needed
    public function store(Request $request, $id)
    {
        $user = $request->user();

        try {
            $kursus = Kursus::findOrFail($id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Kursus tidak ditemukan');
        }

        // Prevent duplicate enrollment
        $existing = Enrollment::where('user_id', $user->id)->where('kursus_id', $kursus->id)->first();
        if ($existing) {
            return redirect()->route('pelatihan.index')->with('status', 'Anda sudah terdaftar pada pelatihan ini.');
        }

        DB::beginTransaction();
        try {
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'kursus_id' => $kursus->id,
                'tanggal_daftar' => now(),
                'status' => $kursus->harga > 0 ? 'pending' : 'active',
                'progress' => 0,
            ]);

            // If kursus is free, no transaction needed
            if ($kursus->harga <= 0) {
                DB::commit();
                return redirect()->route('pelatihan.index')->with('status', 'Pendaftaran berhasil. Selamat belajar!');
            }

            // Create a transaksi record (pending)
            $transaksi = Transaksi::create([
                'enrollment_id' => $enrollment->id,
                'user_id' => $user->id,
                'tanggal_transaksi' => now(),
                'nominal_pembayaran' => $kursus->harga,
                'status' => 'pending',
                'metode_pembayaran' => $request->input('metode_pembayaran', 'VA'),
            ]);

            DB::commit();

            return redirect()->route('pembayaran.status', $transaksi->id)->with('status', 'Silakan lanjutkan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EnrollmentController@store: '.$e->getMessage());
            return redirect()->back()->with('error', 'Gagal melakukan pendaftaran. Silakan coba lagi.');
        }
    }

    // Show payment status for a transaksi
    public function status(Request $request, $id)
    {
        try {
            $transaksi = Transaksi::with(['enrollment.kursus', 'user'])->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('EnrollmentController@status: '.$e->getMessage());
            abort(404);
        }

        return view('pembayaran.status', compact('transaksi'));
    }

    // Simple dev helper to simulate payment success (for testing only)
    public function simulateSuccess(Request $request, $id)
    {
        try {
            $transaksi = Transaksi::with('enrollment')->findOrFail($id);
            $transaksi->status = 'success';
            $transaksi->tanggal_verifikasi = now();
            $transaksi->save();

            // mark enrollment active
            if ($transaksi->enrollment) {
                $transaksi->enrollment->status = 'active';
                $transaksi->enrollment->save();
            }
        } catch (\Exception $e) {
            Log::error('EnrollmentController@simulateSuccess: '.$e->getMessage());
            return redirect()->back()->with('error', 'Gagal mensimulasikan pembayaran');
        }

        return redirect()->route('pembayaran.status', $transaksi->id)->with('status', 'Pembayaran disimulasikan berhasil');
    }
}
