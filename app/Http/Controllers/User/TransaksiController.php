<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    /**
     * Return JSON status for a transaction identified by its kode_transaksi.
     * This route is intended for the frontend polling that checks live status.
     */
    public function status(Request $request, $kode)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode)->first();

        if (! $transaksi) {
            return response()->json(['error' => 'Transaksi not found'], 404);
        }

        return response()->json([
            'kode' => $transaksi->kode_transaksi,
            'status' => $transaksi->status,
        ]);
    }
}
