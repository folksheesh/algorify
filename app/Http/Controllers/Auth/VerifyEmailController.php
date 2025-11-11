<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Tandai email pengguna yang sedang login sebagai "terverifikasi".
     *
     * Penjelasan singkat:
     * - Fungsi ini dipanggil ketika pengguna menekan tautan verifikasi di email.
     * - Jika email sudah terverifikasi, langsung arahkan ke dashboard.
     * - Jika belum, coba tandai sebagai terverifikasi lalu kirim event Verified
     *   (agar bagian lain dari aplikasi bisa merespon, mis. mencatat log atau
     *   memberi akses lebih).
     *
     * Arti parameter/variabel penting:
     * - $request: objek EmailVerificationRequest yang sudah memeriksa signature
     *   (mengamankan bahwa tautan verifikasi valid dan tidak diubah).
     * - $request->user(): pengguna yang sedang login terkait permintaan ini.
     *
     * Alasan langkah tertentu:
     * - Cek hasVerifiedEmail() terlebih dahulu agar tidak memproses ulang
     *   verifikasi yang sudah pernah dilakukan.
     * - markEmailAsVerified() mengubah status di database dan mengembalikan true
     *   jika perubahan terjadi (berguna untuk memicu event hanya saat perlu).
     * - event(new Verified(...)) dipicu supaya listener lain (mis. untuk
     *   mencatat atau mengirim notifikasi) dapat berjalan.
     *
     * Hasil: setelah proses, pengguna diarahkan ke dashboard dengan query
     * parameter ?verified=1 sebagai indikator bahwa verifikasi berhasil.
     */
    
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Jika email sudah terverifikasi sebelumnya, langsung ke dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // Tandai email sebagai terverifikasi. Jika berhasil, jalankan event
        if ($request->user()->markEmailAsVerified()) {
            // Memicu event agar bagian lain sistem tahu ada verifikasi baru
            event(new Verified($request->user()));
        }

        // Arahkan ke dashboard, sertakan query param sebagai tanda verifikasi sukses
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
