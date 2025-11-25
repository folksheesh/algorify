<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\Jawaban;
use App\Models\Nilai;
use App\Models\PilihanJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    /**
     * Submit jawaban ujian
     */
    public function submit(Request $request, $id)
    {
        $user = Auth::user();
        $ujian = Ujian::with('soal.pilihanJawaban')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Save all answers
            $jawaban = $request->input('jawaban', []);
            
            foreach ($jawaban as $soalId => $jawabanValue) {
                Jawaban::updateOrCreate(
                    [
                        'soal_id' => $soalId,
                        'user_id' => $user->id,
                    ],
                    [
                        'jawaban' => is_array($jawabanValue) ? json_encode($jawabanValue) : $jawabanValue,
                        'status' => 'submitted',
                    ]
                );
            }
            
            // Calculate score
            $totalSoal = $ujian->soal->count();
            $benarCount = 0;
            
            foreach ($ujian->soal as $soal) {
                $userJawaban = Jawaban::where('soal_id', $soal->id)
                    ->where('user_id', $user->id)
                    ->first();
                
                if (!$userJawaban) continue;
                
                // Get correct answer IDs
                $correctAnswerIds = $soal->pilihanJawaban()
                    ->where('is_correct', true)
                    ->pluck('id')
                    ->map(function($id) {
                        return (string)$id;
                    })
                    ->toArray();
                
                // Check if answer is correct
                if ($soal->tipe_soal === 'multiple') {
                    // For multiple choice, decode JSON
                    $userAnswerIds = json_decode($userJawaban->jawaban, true);
                    if (is_array($userAnswerIds)) {
                        // Convert to string for comparison
                        $userAnswerIds = array_map('strval', $userAnswerIds);
                        sort($userAnswerIds);
                        sort($correctAnswerIds);
                        if ($userAnswerIds == $correctAnswerIds) {
                            $benarCount++;
                        }
                    }
                } else {
                    // For single choice
                    if (in_array($userJawaban->jawaban, $correctAnswerIds)) {
                        $benarCount++;
                    }
                }
            }
            
            $nilai = $totalSoal > 0 ? ($benarCount / $totalSoal) * 100 : 0;
            
            // Save score
            Nilai::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'ujian_id' => $ujian->id,
                ],
                [
                    'nilai' => $nilai,
                    'status' => 'completed',
                    'tanggal_penilaian' => now(),
                ]
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Ujian berhasil diselesaikan!'
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan ujian: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Show result page
     */
    public function result($id)
    {
        $user = Auth::user();
        $ujian = Ujian::with(['soal', 'kursus'])->findOrFail($id);
        
        // Get score
        $nilai = Nilai::where('user_id', $user->id)
            ->where('ujian_id', $ujian->id)
            ->first();
        
        if (!$nilai) {
            return redirect()->route('admin.ujian.show', $id)
                ->with('error', 'Anda belum menyelesaikan ujian ini.');
        }
        
        // Calculate statistics
        $totalSoal = $ujian->soal->count();
        $jawaban = Jawaban::where('user_id', $user->id)
            ->whereIn('soal_id', $ujian->soal->pluck('id'))
            ->get();
        
        $benarCount = 0;
        $salahCount = 0;
        
        foreach ($ujian->soal as $soal) {
            $userJawaban = $jawaban->where('soal_id', $soal->id)->first();
            
            if (!$userJawaban) {
                $salahCount++;
                continue;
            }
            
            // Get correct answer IDs
            $correctAnswerIds = $soal->pilihanJawaban()
                ->where('is_correct', true)
                ->pluck('id')
                ->map(function($id) {
                    return (string)$id;
                })
                ->toArray();
            
            // Check if answer is correct
            $isCorrect = false;
            if ($soal->tipe_soal === 'multiple') {
                $userAnswerIds = json_decode($userJawaban->jawaban, true);
                if (is_array($userAnswerIds)) {
                    // Convert to string for comparison
                    $userAnswerIds = array_map('strval', $userAnswerIds);
                    sort($userAnswerIds);
                    sort($correctAnswerIds);
                    $isCorrect = ($userAnswerIds == $correctAnswerIds);
                }
            } else {
                $isCorrect = in_array($userJawaban->jawaban, $correctAnswerIds);
            }
            
            if ($isCorrect) {
                $benarCount++;
            } else {
                $salahCount++;
            }
        }
        
        return view('user.ujian.result', compact('ujian', 'nilai', 'totalSoal', 'benarCount', 'salahCount'));
    }
}
