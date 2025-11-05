<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller untuk menangani pengiriman email verifikasi
 * 
 * Controller ini bertugas mengirim ulang email verifikasi kepada pengguna
 * yang belum memverifikasi alamat emailnya. Ini penting untuk:
 * - Memastikan email pengguna valid
 * - Mencegah pendaftaran dengan email palsu
 * - Meningkatkan keamanan akun
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Mengirim email verifikasi baru ke pengguna
     * 
     * Cara kerja fungsi ini:
     * 1. Periksa apakah email sudah diverifikasi
     * 2. Jika sudah, arahkan ke dashboard
     * 3. Jika belum, kirim email verifikasi baru
     * 
     * @param Request $request Data permintaan dari pengguna
     * @return RedirectResponse Mengarahkan pengguna ke halaman yang sesuai
     */
    public function store(Request $request): RedirectResponse
    {
        // Cek apakah email sudah diverifikasi sebelumnya
        if ($request->user()->hasVerifiedEmail()) {
            // Jika sudah verifikasi, langsung ke dashboard
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Jika belum verifikasi, kirim email verifikasi baru
        $request->user()->sendEmailVerificationNotification();

        // Kembali ke halaman sebelumnya dengan pesan sukses
        // 'verification-link-sent' akan ditampilkan sebagai notifikasi
        return back()->with('status', 'verification-link-sent');
    }
}
