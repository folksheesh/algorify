<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sertifikat;

class SertifikatSayaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $certificates = Sertifikat::with('kursus')
            ->where('user_id', $user->id)
            ->orderBy('tanggal_terbit', 'desc')
            ->paginate(9);

        return view('user.sertifikat.index', compact('certificates'));
    }

    public function show($id)
    {
        $user = auth()->user();

        $certificate = Sertifikat::with('kursus')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('user.sertifikat.show', compact('certificate'));
    }
}
