<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller untuk menangani login dan logout pengguna.
 * Sederhananya: tampilkan form login, proses login, dan proses logout.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     *
     * @return View Halaman form login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     * - Cek kredensial melalui LoginRequest
     * - Buat sesi baru kalau berhasil
     * - Arahkan ke dashboard atau halaman yang diminta
     *
     * @param LoginRequest $request Data login dari user
     * @return RedirectResponse Arahkan user setelah login
     */
    
    public function store(LoginRequest $request): RedirectResponse
    {
        // Periksa dan autentikasi user
        $request->authenticate();

        // Buat ulang sesi untuk mencegah fixation
        $request->session()->regenerate();

        // Login ini bukan dari Google OAuth, jadi pastikan flag direset
        $request->session()->forget('login_via_google');

        // Arahkan ke halaman yang diminta sebelumnya atau ke dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Proses logout.
     * - Logout user
     * - Hapus sesi
     * - Buat token CSRF baru
     * - Kembali ke halaman utama
     *
     * @param Request $request Request dari user
     * @return RedirectResponse Arahkan user ke beranda
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout user
        Auth::guard('web')->logout();

        // Hapus data sesi saat ini
        $request->session()->invalidate();

        // Buat token CSRF (penjelasan) baru
        // Untuk memastikan setiap permintaan berasal dari pengguna asli, bukan situs berbahaya.
        $request->session()->regenerateToken();

        // Kembali ke beranda
        return redirect('/');
    }
}
