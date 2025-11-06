<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sertifikat;
use Illuminate\Support\Facades\Log;

class SertifikatController extends Controller
{
    public function show($id)
    {
        $dbError = false;
        $sertifikat = null;
        try {
            $sertifikat = Sertifikat::with('kursus', 'user')->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('SertifikatController@show: '.$e->getMessage());
            // don't throw: render a friendly page indicating data unavailable
            $dbError = true;
        }

        return view('sertifikat.show', compact('sertifikat', 'dbError'));
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $dbError = false;
        try {
            $user = $request->user();
            // If user not authenticated for some reason, return empty set
            if (!$user) {
                $sertifikats = collect();
            } else {
                $sertifikats = Sertifikat::with('kursus')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
            }
        } catch (\Exception $e) {
            Log::error('SertifikatController@index: '.$e->getMessage());
            $sertifikats = collect();
            $dbError = true;
        }

        return view('sertifikat.index', compact('sertifikats', 'dbError'));
    }

    /**
     * Public verification form for certificates (no auth required)
     */
    public function verifyForm()
    {
        return view('sertifikat.verify');
    }

    /**
     * Handle verification submission and show result
     */
    public function verify(\Illuminate\Http\Request $request)
    {
        $nomor = $request->input('nomor');
        $dbError = false;
        $sertifikat = null;
        $status = null; // 'valid' | 'notfound' | 'error'

        try {
            if (!empty($nomor)) {
                $sertifikat = Sertifikat::with('kursus', 'user')
                    ->where('nomor_sertifikat', $nomor)
                    ->first();

                if ($sertifikat) {
                    $status = 'valid';
                } else {
                    $status = 'notfound';
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SertifikatController@verify: '.$e->getMessage());
            $dbError = true;
            $status = 'error';
        }

        return view('sertifikat.verify', compact('sertifikat', 'status', 'dbError'));
    }
}
