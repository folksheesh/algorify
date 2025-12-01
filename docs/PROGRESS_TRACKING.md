# Sistem Progress Tracking Peserta

## Overview
Sistem ini melacak progress pembelajaran peserta dalam kursus online. Progress dihitung berdasarkan completion status dari berbagai item pembelajaran:
- **Video** - Ditandai selesai jika ditonton sampai tersisa < 10 detik
- **Materi Bacaan** - Ditandai selesai dengan tombol "Tandai Sudah Dibaca"
- **Quiz** - Ditandai selesai jika lulus (score >= passing_grade)
- **Ujian** - Sama seperti quiz

## Database Structure

### Tabel: user_progress
```sql
- id: bigint (PK)
- user_id: FK ke users
- kursus_id: FK ke kursus
- item_type: enum ('video', 'materi', 'quiz', 'ujian')
- item_id: bigint (ID dari video/materi/ujian)
- status: enum ('not_started', 'in_progress', 'completed')
- watch_time: int (untuk video, dalam detik)
- total_duration: int (durasi total video)
- score: decimal (untuk quiz/ujian)
- passed: boolean
- completed_at: timestamp
```

## API Endpoints

### 1. Update Video Progress
**POST** `/user/course/video/progress`

Request:
```json
{
    "video_id": 1,
    "watch_time": 120,
    "total_duration": 600
}
```

Response:
```json
{
    "success": true,
    "message": "Video selesai ditonton!",
    "data": {
        "item_type": "video",
        "item_id": 1,
        "status": "completed",
        "completed": true,
        "watch_time": 595,
        "total_duration": 600,
        "show_checklist": true
    },
    "course_progress": {
        "total_items": 10,
        "completed_items": 5,
        "percentage": 50.0,
        "is_completed": false
    }
}
```

### 2. Mark Reading Completed
**POST** `/user/course/reading/complete`

Request:
```json
{
    "materi_id": 1
}
```

### 3. Submit Quiz Score
**POST** `/user/course/quiz/score`

Request:
```json
{
    "quiz_id": 1,
    "score": 85.5
}
```

### 4. Submit Exam Score
**POST** `/user/course/exam/score`

Request:
```json
{
    "ujian_id": 1,
    "score": 90.0
}
```

### 5. Get Course Progress
**GET** `/user/course/{kursusId}/progress`

Response:
```json
{
    "success": true,
    "data": {
        "summary": {
            "total_items": 10,
            "completed_items": 5,
            "percentage": 50.0,
            "is_completed": false
        },
        "breakdown": {
            "videos": {"total": 4, "completed": 2, "items": [...]},
            "materi": {"total": 3, "completed": 2, "items": [...]},
            "quiz": {"total": 2, "completed": 1, "items": [...]},
            "ujian": {"total": 1, "completed": 0, "items": [...]}
        }
    }
}
```

### 6. Get Completed Items
**GET** `/user/course/{kursusId}/completed-items`

### 7. Get Item Status
**GET** `/user/course/item/{type}/{id}/status`

## Komponen Kode

### ProgressRepository
Lokasi: `app/Repositories/ProgressRepository.php`

Fungsi utama:
- `getOrCreate()` - Get atau create progress record
- `updateVideoProgress()` - Update progress video
- `markMateriCompleted()` - Mark materi sebagai selesai
- `updateQuizProgress()` - Update progress quiz
- `updateUjianProgress()` - Update progress ujian
- `calculateProgress()` - Hitung persentase progress
- `getDetailedProgress()` - Get breakdown lengkap
- `recalculateCourseProgress()` - Update enrollment progress

### ProgressController
Lokasi: `app/Http/Controllers/User/ProgressController.php`

Endpoint handlers untuk semua API di atas.

### UserProgress Model
Lokasi: `app/Models/UserProgress.php`

Eloquent model untuk tabel user_progress.

## Frontend Integration

### Video Tracking
JavaScript otomatis save progress setiap 10 detik saat video diputar. Ketika video hampir selesai (< 10 detik tersisa) atau video ended, akan di-mark sebagai completed.

```javascript
videoElement.addEventListener('ended', function() {
    // Auto-mark completed when video ends
});
```

### Reading Tracking
User menekan tombol "Tandai Sudah Dibaca" untuk mark completion.

```javascript
fetch('/user/course/reading/complete', {
    method: 'POST',
    body: JSON.stringify({ materi_id: materiId })
});
```

### Quiz/Ujian Tracking
Otomatis di-track setelah submit jawaban di UjianController.

## UI Indicators

- **Checklist Icon**: Muncul di navigasi sidebar untuk item yang sudah selesai
- **Completion Badge**: Muncul di header video/materi setelah selesai
- **Progress Bar**: Menampilkan persentase progress kursus

## Perhitungan Progress

```
Total Progress = (Completed Items / Total Items) × 100%

Total Items = Videos + Materi + Quiz + Ujian dalam kursus
Completed Items = Items dengan status "completed"
```

Progress disimpan ke tabel `enrollment.progress` dan auto-update setiap ada completion.
