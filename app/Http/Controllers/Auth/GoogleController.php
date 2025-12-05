<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    /**
     * Arahkan pengguna ke Google untuk proses login (OAuth)
     *
     * Fungsi ini memulai alur OAuth dengan Google. Pengguna akan
     * diarahkan ke halaman Google untuk memilih akun dan memberi izin.
     *
     * @return RedirectResponse Redirect ke halaman otorisasi Google
     */
    public function redirectToGoogle(): RedirectResponse
    {
        // Gunakan driver 'google' dari Socialite untuk memulai redirect
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback dari Google setelah pengguna mengizinkan/menolak
     *
     * Alur singkat:
     * - Jika pengguna menolak (atau ada error), kembalikan ke login dengan pesan
     * - Jika tidak ada 'code' (token), ulangi proses redirect
     * - Ambil data pengguna dari Google
     * - Cari user di database berdasarkan email
     * - Jika belum ada, buat user baru (beri password acak karena kolom diperlukan)
     * - Login user dan arahkan ke dashboard
     *
     * Catatan variabel penting:
     * - $request : data callback dari Google (mengandung 'code' atau 'error')
     * - $googleUser : objek yang berisi info user dari Google (email, nama, dsb.)
     * - $user : model User dari aplikasi (dipakai untuk login)
     *
     * Alasan langkah tertentu:
     * - Menggunakan password acak saat membuat user baru karena tabel user
     *   biasanya memerlukan field password; pengguna tetap bisa login via OAuth.
     * - Memanggil stateless() ke Socialite untuk menghindari masalah sesi
     *   saat aplikasi tidak menyimpan state OAuth (lebih sederhana untuk API/edge cases).
     * - Mencoba assign role 'peserta' hanya jika fitur role tersedia; jika tidak
     *   maka dijaga agar tidak menimbulkan error.
     *
     * @param Request $request Data callback dari Google
     * @return RedirectResponse Arahkan user setelah proses selesai
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        // Jika pengguna menolak permintaan (contoh: menekan "Cancel"),
        // Google biasanya mengembalikan parameter 'error'. Tunjukkan pesan yang ramah.
        if ($request->has('error')) {
            return redirect()->route('login')->with('oauth_error', 'Google sign-in dibatalkan.');
        }

        // Jika callback tidak berisi 'code', berarti alur OAuth belum lengkap.
        // Arahan ulang ke route yang memulai proses OAuth.
        if (! $request->has('code')) {
            return redirect()->route('google.redirect');
        }

        try {
            // Get user data from Google OAuth
            // Gunakan stateless() untuk menghindari masalah InvalidStateException
            // yang terjadi karena session state tidak tersimpan dengan baik
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();
            // Cari user di database berdasarkan email (umumnya unik)
            $user = User::where('email', $googleUser->getEmail())->first();

            if (! $user) {
                // Jika user belum ada, buat user baru dengan data dari Google.
                // Gunakan nama yang tersedia: nama lengkap, nickname, atau fallback.
                $user = User::create([
                    'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                    'email' => $googleUser->getEmail(),
                    // Simpan password acak karena kolom password biasanya wajib
                    'password' => Hash::make(Str::random(24)),
                ]);

                // Jika paket manajemen peran (Spatie) tersedia, coba beri peran 'peserta'
                // Kami bungkus dengan try/catch agar tidak crash jika role/table belum ada.
                if (method_exists($user, 'assignRole')) {
                    try {
                        $user->assignRole('peserta');
                    } catch (\Throwable $e) {
                        // Jika role belum ada, coba buat role lalu assign lagi.
                        try {
                            \Spatie\Permission\Models\Role::findOrCreate('peserta');
                            $user->assignRole('peserta');
                        } catch (\Throwable $e2) {
                            // Jika tetap gagal, kita abaikan agar proses login tetap jalan.
                        }
                    }
                }
            }

            // Login user ke aplikasi (remember = true agar sesi persisten)
            Auth::login($user, true);

            // Arahkan user ke halaman tujuan (dashboard) atau ke halaman yang diminta sebelumnya
            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Tangani error apapun yang terjadi saat OAuth
            return redirect()->route('login')->with('oauth_error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
}
