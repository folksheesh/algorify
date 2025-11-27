<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\ProgressRepository;
use App\Models\UserProgress;
use App\Models\Video;
use App\Models\Materi;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    protected ProgressRepository $progressRepository;

    public function __construct(ProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    /**
     * Update video watch progress
     * POST /course/video/progress
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function updateVideoProgress(Request $request): JsonResponse
    {
        $request->validate([
            'video_id' => 'required|exists:video,id',
            'watch_time' => 'required|integer|min:0',
            'total_duration' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $videoId = $request->video_id;
        $watchTime = $request->watch_time;
        $totalDuration = $request->total_duration;

        try {
            // Update video progress
            $progress = $this->progressRepository->updateVideoProgress(
                $userId,
                $videoId,
                $watchTime,
                $totalDuration
            );

            // Get video to find kursus_id
            $video = Video::with('modul')->find($videoId);
            $kursusId = $video->modul->kursus_id;

            // Get updated course progress
            $courseProgress = $this->progressRepository->calculateProgress($userId, $kursusId);

            return response()->json([
                'success' => true,
                'message' => $progress->isCompleted() 
                    ? 'Video selesai ditonton!' 
                    : 'Progress video diupdate',
                'data' => [
                    'item_type' => 'video',
                    'item_id' => $videoId,
                    'status' => $progress->status,
                    'completed' => $progress->isCompleted(),
                    'watch_time' => $progress->watch_time,
                    'total_duration' => $progress->total_duration,
                    'show_checklist' => $progress->isCompleted(),
                ],
                'course_progress' => $courseProgress,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate progress video: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark reading material as completed
     * POST /course/reading/complete
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function markReadingCompleted(Request $request): JsonResponse
    {
        $request->validate([
            'materi_id' => 'required|exists:materi,id',
        ]);

        $userId = Auth::id();
        $materiId = $request->materi_id;

        try {
            // Mark materi as completed
            $progress = $this->progressRepository->markMateriCompleted($userId, $materiId);

            // Get materi to find kursus_id
            $materi = Materi::with('modul')->find($materiId);
            $kursusId = $materi->modul->kursus_id;

            // Get updated course progress
            $courseProgress = $this->progressRepository->calculateProgress($userId, $kursusId);

            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil ditandai sudah dibaca!',
                'data' => [
                    'item_type' => 'materi',
                    'item_id' => $materiId,
                    'status' => $progress->status,
                    'completed' => true,
                    'show_checklist' => true,
                    'completed_at' => $progress->completed_at->toISOString(),
                ],
                'course_progress' => $courseProgress,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai materi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Submit quiz score and update progress
     * POST /course/quiz/score
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function submitQuizScore(Request $request): JsonResponse
    {
        $request->validate([
            'quiz_id' => 'required|exists:ujian,id',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $userId = Auth::id();
        $quizId = $request->quiz_id;
        $score = $request->score;

        try {
            // Get quiz to determine passing grade
            $quiz = Ujian::findOrFail($quizId);
            $passingGrade = $quiz->minimum_score ?? 70;

            // Update quiz progress
            $progress = $this->progressRepository->updateQuizProgress(
                $userId,
                $quizId,
                $score,
                $passingGrade
            );

            // Get updated course progress
            $kursusId = $quiz->modul->kursus_id;
            $courseProgress = $this->progressRepository->calculateProgress($userId, $kursusId);

            $passed = $score >= $passingGrade;

            return response()->json([
                'success' => true,
                'message' => $passed 
                    ? 'Selamat! Anda lulus quiz dengan nilai ' . $score 
                    : 'Anda belum lulus. Nilai: ' . $score . ', Minimum: ' . $passingGrade,
                'data' => [
                    'item_type' => 'quiz',
                    'item_id' => $quizId,
                    'status' => $progress->status,
                    'completed' => $progress->isCompleted(),
                    'score' => $score,
                    'passing_grade' => $passingGrade,
                    'passed' => $passed,
                    'show_checklist' => $passed,
                ],
                'course_progress' => $courseProgress,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nilai quiz: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Submit exam score and update progress
     * POST /course/exam/score
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function submitExamScore(Request $request): JsonResponse
    {
        $request->validate([
            'ujian_id' => 'required|exists:ujian,id',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $userId = Auth::id();
        $ujianId = $request->ujian_id;
        $score = $request->score;

        try {
            // Get ujian to determine passing grade
            $ujian = Ujian::findOrFail($ujianId);
            $passingGrade = $ujian->minimum_score ?? 70;

            // Update ujian progress
            $progress = $this->progressRepository->updateUjianProgress(
                $userId,
                $ujianId,
                $score,
                $passingGrade
            );

            // Get updated course progress
            $kursusId = $ujian->modul->kursus_id;
            $courseProgress = $this->progressRepository->calculateProgress($userId, $kursusId);

            $passed = $score >= $passingGrade;

            return response()->json([
                'success' => true,
                'message' => $passed 
                    ? 'Selamat! Anda lulus ujian dengan nilai ' . $score 
                    : 'Anda belum lulus. Nilai: ' . $score . ', Minimum: ' . $passingGrade,
                'data' => [
                    'item_type' => 'ujian',
                    'item_id' => $ujianId,
                    'status' => $progress->status,
                    'completed' => $progress->isCompleted(),
                    'score' => $score,
                    'passing_grade' => $passingGrade,
                    'passed' => $passed,
                    'show_checklist' => $passed,
                ],
                'course_progress' => $courseProgress,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nilai ujian: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get overall course progress
     * GET /course/{kursusId}/progress
     * 
     * @param int $kursusId
     * @return JsonResponse
     */
    public function getCourseProgress(int $kursusId): JsonResponse
    {
        $userId = Auth::id();

        try {
            $progress = $this->progressRepository->calculateProgress($userId, $kursusId);
            $detailed = $this->progressRepository->getDetailedProgress($userId, $kursusId);

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => $progress,
                    'breakdown' => $detailed,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil progress: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get completed items for a course
     * GET /course/{kursusId}/completed-items
     * 
     * @param int $kursusId
     * @return JsonResponse
     */
    public function getCompletedItems(int $kursusId): JsonResponse
    {
        $userId = Auth::id();

        try {
            $completedItems = $this->progressRepository->getCompletedItems($userId, $kursusId);

            return response()->json([
                'success' => true,
                'data' => $completedItems,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if specific item is completed
     * GET /course/item/{type}/{id}/status
     * 
     * @param string $type
     * @param int $id
     * @return JsonResponse
     */
    public function getItemStatus(string $type, int $id): JsonResponse
    {
        $userId = Auth::id();
        
        // Validate type
        if (!in_array($type, ['video', 'materi', 'quiz', 'ujian'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe item tidak valid',
            ], 400);
        }

        try {
            $progress = $this->progressRepository->getItemProgress($userId, $type, $id);

            return response()->json([
                'success' => true,
                'data' => [
                    'item_type' => $type,
                    'item_id' => $id,
                    'status' => $progress ? $progress->status : 'not_started',
                    'completed' => $progress ? $progress->isCompleted() : false,
                    'show_checklist' => $progress ? $progress->isCompleted() : false,
                    'score' => $progress->score ?? null,
                    'watch_time' => $progress->watch_time ?? null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
