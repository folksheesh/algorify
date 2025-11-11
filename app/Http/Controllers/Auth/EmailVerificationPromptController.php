<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk menampilkan halaman permintaan verifikasi email
 * 
 * Controller ini menggunakan pola "__invoke" (controller dengan satu fungsi)
 * untuk menangani tampilan halaman verifikasi email. Controller ini akan:
 * - Mengecek status verifikasi email pengguna
 * - Mengarahkan ke dashboard jika sudah terverifikasi
 * - Menampilkan halaman verifikasi jika belum terverifikasi
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * Menampilkan halaman verifikasi email atau mengarahkan ke dashboard
     * 
     * Cara kerja:
     * - Jika email sudah diverifikasi: arahkan ke dashboard
     * - Jika belum: tampilkan halaman verifikasi email
     * 
     * @param Request $request Data permintaan dari pengguna
     * @return RedirectResponse|View Bisa mengembalikan redirect atau tampilan
     *         tergantung status verifikasi email pengguna
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // Gunakan operator ternary (?) untuk mengecek status verifikasi
        // dan menentukan tindakan yang sesuai
        return $request->user()->hasVerifiedEmail()
                    // Jika sudah verifikasi: ke dashboard
                    ? redirect()->intended(route('dashboard', absolute: false))
                    // Jika belum: tampilkan halaman verifikasi
                    : view('auth.verify-email');
    }
}
