<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sertifikat;
use App\Models\Enrollment;

class CertificateVerificationController extends Controller
{
    /**
     * Show the verification form
     */
    public function index(Request $request)
    {
        // optionally accept query param for quick checks
        $query = $request->query('q');

        $result = null;
        $enrollment = null;

        if ($query) {
            // Load the certificate together with related user + kursus
            $result = Sertifikat::with(['user', 'kursus'])
                ->where('nomor_sertifikat', $query)
                ->first();

            // If a certificate was found, also attempt to load the enrollment
            // row for the (user, kursus) to show nilai_akhir and progress.
            if ($result) {
                $enrollment = Enrollment::where('user_id', $result->user_id)
                    ->where('kursus_id', $result->kursus_id)
                    ->first();
            }
        }

        return view('verify.sertifikat.index', compact('result', 'query', 'enrollment'));
    }

    /**
     * Verify by form POST and redirect back with query
     */
    public function verify(Request $request)
    {
        $request->validate([
            'nomor' => 'required|string|max:255'
        ]);

        $nomor = trim($request->input('nomor'));

        // redirect to index with query param so index handles lookup and display
        return redirect()->route('verify.sertifikat.index', ['q' => $nomor]);
    }

    /**
     * QR scan endpoint — accepts a token (nomor_sertifikat) and shows verification result.
     * Useful when a printed/digital cert contains a QR that encodes the certificate number
     * as a path segment (e.g. /verifikasi-sertifikat/scan/CERT-ALG-2025-001234).
     */
    public function scan(Request $request, $token)
    {
        $query = trim($token);

        $result = null;
        $enrollment = null;

        if ($query) {
            $result = Sertifikat::with(['user', 'kursus'])
                ->where('nomor_sertifikat', $query)
                ->first();

            if ($result) {
                $enrollment = \App\Models\Enrollment::where('user_id', $result->user_id)
                    ->where('kursus_id', $result->kursus_id)
                    ->first();
            }
        }

        return view('verify.sertifikat.index', compact('result', 'query', 'enrollment'));
    }
}
