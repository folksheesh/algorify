<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SertifikatController extends Controller
{
    public function index()
    {
        // Get current signature if exists
        $signaturePath = 'signatures/director_signature.png';
        $signature = null;
        
        // Check for PNG first, then JPG, then JPEG
        foreach (['png', 'jpg', 'jpeg'] as $ext) {
            $path = "signatures/director_signature.{$ext}";
            if (Storage::disk('public')->exists($path)) {
                $signature = asset('storage/' . $path);
                break;
            }
        }
            
        return view('admin.sertifikat.index', compact('signature'));
    }

    public function uploadSignature(Request $request)
    {
        try {
            $request->validate([
                'signature' => 'required|image|mimes:png,jpg,jpeg|max:2048'
            ], [
                'signature.required' => 'File tanda tangan wajib diupload',
                'signature.image' => 'File harus berupa gambar',
                'signature.mimes' => 'Format file yang diterima: PNG, JPG, atau JPEG',
                'signature.max' => 'Ukuran file maksimal 2MB'
            ]);

            // Delete old signatures if exists
            foreach (['png', 'jpg', 'jpeg'] as $ext) {
                $oldPath = "signatures/director_signature.{$ext}";
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Store new signature
            $extension = $request->file('signature')->getClientOriginalExtension();
            $path = $request->file('signature')->storeAs(
                'signatures',
                'director_signature.' . $extension,
                'public'
            );

            return response()->json([
                'success' => true,
                'message' => 'Tanda tangan berhasil diupload',
                'url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading signature: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload tanda tangan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteSignature()
    {
        try {
            $deleted = false;
            // Delete all possible signature formats
            foreach (['png', 'jpg', 'jpeg'] as $ext) {
                $path = "signatures/director_signature.{$ext}";
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                    $deleted = true;
                }
            }

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tanda tangan berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada tanda tangan untuk dihapus'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting signature: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tanda tangan'
            ], 500);
        }
    }
}
