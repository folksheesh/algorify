<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Sertifikat;
use App\Models\Kursus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Nilai;

class SertifikatSayaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get completed enrollments (100% progress or nilai_akhir >= 70)
        // Include both 'active' and 'completed' status since progress 100% changes status to 'completed'
        $completedEnrollments = Enrollment::where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->where(function($query) {
                $query->where('progress', '>=', 100)
                      ->orWhere('nilai_akhir', '>=', 70);
            })
            ->with(['kursus.user', 'kursus.modul.ujian'])
            ->latest()
            ->get();
        
        // Get certificates
        $certificates = Sertifikat::where('user_id', $user->id)
            ->with(['kursus.user'])
            ->latest()
            ->get();
        
        // Map enrollments with certificate status and calculate nilai_akhir if missing
        $enrollmentsWithCertStatus = $completedEnrollments->map(function($enrollment) use ($certificates, $user) {
            $cert = $certificates->where('kursus_id', $enrollment->kursus_id)->first();
            $enrollment->has_certificate = $cert ? true : false;
            $enrollment->certificate = $cert;
            
            // Calculate nilai_akhir from Nilai table if not set
            if (empty($enrollment->nilai_akhir) || $enrollment->nilai_akhir == 0) {
                // Get all ujian IDs from this kursus
                $ujianIds = $enrollment->kursus->modul->flatMap(function($modul) {
                    return $modul->ujian->pluck('id');
                })->toArray();
                
                if (!empty($ujianIds)) {
                    // Calculate average nilai from all ujian in this kursus
                    $avgNilai = Nilai::where('user_id', $user->id)
                        ->whereIn('ujian_id', $ujianIds)
                        ->avg('nilai');
                    
                    $enrollment->nilai_akhir = $avgNilai ? round($avgNilai, 2) : 0;
                }
            }
            
            return $enrollment;
        });
        
        return view('user.sertifikat.index', [
            'completedEnrollments' => $enrollmentsWithCertStatus,
            'certificates' => $certificates,
            'totalCompleted' => $completedEnrollments->count(),
            'totalCertificates' => $certificates->count()
        ]);
    }
    
    public function generate($enrollmentId)
    {
        $user = Auth::user();
        
        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('user_id', $user->id)
            ->with(['kursus.pengajar'])
            ->firstOrFail();
        
        // Check if already has certificate
        $existingCert = Sertifikat::where('user_id', $user->id)
            ->where('kursus_id', $enrollment->kursus_id)
            ->first();
        
        if ($existingCert) {
            return redirect()->route('user.sertifikat.download', $existingCert->id)
                ->with('info', 'Sertifikat sudah pernah dibuat, silakan unduh.');
        }
        
        // Check if eligible (progress >= 100 or nilai >= 70)
        if ($enrollment->progress < 100 && $enrollment->nilai_akhir < 70) {
            return back()->with('error', 'Anda belum menyelesaikan kursus ini. Selesaikan semua modul terlebih dahulu.');
        }
        
        // Create certificate record
        $certificate = Sertifikat::create([
            'user_id' => $user->id,
            'kursus_id' => $enrollment->kursus_id,
            'judul' => $enrollment->kursus->judul,
            'deskripsi' => 'Telah menyelesaikan pelatihan ' . $enrollment->kursus->judul,
            'tanggal_terbit' => now(),
            'status_sertifikat' => 'active',
        ]);
        
        return redirect()->route('user.sertifikat.download', $certificate->id)
            ->with('success', 'Sertifikat berhasil dibuat!');
    }
    
    public function download($id)
    {
        $user = Auth::user();
        
        $certificate = Sertifikat::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['kursus.pengajar', 'user'])
            ->firstOrFail();
        
        // Get enrollment to get nilai_akhir
        $enrollment = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('kursus_id', $certificate->kursus_id)
            ->first();
        
        // Prepare data for PDF
        $data = [
            'nama' => $user->name,
            'kursus' => $certificate->kursus->judul,
            'tanggal' => $certificate->tanggal_terbit->format('d F Y'),
            'nilai' => ($enrollment->nilai_akhir ?? 100) . '/100',
            'kode' => $certificate->nomor_sertifikat,
        ];
        
        // Generate PDF - custom size to fit content
        $pdf = Pdf::loadView('user.sertifikat.template', $data);
        $pdf->setPaper([0, 0, 680, 520]); // width x height in points (landscape-ish)
        
        return $pdf->download('Sertifikat-' . $certificate->nomor_sertifikat . '.pdf');
    }
    
    public function preview($id)
    {
        $user = Auth::user();
        
        $certificate = Sertifikat::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['kursus.pengajar', 'user'])
            ->firstOrFail();
        
        // Get signature
        $signaturePath = null;
        foreach (['png', 'jpg', 'jpeg'] as $ext) {
            $path = "signatures/director_signature.{$ext}";
            if (Storage::disk('public')->exists($path)) {
                $signaturePath = storage_path('app/public/' . $path);
                break;
            }
        }
        
        $data = [
            'certificate' => $certificate,
            'user' => $user,
            'kursus' => $certificate->kursus,
            'pengajar' => $certificate->kursus->pengajar,
            'signature' => $signaturePath,
            'tanggal_terbit' => $certificate->tanggal_terbit->format('d F Y'),
        ];
        
        return view('user.sertifikat.template', $data);
    }
}
