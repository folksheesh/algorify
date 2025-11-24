<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modul;
use App\Models\Materi;
use App\Models\Video;

class UrutanController extends Controller
{
    /**
     * Update urutan modul
     */
    public function updateModulOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:modul,id',
            'orders.*.urutan' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $order) {
            Modul::where('id', $order['id'])->update(['urutan' => $order['urutan']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan modul berhasil diperbarui'
        ]);
    }

    /**
     * Update urutan materi dalam modul
     */
    public function updateMateriOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:materi,id',
            'orders.*.urutan' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $order) {
            Materi::where('id', $order['id'])->update(['urutan' => $order['urutan']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan materi berhasil diperbarui'
        ]);
    }

    /**
     * Update urutan video dalam modul
     */
    public function updateVideoOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:video,id',
            'orders.*.urutan' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $order) {
            Video::where('id', $order['id'])->update(['urutan' => $order['urutan']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan video berhasil diperbarui'
        ]);
    }
}
