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

            // Local-only development fallback: when running on local and the
            // CERT_DEMO_ENABLED flag is true, return a non-persistent demo
            // certificate so the verification page can be tried without DB data.
            if (! $result && app()->environment('local') && env('CERT_DEMO_ENABLED', false)) {
                // Create an in-memory demo result (stdClass) — do NOT persist
                $result = new \stdClass();
                $result->nomor_sertifikat = $query ?: 'CERT-ALG-2025-001234';
                $result->judul = 'Sertifikat Desain UI/UX (Demo)';
                $result->deskripsi = 'Demo sertifikat untuk verifikasi lokal';
                $result->tanggal_terbit = now();
                $result->status_sertifikat = 'active';
                $result->file_path = null;

                // attach a demo user and kursus objects (stdClass) so the view can render them
                $user = new \stdClass();
                $user->id = 0;
                $user->name = 'Prashant Kumar Singh';

                $kursus = new \stdClass();
                $kursus->id = 0;
                $kursus->judul = 'Desain UI/UX';
                $kursus->tanggal_selesai = now();

                $result->user = $user;
                $result->kursus = $kursus;

                // Demo enrollment info
                $enrollment = new \stdClass();
                $enrollment->nilai_akhir = 85;
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

            // Local-only fallback for scan (non-persistent demo) — mirrors index() behaviour
            if (! $result && app()->environment('local') && env('CERT_DEMO_ENABLED', false)) {
                $result = new \stdClass();
                $result->nomor_sertifikat = $query ?: 'CERT-ALG-2025-001234';
                $result->judul = 'Sertifikat Desain UI/UX (Demo)';
                $result->deskripsi = 'Demo sertifikat untuk verifikasi lokal';
                $result->tanggal_terbit = now();
                $result->status_sertifikat = 'active';
                $result->file_path = null;

                $user = new \stdClass();
                $user->id = 0;
                $user->name = 'Prashant Kumar Singh';

                $kursus = new \stdClass();
                $kursus->id = 0;
                $kursus->judul = 'Desain UI/UX';
                $kursus->tanggal_selesai = now();

                $result->user = $user;
                $result->kursus = $kursus;

                $enrollment = new \stdClass();
                $enrollment->nilai_akhir = 85;
            }
        }

        return view('verify.sertifikat.index', compact('result', 'query', 'enrollment'));
    }
}
