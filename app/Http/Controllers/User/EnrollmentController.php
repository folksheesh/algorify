<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kursus;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    /**
     * Show the payment page for a course
     */
    public function showPayment($id)
    {
        $kursus = Kursus::with('pengajar')->findOrFail($id);
        
        // Check if user already enrolled
        $existingEnrollment = Enrollment::where('user_id', auth()->id())
            ->where('kursus_id', $id)
            ->first();
        
        if ($existingEnrollment) {
            return redirect()->route('user.pelatihan.index')
                ->with('info', 'Anda sudah terdaftar di pelatihan ini.');
        }
        
        return view('user.pembayaran', compact('kursus'));
    }

    /**
     * Process enrollment
     */
    public function enroll(Request $request, $id)
    {
        $kursus = Kursus::findOrFail($id);
        
        // Check if user already enrolled
        $existingEnrollment = Enrollment::where('user_id', auth()->id())
            ->where('kursus_id', $id)
            ->first();
        
        if ($existingEnrollment) {
            return redirect()->route('user.pelatihan.index')
                ->with('info', 'Anda sudah terdaftar di pelatihan ini.');
        }
        
        // Create enrollment
        $enrollment = Enrollment::create([
            'user_id' => auth()->id(),
            'kursus_id' => $kursus->id,
            'tanggal_daftar' => now(),
            'status' => $kursus->harga > 0 ? 'pending' : 'active',
            'progress' => 0,
        ]);
        
        // If free course, redirect to training page
        if ($kursus->harga <= 0) {
            return redirect()->route('user.pelatihan.show', $enrollment->id)
                ->with('success', 'Pendaftaran berhasil! Silakan mulai belajar.');
        }
        
        // For paid courses, redirect to payment status
        return redirect()->route('user.pembayaran', $kursus->id)
            ->with('success', 'Silakan selesaikan pembayaran untuk mengakses pelatihan.');
    }
}
