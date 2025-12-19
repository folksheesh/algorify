<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCompletionController extends Controller
{
    /**
     * Show the profile completion form for newly registered users.
     */
    public function show()
    {
        return view('auth.complete-profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Persist required profile fields and continue to the intended page.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'profesi' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $user->fill($data);

        // Backfill registration date if it was not set
        if (! $user->tanggal_daftar) {
            $user->tanggal_daftar = now();
        }

        $user->save();

        return redirect()->intended(route('dashboard'))
            ->with('status', 'Profil berhasil dilengkapi.');
    }
}
