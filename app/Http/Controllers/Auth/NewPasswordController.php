<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Tampilkan halaman reset password (setelah OTP terverifikasi).
     */
    public function create(Request $request): View
    {
        $email = session('password_reset_email');
        $verified = session('password_reset_verified');
        
        if (!$email || !$verified) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', ['email' => $email]);
    }

    /**
     * Simpan password baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = session('password_reset_email');
        $verified = session('password_reset_verified');
        
        if (!$email || !$verified) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi telah berakhir. Silakan mulai ulang.']);
        }

        // Find user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Update password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Clear OTP and session data
        PasswordResetOtp::clearFor($email);
        session()->forget(['password_reset_email', 'password_reset_verified']);

        // Fire event
        event(new PasswordReset($user));

        return redirect()->route('login')
            ->with('status', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
