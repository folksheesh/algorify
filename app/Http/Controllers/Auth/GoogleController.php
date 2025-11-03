<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google for authentication.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        // If user declined or the request is invalid, guide them back gracefully
        if ($request->has('error')) {
            return redirect()->route('login')->with('oauth_error', 'Google sign-in dibatalkan.');
        }
        if (!$request->has('code')) {
            // No code in callback, restart the OAuth flow
            return redirect()->route('google.redirect');
        }

        $googleUser = Socialite::driver('google')->stateless()->user();

        // Try finding by Google ID first, then by email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                'email' => $googleUser->getEmail(),
                // Store a random password since users table requires it
                'password' => Hash::make(Str::random(24)),
            ]);

            // Assign default role "peserta" if Spatie is installed and tables exist
            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('peserta');
                } catch (\Throwable $e) {
                    // role might not exist yet; we can create it silently
                    try {
                        \Spatie\Permission\Models\Role::findOrCreate('peserta');
                        $user->assignRole('peserta');
                    } catch (\Throwable $e2) {
                        // ignore
                    }
                }
            }
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}
