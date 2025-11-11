<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class PelatihanSayaController extends Controller
{
    public function index()
    {
        // Get current authenticated user
        $user = Auth::user();
        
        // Get all enrollments for this user with kursus relationship
        $enrollments = Enrollment::with(['kursus', 'kursus.modul'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user.pelatihan-saya.index', compact('enrollments'));
    }
}
