<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman pendaftaran (register).
     *
     * Halaman ini menampilkan form untuk input nama, email, dan password.
     *
     * @return View Tampilan form registrasi
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Tangani permintaan pendaftaran user baru.
     *
     * Alur singkat:
     * 1. Validasi input (nama, email, password + konfirmasi)
     * 2. Buat user baru di database dengan password yang sudah di-hash
     * 3. Jika tersedia, beri peran default 'peserta' (cek Spatie Role)
     * 4. Trigger event Registered (untuk listener lain, mis. verifikasi email)
     * 5. Login user baru dan arahkan ke dashboard
     *
     * Penjelasan variabel/konsep penting:
     * - $request: berisi data dari form registrasi
     * - Rules\Password::defaults(): aturan keamanan password dasar (panjang/kompleksitas)
     * - method_exists($user, 'assignRole'): cek apakah fitur role (Spatie) tersedia
     * - event(new Registered($user)): memicu event sehingga sistem lain bisa merespon
     *
     * Alasan langkah tertentu:
     * - Validasi email 'unique' mencegah duplikasi akun dengan email sama.
     * - Hash password sebelum menyimpan agar password tidak tersimpan dalam bentuk teks.
     * - Pemberian role dilakukan secara hati-hati (try/catch) agar tidak menimbulkan
     *   error jika paket role belum terpasang atau tabel role belum dibuat.
     *
     * @param Request $request Data dari form registrasi
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal
     * @return RedirectResponse Arahkan user setelah pendaftaran (ke dashboard)
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input: pastikan nama, email, dan password sesuai aturan
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'profesi' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'address' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'profesi.required' => 'Profesi/pekerjaan wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'address.required' => 'Kabupaten/Kota wajib dipilih.',
        ]);

        // Buat user baru dengan password yang sudah di-hash
        $user = User::create([
            'id' => User::generateId('peserta'),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'profesi' => $request->profesi,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'address' => $request->address,
        ]);

        // Jika model User mendukung assignRole (biasanya karena Spatie terpasang),
        // coba beri role default 'peserta'. Bungkus dengan try/catch agar gagal
        // pemberian role tidak menggagalkan proses pendaftaran.
        if (method_exists($user, 'assignRole')) {
            try {
                $user->assignRole('peserta');
            } catch (\Throwable $e) {
                try {
                    // Jika role belum ada, coba buat lalu assign kembali
                    Role::findOrCreate('peserta');
                    $user->assignRole('peserta');
                } catch (\Throwable $e2) {
                    // Jika tetap gagal, abaikan agar pendaftaran tetap berhasil
                }
            }
        }

        // Beri tahu sistem bahwa user baru telah terdaftar (bisa untuk verifikasi email dsb.)
        event(new Registered($user));

        // Login user baru dan arahkan ke dashboard
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
