<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Perbarui (ganti) password pengguna yang sedang login.
     *
     * Alur singkat:
     * 1. Validasi input: cek password saat ini dan password baru (beserta konfirmasi)
     * 2. Jika valid, simpan password baru yang sudah di-hash ke database
     * 3. Kembalikan respons ke halaman sebelumnya dengan status sukses
     *
     * Penjelasan variabel/konsep penting:
     * - $request: berisi input dari form (current_password, password, password_confirmation)
     * - validateWithBag('updatePassword', ...): melakukan validasi dan menempatkan
     *   error ke "bag" bernama 'updatePassword' agar mudah ditampilkan di view
     * - Password::defaults(): aturan standar password (panjang/kompleksitas dasar)
     * - current_password: rule yang memeriksa apakah password saat ini cocok dengan yang ada
     *
     * Alasan langkah tertentu:
     * - Mengharuskan current_password untuk memastikan orang yang mengganti password
     *   memang pemilik akun (mengurangi risiko perubahan tanpa izin).
     * - Meng-hash password sebelum menyimpan agar password tidak tersimpan dalam bentuk teks.
     * - Menggunakan "confirmed" untuk memastikan user mengetik password baru dua kali sama.
     *
     * @param Request $request Data dari form ubah password
     * @return RedirectResponse Kembali ke halaman sebelumnya dengan status
     */
    public function update(Request $request): RedirectResponse
    {
        // Lakukan validasi input. Jika gagal, errors akan masuk ke error bag 'updatePassword'.
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'], // pastikan cocok dengan password sekarang
            'password' => ['required', Password::defaults(), 'confirmed'], // password baru harus sesuai aturan dan dikonfirmasi
        ]);

        // Simpan password baru yang sudah di-hash ke model user saat ini
        $request->user()->update([
            'password' => Hash::make($validated['password']), // Hash agar tidak tersimpan teks asli
        ]);

        // Kembalikan ke halaman sebelumnya dengan pesan status untuk notifikasi
        return back()->with('status', 'password-updated');
    }
}
