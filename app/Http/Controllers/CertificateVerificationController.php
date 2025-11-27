<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sertifikat;

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

        if ($query) {
            $result = Sertifikat::with(['user', 'kursus'])
                ->where('nomor_sertifikat', $query)
                ->first();
        }

        return view('verify.sertifikat.index', compact('result', 'query'));
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
}
