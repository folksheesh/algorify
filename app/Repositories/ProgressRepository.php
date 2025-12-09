<?php

namespace App\Repositories;

use App\Models\UserProgress;
use App\Models\Enrollment;
use App\Models\Video;
use App\Models\Materi;
use App\Models\Ujian;
use App\Models\Kursus;
use Illuminate\Support\Facades\DB;

class ProgressRepository
{
    /**
     * Get or create progress record for a user and item
     */
    public function getOrCreate(string $userId, int $kursusId, string $itemType, int $itemId): UserProgress
    {
        return UserProgress::firstOrCreate(
            [
                'user_id' => $userId,
                'item_type' => $itemType,
                'item_id' => $itemId,
            ],
            [
                'kursus_id' => $kursusId,
                'status' => UserProgress::STATUS_NOT_STARTED,
            ]
        );
    }

    /**
     * Update video progress
     */
    public function updateVideoProgress(string $userId, int $videoId, int $watchTime, int $totalDuration): UserProgress
    {
        $video = Video::with('modul')->findOrFail($videoId);
        $kursusId = $video->modul->kursus_id;
        
        $progress = $this->getOrCreate($userId, $kursusId, UserProgress::TYPE_VIDEO, $videoId);
        
        $progress->watch_time = max($progress->watch_time ?? 0, $watchTime);
        $progress->total_duration = $totalDuration;
        
        // Jika sudah menonton 95% dari durasi video, mark as completed
        $watchedPercentage = $totalDuration > 0 ? ($watchTime / $totalDuration) : 0;
        if ($watchedPercentage >= 0.95) {
            $progress->status = UserProgress::STATUS_COMPLETED;
            $progress->completed_at = now();
        } else {
            $progress->status = UserProgress::STATUS_IN_PROGRESS;
        }
        
        $progress->save();
        
        // Update overall course progress
        $this->recalculateCourseProgress($userId, $kursusId);
        
        return $progress;
    }

    /**
     * Mark materi/bacaan as completed
     */
    public function markMateriCompleted(string $userId, int $materiId): UserProgress
    {
        $materi = Materi::with('modul')->findOrFail($materiId);
        $kursusId = $materi->modul->kursus_id;
        
        $progress = $this->getOrCreate($userId, $kursusId, UserProgress::TYPE_MATERI, $materiId);
        
        $progress->status = UserProgress::STATUS_COMPLETED;
        $progress->completed_at = now();
        $progress->save();
        
        // Update overall course progress
        $this->recalculateCourseProgress($userId, $kursusId);
        
        return $progress;
    }

    /**
     * Update quiz progress based on score
     */
    public function updateQuizProgress(string $userId, int $quizId, float $score, float $passingGrade): UserProgress
    {
        $quiz = Ujian::with('modul')->findOrFail($quizId);
        $kursusId = $quiz->modul->kursus_id;
        
        $progress = $this->getOrCreate($userId, $kursusId, UserProgress::TYPE_QUIZ, $quizId);
        
        $progress->score = $score;
        $progress->passed = $score >= $passingGrade;
        
        if ($progress->passed) {
            $progress->status = UserProgress::STATUS_COMPLETED;
            $progress->completed_at = now();
        } else {
            $progress->status = UserProgress::STATUS_IN_PROGRESS;
        }
        
        $progress->save();
        
        // Update overall course progress
        $this->recalculateCourseProgress($userId, $kursusId);
        
        return $progress;
    }

    /**
     * Update exam/ujian progress based on score
     */
    public function updateUjianProgress(string $userId, int $ujianId, float $score, float $passingGrade): UserProgress
    {
        $ujian = Ujian::with('modul')->findOrFail($ujianId);
        $kursusId = $ujian->modul->kursus_id;
        
        $progress = $this->getOrCreate($userId, $kursusId, UserProgress::TYPE_UJIAN, $ujianId);
        
        $progress->score = $score;
        $progress->passed = $score >= $passingGrade;
        
        if ($progress->passed) {
            $progress->status = UserProgress::STATUS_COMPLETED;
            $progress->completed_at = now();
        } else {
            $progress->status = UserProgress::STATUS_IN_PROGRESS;
        }
        
        $progress->save();
        
        // Update overall course progress
        $this->recalculateCourseProgress($userId, $kursusId);
        
        return $progress;
    }

    /**
     * Calculate progress percentage for a course
     */
    public function calculateProgress(string $userId, int $kursusId): array
    {
        // Get all items in the course
        $totalItems = $this->getTotalCourseItems($kursusId);
        
        // Get completed items
        $completedItems = UserProgress::forUser($userId)
            ->forKursus($kursusId)
            ->completed()
            ->count();
        
        // Calculate percentage
        $percentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100, 1) : 0;
        
