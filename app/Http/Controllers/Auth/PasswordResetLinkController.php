<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Tampilkan halaman permintaan tautan reset password.
     *
     * Halaman ini berisi form di mana pengguna memasukkan email mereka
     * untuk menerima tautan reset password melalui email.
     *
     * @return View Halaman form lupa password
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Tangani permintaan untuk mengirim tautan reset password.
     *
     * Alur kerja:
     * 1. Validasi format email yang dimasukkan pengguna
     * 2. Panggil Password::sendResetLink untuk mencoba mengirim email
     * 3. Periksa status hasil pengiriman dan berikan respons yang sesuai
     *
     * Penjelasan variabel penting:
     * - $request: berisi input dari form (hanya 'email' yang dipakai)
     * - $status: hasil dari Password::sendResetLink() (kode yang menjelaskan sukses/gagal)
     *
     * Alasan langkah tertentu:
     * - Validasi email diperlukan agar tidak mencoba mengirim ke alamat yang jelas salah
     * - Password::sendResetLink mengurus pembuatan token dan pengiriman email
     *   (kita tidak perlu menulis logika token manual di sini)
     * - Mengembalikan input email saat gagal agar pengguna tidak perlu mengetik ulang
     *
     * @param Request $request Data dari form lupa password
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
     * @return RedirectResponse Kembali ke form dengan status atau error
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input: pastikan email diisi dan berbentuk alamat email yang benar
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Coba kirim tautan reset password ke email yang diminta.
        // Password::sendResetLink akan membuat token dan mengirim email sesuai konfigurasi.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Jika pengiriman sukses, tampilkan status sukses. Jika gagal, kembalikan
        // ke form dengan input email supaya pengguna bisa mencoba lagi.
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
