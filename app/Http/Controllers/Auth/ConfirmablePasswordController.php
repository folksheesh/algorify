<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controller untuk memastikan password pengguna
 * 
 * Controller ini digunakan saat aplikasi perlu memverifikasi ulang password pengguna
 * sebelum mengizinkan akses ke halaman-halaman sensitif/penting.
 * Ini menambah lapisan keamanan untuk aksi-aksi penting.
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Menampilkan halaman konfirmasi password
     * 
     * Fungsi ini menampilkan form dimana pengguna diminta
     * memasukkan password mereka lagi untuk verifikasi tambahan
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Memeriksa kebenaran password yang dimasukkan pengguna
     * 
     * Cara kerja fungsi ini:
     * 1. Mengambil email pengguna yang sedang login
     * 2. Memeriksa apakah password yang dimasukkan cocok
     * 3. Jika cocok, catat waktu konfirmasi di sesi
     * 4. Jika tidak cocok, kembalikan pesan error
     * 
     * @param Request $request Data dari form konfirmasi password
     * @throws ValidationException Dilempar jika password salah
     */
    public function store(Request $request): RedirectResponse
    {
        // Cek apakah kombinasi email dan password benar
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,  // Ambil email user yang sedang login
            'password' => $request->password,     // Password dari form
        ])) {
            // Jika salah, lempar error dengan pesan dari file bahasa
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Jika benar, simpan waktu konfirmasi di sesi
        // Ini digunakan sistem untuk tahu kapan terakhir password dikonfirmasi
        $request->session()->put('auth.password_confirmed_at', time());

        // Arahkan pengguna ke halaman yang diminta sebelumnya atau ke dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
