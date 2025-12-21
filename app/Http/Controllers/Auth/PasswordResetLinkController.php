<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Tampilkan halaman permintaan OTP reset password.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim OTP ke email pengguna.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem.']);
        }

        // Generate OTP
        $otp = PasswordResetOtp::generateFor($request->email);

        // Send OTP via email
        try {
            Mail::send('emails.password-reset-otp', [
                'otp' => $otp,
                'name' => $user->name,
            ], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Kode OTP Reset Password - Algorify');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi.']);
        }

        // Store email in session for next step
        session(['password_reset_email' => $request->email]);

        return redirect()->route('password.verify-otp')
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Tampilkan form verifikasi OTP.
     */
    public function showVerifyOtp(): View
    {
        $email = session('password_reset_email');
        
        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    /**
     * Verifikasi OTP yang dimasukkan.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('password_reset_email');
        
        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi telah berakhir. Silakan mulai ulang.']);
        }

        // Verify OTP
        if (!PasswordResetOtp::verify($email, $request->otp)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
        }

        // Mark as verified in session
        session(['password_reset_verified' => true]);

        return redirect()->route('password.reset.form');
    }

    /**
     * Tampilkan form reset password (setelah OTP terverifikasi).
     */
    public function showResetForm(): View
    {
        $email = session('password_reset_email');
        $verified = session('password_reset_verified');
        
        if (!$email || !$verified) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', ['email' => $email]);
    }
}