        return [
            'total_items' => $totalItems,
            'completed_items' => $completedItems,
            'percentage' => $percentage,
            'is_completed' => $percentage >= 100,
        ];
    }

    /**
     * Get total number of items in a course
     */
    public function getTotalCourseItems(int $kursusId): int
    {
        $kursus = Kursus::with(['modul.video', 'modul.materi', 'modul.ujian'])->find($kursusId);
        
        if (!$kursus) {
            return 0;
        }
        
        $totalVideos = 0;
        $totalMateri = 0;
        $totalQuiz = 0;
        $totalUjian = 0;
        
        foreach ($kursus->modul as $modul) {
            $totalVideos += $modul->video->count();
            $totalMateri += $modul->materi->count();
            
            foreach ($modul->ujian as $ujian) {
                if ($ujian->tipe === 'practice') {
                    $totalQuiz++;
                } else {
                    $totalUjian++;
                }
            }
        }
        
        return $totalVideos + $totalMateri + $totalQuiz + $totalUjian;
    }

    /**
     * Get detailed progress breakdown
     */
    public function getDetailedProgress(string $userId, int $kursusId): array
    {
        $kursus = Kursus::with(['modul.video', 'modul.materi', 'modul.ujian'])->find($kursusId);
        
        if (!$kursus) {
            return [];
        }
        
        $userProgress = UserProgress::forUser($userId)
            ->forKursus($kursusId)
            ->get()
            ->keyBy(fn($p) => $p->item_type . '_' . $p->item_id);
        
        $breakdown = [
            'videos' => ['total' => 0, 'completed' => 0, 'items' => []],
            'materi' => ['total' => 0, 'completed' => 0, 'items' => []],
            'quiz' => ['total' => 0, 'completed' => 0, 'items' => []],
            'ujian' => ['total' => 0, 'completed' => 0, 'items' => []],
        ];
        
        foreach ($kursus->modul as $modul) {
            // Videos
            foreach ($modul->video as $video) {
                $key = 'video_' . $video->id;
                $progress = $userProgress->get($key);
                $isCompleted = $progress && $progress->isCompleted();
                
                $breakdown['videos']['total']++;
                if ($isCompleted) $breakdown['videos']['completed']++;
                
                $breakdown['videos']['items'][] = [
                    'id' => $video->id,
                    'judul' => $video->judul,
                    'modul' => $modul->judul,
                    'completed' => $isCompleted,
                    'watch_time' => $progress->watch_time ?? 0,
                    'total_duration' => $progress->total_duration ?? 0,
                ];
            }
            
            // Materi
            foreach ($modul->materi as $materi) {
                $key = 'materi_' . $materi->id;
                $progress = $userProgress->get($key);
                $isCompleted = $progress && $progress->isCompleted();
                
                $breakdown['materi']['total']++;
                if ($isCompleted) $breakdown['materi']['completed']++;
                
                $breakdown['materi']['items'][] = [
                    'id' => $materi->id,
                    'judul' => $materi->judul,
                    'modul' => $modul->judul,
                    'completed' => $isCompleted,
                ];
            }
            
            // Quiz & Ujian
            foreach ($modul->ujian as $ujian) {
                $type = $ujian->tipe === 'practice' ? 'quiz' : 'ujian';
                $key = $type . '_' . $ujian->id;
                $progress = $userProgress->get($key);
                $isCompleted = $progress && $progress->isCompleted();
                
                $breakdown[$type]['total']++;
                if ($isCompleted) $breakdown[$type]['completed']++;
                
                $breakdown[$type]['items'][] = [
                    'id' => $ujian->id,
                    'judul' => $ujian->judul,
                    'modul' => $modul->judul,
                    'completed' => $isCompleted,
                    'score' => $progress->score ?? null,
                    'passed' => $progress->passed ?? null,
                ];
            }
        }
        
        return $breakdown;
    }

    /**
     * Recalculate and update course progress in enrollment table
     */
    public function recalculateCourseProgress(string $userId, int $kursusId): void
    {
        $progressData = $this->calculateProgress($userId, $kursusId);
        
        // Update enrollment progress
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('kursus_id', $kursusId)
            ->first();
        
        if ($enrollment) {
            $enrollment->progress = (int) $progressData['percentage'];
            
            // If 100% completed, mark enrollment as completed
            if ($progressData['is_completed']) {
                $enrollment->status = 'completed';
            }
            
            $enrollment->save();
        }
    }

    /**
     * Get user's progress for a specific item
     */
    public function getItemProgress(string $userId, string $itemType, int $itemId): ?UserProgress
    {
        return UserProgress::forUser($userId)
            ->ofType($itemType)
            ->where('item_id', $itemId)
            ->first();
    }

    /**
     * Check if an item is completed by user
     */
    public function isItemCompleted(string $userId, string $itemType, int $itemId): bool
    {
        $progress = $this->getItemProgress($userId, $itemType, $itemId);
        return $progress && $progress->isCompleted();
    }

    /**
     * Get all completed items for a user in a course
     */
    public function getCompletedItems(string $userId, int $kursusId): array
    {
        $completed = UserProgress::forUser($userId)
            ->forKursus($kursusId)
            ->completed()
            ->get();
        
        return $completed->map(function ($item) {
            return [
                'type' => $item->item_type,
                'id' => $item->item_id,
                'completed_at' => $item->completed_at,
            ];
        })->toArray();
    }
}
