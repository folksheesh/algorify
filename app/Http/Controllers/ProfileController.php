<?php // Controller untuk fitur profil user

namespace App\Http\Controllers; // Namespace controller

use App\Http\Requests\ProfileUpdateRequest; // Import request khusus update profil
use Illuminate\Http\RedirectResponse; // Untuk response redirect
use Illuminate\Http\Request; // Untuk request HTTP
use Illuminate\Support\Facades\Auth; // Untuk autentikasi
use Illuminate\Support\Facades\Redirect; // Untuk redirect
use Illuminate\Support\Facades\Storage; // Untuk storage
use Illuminate\View\View; // Untuk view

// Controller ini menangani fitur profil user
class ProfileController extends Controller
{
    /**
     * Menampilkan form profil user.
     */
    public function edit(Request $request): View
    {
        // Ambil data user terbaru dari database
        $user = $request->user()->fresh();

        // Kirim data user ke view profile.index
        return view('profile.index', [
            'user' => $user,
        ]);
    }

    /**
     * Memperbarui data profil user.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Ambil user yang sedang login
        $user = $request->user(); 
        
        // Isi data user dengan data yang sudah divalidasi
        $user->fill($request->validated());

        // Jika email berubah, verifikasi email direset
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Proses upload foto profil baru
        if ($request->hasFile('foto_profil')) {
            // Simpan foto baru ke storage
            $path = $request->file('foto_profil')->store('profile-photos', 'public');

            // Simpan path foto ke session untuk ditampilkan tanpa menyimpan ke database
            $request->session()->put('temp_profile_photo', $path);

            // Jika user ingin menyimpan foto ke database (opsional), uncomment baris berikut:
            // $user->foto_profil = $path;
        }

        // Proses ganti password
        if ($request->filled('password_baru')) {
            // Validasi password lama
            if (!$request->filled('password_lama') || !\Hash::check($request->password_lama, $user->password)) {
                return Redirect::route('profile.edit')->withErrors(['password_lama' => 'Password lama tidak sesuai']);
            }
            // Simpan password baru
            $user->password = \Hash::make($request->password_baru);
        }

        // Simpan perubahan ke database
        $user->save();

        // Refresh data user dari database untuk memastikan data terbaru
        $user = $user->fresh();

        // Update authenticated user di session
        $request->session()->put('user', $user);
        Auth::setUser($user);

        // Redirect ke halaman edit profil dengan status sukses
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi password sebelum hapus akun
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user(); // Ambil user yang sedang login

        Auth::logout(); // Logout user

        $user->delete(); // Hapus user dari database

        // Invalidate session dan generate token baru
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return Redirect::to('/');
    }
}
