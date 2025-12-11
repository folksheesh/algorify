# Algorify - AI Coding Instructions

## Project Overview
Algorify is an Indonesian online learning management system (LMS) built with **Laravel 11**, **Tailwind CSS**, and **Alpine.js**. It supports course enrollment, video/reading content, quizzes, certificates, and DOKU payment integration.

## Architecture

### Role-Based Access Control
Uses **Spatie Laravel Permission** with 4 roles:
- `super admin` - Full system access, manages admins
- `admin` - Manages courses, instructors, students
- `pengajar` (instructor) - Creates/manages own courses
- `peserta` (student) - Enrolls in courses, takes quizzes

### Key Domain Models
```
User (string UUID PK, uses HasRoles trait)
├── Kursus (courses) - belongs to pengajar
│   ├── Modul (modules, ordered by 'urutan')
│   │   ├── Video (ordered by 'urutan')
│   │   ├── Materi (reading content, ordered by 'urutan')
│   │   └── Ujian (exams/quizzes)
│   │       └── Soal → PilihanJawaban (questions/answers)
│   └── Enrollment → Transaksi (payments)
└── Sertifikat (certificates)
```

### Data Patterns
- **User IDs are UUIDs (string)** - not auto-incrementing integers
- Tables use Indonesian naming: `kursus`, `modul`, `ujian`, `soal`, `nilai`, `sertifikat`
- Ordering via `urutan` column on modul, video, materi tables
- Progress tracking via `user_progress` table (see `ProgressRepository`)

## Key Conventions

### Controllers
- **Admin controllers** in `app/Http/Controllers/Admin/` - use `getData()` method for paginated JSON responses
- **User controllers** in `app/Http/Controllers/User/` - student-facing features
- **API controllers** in `app/Http/Controllers/Api/` - Sanctum-authenticated endpoints

### Repository Pattern
- `ProgressRepository` handles all progress calculations for videos, materi, quizzes
- Video completion: watched ≥95% of duration
- Quiz completion: score ≥ passing_grade

### Views Structure
```
resources/views/
├── admin/          # Admin panel views
├── user/           # Student dashboard views
├── pengajar/       # Instructor views
├── layouts/
│   └── app.blade.php    # Main authenticated layout
└── components/     # Blade components
```

### Frontend Stack
- **Mazer** admin template in `public/template/`
- Uses `@stack('scripts')` and `@stack('styles')` for page-specific assets
- TinyMCE for rich text editing (materi content)
- Simple DataTables via AJAX `getData()` endpoints

## Developer Workflows

### Run Development Server
```bash
php artisan serve
npm run dev          # Vite dev server for assets
```

### Database Operations
```bash
php artisan migrate
php artisan db:seed                    # Full seeding
php artisan db:seed --class=AdminSeeder  # Specific seeder
```

### Testing
```bash
php artisan test                       # Run all tests
php artisan test --filter=FeatureName  # Specific test
```
Tests use SQLite in-memory database.

### Key Artisan Commands
```bash
php artisan permission:cache-reset  # After role/permission changes
php artisan storage:link            # Link storage for uploads
```

## Payment Integration (DOKU)
- Config in `config/doku.php`, credentials via `.env`
- `DokuSignatureService` generates HMAC-SHA256 signatures
- Callback routes are public (no auth): `/payment/callback`, `/doku/notification`

## Import/Export Features
- `SoalImport`/`SoalExport` for quiz questions via Maatwebsite Excel
- `BankSoalController` supports CSV import/export for question bank
- Template downloads available at `/admin/soal/template`

## Common Patterns

### Adding a New Admin Feature
1. Create model in `app/Models/`
2. Create migration in `database/migrations/`
3. Create controller in `app/Http/Controllers/Admin/`
4. Add routes in `routes/web.php` under admin middleware group
5. Create views in `resources/views/admin/{feature}/`

### Role Middleware Usage
```php
Route::middleware('role:admin|super admin|pengajar')->group(function () {
    // Routes accessible by these roles
});
```

### JSON Response Pattern for DataTables
```php
public function getData(Request $request)
{
    $query = Model::query();
    if ($request->filled('search')) {
        $query->where('name', 'like', "%{$request->search}%");
    }
    return response()->json($query->paginate(10));
}
```

## Gotchas
- User `id` is string UUID, always use `$user->id` not numeric casts
- Indonesian field names throughout (judul, deskripsi, tanggal_mulai, etc.)
- Kursus `user_id` refers to the `pengajar` (instructor), not student
- Enrollment auto-generates `kode` with prefix `ENR-`
