<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Tampilkan halaman reset password.
     *
     * Fungsi ini menampilkan form di mana pengguna memasukkan token, email,
     * dan password baru. Parameter $request diteruskan ke view agar token dan
     * email tetap tersedia bila perlu.
     *
     * @param Request $request Data permintaan (berisi token jika diklik dari email)
     * @return View Halaman form reset password
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Tangani permintaan untuk menyetel password baru.
     *
     * Alur singkat:
     * 1. Validasi input (token, email, password dan konfirmasi password)
     * 2. Coba reset password lewat facade Password (yang mengurus token & validasi)
     * 3. Jika sukses: simpan password baru, buat ulang remember_token, kirim event
     * 4. Kembalikan respons sesuai status (redirect ke login atau kembali dengan error)
     *
     * Penjelasan variabel penting:
     * - $request: berisi token, email, password, dan password_confirmation
     * - $status: hasil dari Password::reset(), berisi kode status (sukses atau alasan gagal)
     *
     * Alasan langkah tertentu:
     * - Rules\Password::defaults() memastikan password memenuhi aturan keamanan
     *   (panjang/kompleksitas dasar) agar akun tidak mudah dibobol.
     * - forceFill + save() digunakan untuk langsung menulis field password yang
     *   sudah di-hash ke database.
     * - remember_token diubah untuk membatalkan sesi lama (agar token lama tidak lagi valid).
     * - event PasswordReset dipicu agar listener lain (mis. audit/login) bisa tahu ada perubahan.
     *
     * @param Request $request Data dari form reset password
     * @throws \Illuminate\Validation\ValidationException Jika validasi input gagal
     * @return RedirectResponse Redirect/response sesuai hasil reset
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input dasar: token wajib, email format benar, password harus dikonfirmasi
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Password::reset akan memeriksa token dan email, lalu menjalankan callback
        // jika token valid. Callback menerima model User dan bertugas menyimpan
        // password baru (sudah di-hash) ke database.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                // Simpan password baru yang sudah di-hash dan buat ulang remember_token
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Beri tahu sistem bahwa password telah direset (bisa untuk logging atau listener lain)
                event(new PasswordReset($user));
            }
        );

        // Jika berhasil, Password::reset mengembalikan konstanta PASSWORD_RESET.
        // Kita arahkan user ke halaman login dengan pesan sukses.
        // Jika gagal, kembalikan ke form dengan input email dan tampilkan error.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
