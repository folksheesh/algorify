<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Log;

class PelatihanSayaController extends Controller
{
    public function index()
    {
        try {
            // Get current authenticated user
            $user = Auth::user();
            
            // Get all enrollments for this user with kursus relationship
            $enrollments = Enrollment::with(['kursus', 'kursus.modul'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('user.pelatihan-saya.index', compact('enrollments'));
        } catch (\Exception $e) {
            Log::error('Error fetching user enrollments: ' . $e->getMessage());
            
            // Return empty collection if database error
            $enrollments = collect();
            return view('user.pelatihan-saya.index', compact('enrollments'));
        }
    }
}
