<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileComplete
{
    /**
     * Redirect users to the profile completion page when required fields are missing.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Allow guests or already completed profiles to proceed
        if (! $user || $this->isProfileComplete($user)) {
            return $next($request);
        }

        // Avoid redirect loop on the completion routes
        if ($request->routeIs('profile.complete.*')) {
            return $next($request);
        }

        return redirect()->route('profile.complete.show')
            ->with('status', 'Lengkapi profil Anda terlebih dahulu.');
    }

    /**
     * Determine if all mandatory profile fields are filled.
     */
    protected function isProfileComplete($user): bool
    {
        $requiredFields = [
            'phone',
            'profesi',
            'tanggal_lahir',
            'jenis_kelamin',
            'address',
        ];

        foreach ($requiredFields as $field) {
            if (! filled($user->{$field} ?? null)) {
                return false;
            }
        }

        return true;
    }
}
