<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profesi' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'pendidikan' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'password_lama' => 'nullable|string',
            'password_baru' => 'nullable|string|min:8|confirmed',
        ]);

        $user->fill($request->only(['name', 'email', 'phone', 'profesi', 'address', 'pendidikan']));

        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            // Delete old photo if exists
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            
            // Store new photo
            $path = $request->file('foto_profil')->store('profile-photos', 'public');
            $user->foto_profil = $path;
        }

        // Handle password change
        if ($request->filled('password_baru')) {
            if (!$request->filled('password_lama') || !Hash::check($request->password_lama, $user->password)) {
                return response()->json([
                    'message' => 'Password lama tidak sesuai',
                    'errors' => ['password_lama' => ['Password lama tidak sesuai']]
                ], 422);
            }
            $user->password = Hash::make($request->password_baru);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
            'id' => $user->id
        ]);
    }
}
