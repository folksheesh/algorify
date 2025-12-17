<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kursus;
use App\Models\Modul;
use App\Models\Video;
use App\Models\Materi;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use App\Models\User;

class PelatihanLengkapSeeder extends Seeder
{
    /**
     * Seeder lengkap untuk pelatihan dengan:
     * - Sub Modul
     * - Video
     * - Bacaan (Materi)
     * - Quiz
     * - Ujian Akhir
     */
    public function run(): void
    {
        $pengajar = User::role('pengajar')->first() ?? User::first();

        if (!$pengajar) {
            $this->command->error('Tidak ada user. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // ============================================
        // KURSUS 1: FULLSTACK LARAVEL
        // ============================================
        $kursusLaravel = Kursus::create([
            'judul' => 'Fullstack Laravel Development',
            'deskripsi' => 'Pelajari Laravel dari dasar hingga mahir. Bangun aplikasi web fullstack dengan Laravel, MySQL, dan Tailwind CSS.',
            'deskripsi_singkat' => 'Belajar Laravel dari dasar hingga mahir',
            'kategori' => 'programming',
            'user_id' => $pengajar->id,
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonths(3),
            'status' => 'published',
            'harga' => 750000,
            'durasi' => '12 Minggu',
            'tipe_kursus' => 'online',
            'thumbnail' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=900&q=80',
        ]);

        // MODUL 1: Pengenalan Laravel
        $modul1 = Modul::create([
            'kursus_id' => $kursusLaravel->id,
            'judul' => 'Pengenalan Laravel',
            'deskripsi' => 'Memahami dasar-dasar Laravel dan ekosistemnya',
            'urutan' => 1,
        ]);

        // Video untuk Modul 1
        Video::create([
            'modul_id' => $modul1->id,
            'judul' => 'Apa itu Laravel?',
            'deskripsi' => 'Pengenalan framework Laravel dan keunggulannya',
            'file_video' => 'https://www.youtube.com/watch?v=ImtZ5yENzgE',
            'urutan' => 1,
        ]);
        Video::create([
            'modul_id' => $modul1->id,
            'judul' => 'Instalasi Laravel',
            'deskripsi' => 'Cara install Laravel menggunakan Composer',
            'file_video' => 'https://www.youtube.com/watch?v=MFh0Fd7BsjE',
            'urutan' => 2,
        ]);

        // Bacaan untuk Modul 1
        Materi::create([
            'modul_id' => $modul1->id,
            'judul' => 'Struktur Folder Laravel',
            'deskripsi' => 'Memahami struktur folder dalam project Laravel',
            'konten' => '<h1>Struktur Folder Laravel</h1>
<p>Laravel memiliki struktur folder yang terorganisir dengan baik untuk memudahkan pengembangan aplikasi.</p>

<h2>Folder Utama</h2>
<ul>
<li><strong>app/</strong> - Berisi kode aplikasi (Models, Controllers, dll)</li>
<li><strong>bootstrap/</strong> - File bootstrap framework</li>
<li><strong>config/</strong> - Semua file konfigurasi</li>
<li><strong>database/</strong> - Migrations, seeders, factories</li>
<li><strong>public/</strong> - Entry point dan assets publik</li>
<li><strong>resources/</strong> - Views, CSS, JS mentah</li>
<li><strong>routes/</strong> - Definisi semua routes</li>
<li><strong>storage/</strong> - File generated, logs, cache</li>
<li><strong>tests/</strong> - Unit dan feature tests</li>
<li><strong>vendor/</strong> - Dependencies Composer</li>
</ul>

<h2>Folder app/</h2>
<pre><code>app/
├── Console/        # Artisan commands
├── Exceptions/     # Exception handlers
├── Http/
│   ├── Controllers/  # Controllers
│   ├── Middleware/   # HTTP middlewares
│   └── Requests/     # Form requests
├── Models/         # Eloquent models
└── Providers/      # Service providers</code></pre>

<h2>Tips</h2>
<blockquote>
Gunakan perintah <code>php artisan</code> untuk generate file-file baru seperti controller, model, migration, dll.
</blockquote>',
            'urutan' => 1,
        ]);

        // Quiz untuk Modul 1
        $quiz1 = Ujian::create([
            'kursus_id' => $kursusLaravel->id,
            'modul_id' => $modul1->id,
            'judul' => 'Quiz: Pengenalan Laravel',
            'deskripsi' => 'Test pemahaman dasar Laravel',
            'tipe' => 'practice',
            'waktu_pengerjaan' => 10,
            'minimum_score' => 70,
        ]);

        $this->createSoalWithPilihan($quiz1, [
            [
                'pertanyaan' => 'Laravel adalah framework untuk bahasa pemrograman apa?',
                'tipe_soal' => 'single',
                'pilihan' => ['Python', 'PHP', 'JavaScript', 'Ruby'],
                'jawaban_benar' => 1,
                'pembahasan' => 'Laravel adalah framework PHP yang populer untuk web development.',
            ],
            [
                'pertanyaan' => 'Perintah untuk membuat project Laravel baru adalah?',
                'tipe_soal' => 'single',
                'pilihan' => ['npm create laravel', 'composer create-project laravel/laravel', 'laravel new project', 'php install laravel'],
                'jawaban_benar' => 1,
                'pembahasan' => 'Gunakan composer create-project laravel/laravel nama-project untuk membuat project Laravel baru.',
            ],
            [
                'pertanyaan' => 'Folder mana yang berisi file views/template?',
                'tipe_soal' => 'single',
                'pilihan' => ['app/', 'public/', 'resources/', 'storage/'],
                'jawaban_benar' => 2,
                'pembahasan' => 'Folder resources/ berisi views, CSS, dan JavaScript mentah.',
            ],
        ]);

        // MODUL 2: Routing & Controller
        $modul2 = Modul::create([
            'kursus_id' => $kursusLaravel->id,
            'judul' => 'Routing & Controller',
            'deskripsi' => 'Memahami sistem routing dan controller di Laravel',
            'urutan' => 2,
        ]);

        Video::create([
            'modul_id' => $modul2->id,
            'judul' => 'Dasar Routing Laravel',
            'deskripsi' => 'Cara mendefinisikan routes di Laravel',
            'file_video' => 'https://www.youtube.com/watch?v=routing1',
            'urutan' => 1,
        ]);
        Video::create([
            'modul_id' => $modul2->id,
            'judul' => 'Membuat Controller',
            'deskripsi' => 'Membuat dan menggunakan controller',
            'file_video' => 'https://www.youtube.com/watch?v=controller1',
            'urutan' => 2,
        ]);
        Video::create([
            'modul_id' => $modul2->id,
            'judul' => 'Resource Controller',
            'deskripsi' => 'CRUD dengan resource controller',
            'file_video' => 'https://www.youtube.com/watch?v=resource1',
            'urutan' => 3,
        ]);

        Materi::create([
            'modul_id' => $modul2->id,
            'judul' => 'Panduan Routing Laravel',
            'deskripsi' => 'Referensi lengkap routing Laravel',
            'konten' => '<h1>Routing di Laravel</h1>
<p>Routes adalah penghubung antara URL dan logic aplikasi.</p>

<h2>Basic Routing</h2>
<pre><code>// routes/web.php
Route::get("/", function () {
    return view("welcome");
});

Route::get("/about", function () {
    return "About Page";
});</code></pre>

<h2>Route dengan Controller</h2>
<pre><code>Route::get("/users", [UserController::class, "index"]);
Route::post("/users", [UserController::class, "store"]);
Route::get("/users/{id}", [UserController::class, "show"]);</code></pre>

<h2>Resource Route</h2>
<pre><code>// Membuat 7 routes CRUD sekaligus
Route::resource("posts", PostController::class);</code></pre>

<h2>Route Parameters</h2>
<pre><code>Route::get("/user/{id}", function ($id) {
    return "User " . $id;
});

// Optional parameter
Route::get("/user/{name?}", function ($name = "Guest") {
    return "Hello " . $name;
});</code></pre>

<h2>Named Routes</h2>
<pre><code>Route::get("/profile", [ProfileController::class, "show"])->name("profile");

// Menggunakan named route
return redirect()->route("profile");</code></pre>',
            'urutan' => 1,
        ]);

        $quiz2 = Ujian::create([
            'kursus_id' => $kursusLaravel->id,
            'modul_id' => $modul2->id,
            'judul' => 'Quiz: Routing & Controller',
            'deskripsi' => 'Test pemahaman routing dan controller',
            'tipe' => 'practice',
            'waktu_pengerjaan' => 15,
            'minimum_score' => 70,
        ]);

        $this->createSoalWithPilihan($quiz2, [
            [
                'pertanyaan' => 'File routes untuk web browser ada di?',
                'tipe_soal' => 'single',
                'pilihan' => ['routes/api.php', 'routes/web.php', 'routes/console.php', 'app/routes.php'],
                'jawaban_benar' => 1,
                'pembahasan' => 'routes/web.php untuk web routes, routes/api.php untuk API routes.',
            ],
            [
                'pertanyaan' => 'Perintah artisan untuk membuat controller adalah?',
                'tipe_soal' => 'single',
                'pilihan' => ['php artisan create:controller', 'php artisan make:controller', 'php artisan generate:controller', 'php artisan new:controller'],
                'jawaban_benar' => 1,
                'pembahasan' => 'Gunakan php artisan make:controller NamaController.',
            ],
            [
                'pertanyaan' => 'HTTP method yang digunakan untuk menghapus data adalah?',
                'tipe_soal' => 'single',
                'pilihan' => ['GET', 'POST', 'PUT', 'DELETE'],
                'jawaban_benar' => 3,
                'pembahasan' => 'DELETE method digunakan untuk menghapus resource.',
            ],
            [
                'pertanyaan' => 'Pilih HTTP methods yang didukung Laravel (pilih semua yang benar):',
                'tipe_soal' => 'multiple',
                'pilihan' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                'jawaban_benar' => [0, 1, 2, 3, 4, 5],
                'pembahasan' => 'Laravel mendukung semua HTTP methods standar.',
            ],
        ]);

        // MODUL 3: Eloquent ORM
        $modul3 = Modul::create([
            'kursus_id' => $kursusLaravel->id,
            'judul' => 'Eloquent ORM & Database',
            'deskripsi' => 'Bekerja dengan database menggunakan Eloquent ORM',
            'urutan' => 3,
        ]);

        Video::create([
            'modul_id' => $modul3->id,
            'judul' => 'Pengenalan Eloquent',
            'deskripsi' => 'Dasar-dasar Eloquent ORM',
            'file_video' => 'https://www.youtube.com/watch?v=eloquent1',
            'urutan' => 1,
        ]);
        Video::create([
            'modul_id' => $modul3->id,
            'judul' => 'Migrations',
            'deskripsi' => 'Membuat dan menjalankan migrations',
            'file_video' => 'https://www.youtube.com/watch?v=migration1',
            'urutan' => 2,
        ]);
        Video::create([
            'modul_id' => $modul3->id,
            'judul' => 'Model Relationships',
            'deskripsi' => 'One-to-One, One-to-Many, Many-to-Many',
            'file_video' => 'https://www.youtube.com/watch?v=relationship1',
            'urutan' => 3,
        ]);

        Materi::create([
            'modul_id' => $modul3->id,
            'judul' => 'Cheatsheet Eloquent',
            'deskripsi' => 'Referensi cepat Eloquent queries',
            'konten' => '<h1>Eloquent ORM Cheatsheet</h1>

<h2>Basic Queries</h2>
<pre><code>// Get all
$users = User::all();

// Find by ID
$user = User::find(1);

// First matching
$user = User::where("email", "test@test.com")->first();

// Get with conditions
$users = User::where("status", "active")
             ->orderBy("name")
             ->get();</code></pre>

<h2>Create, Update, Delete</h2>
<pre><code>// Create
$user = User::create([
    "name" => "John",
    "email" => "john@example.com"
]);

// Update
$user->update(["name" => "Jane"]);

// Delete
$user->delete();</code></pre>

<h2>Relationships</h2>
<pre><code>// One to Many
public function posts() {
    return $this->hasMany(Post::class);
}

// Belongs To
public function user() {
    return $this->belongsTo(User::class);
}

// Many to Many
public function roles() {
    return $this->belongsToMany(Role::class);
}</code></pre>

<h2>Eager Loading</h2>
<pre><code>// Prevent N+1 problem
$users = User::with("posts")->get();

// Multiple relationships
$users = User::with(["posts", "comments"])->get();</code></pre>',
            'urutan' => 1,
        ]);

        $quiz3 = Ujian::create([
            'kursus_id' => $kursusLaravel->id,
            'modul_id' => $modul3->id,
            'judul' => 'Quiz: Eloquent ORM',
            'deskripsi' => 'Test pemahaman Eloquent ORM',
            'tipe' => 'practice',
            'waktu_pengerjaan' => 15,
            'minimum_score' => 70,
        ]);

        $this->createSoalWithPilihan($quiz3, [
            [
                'pertanyaan' => 'Perintah untuk membuat migration adalah?',
                'tipe_soal' => 'single',
                'pilihan' => ['php artisan make:migration', 'php artisan create:migration', 'php artisan migration:make', 'php artisan new:migration'],
                'jawaban_benar' => 0,
                'pembahasan' => 'Gunakan php artisan make:migration nama_migration.',
            ],
            [
                'pertanyaan' => 'Method yang mengembalikan semua data dari tabel adalah?',
                'tipe_soal' => 'single',
                'pilihan' => ['Model::get()', 'Model::all()', 'Model::fetch()', 'Model::select()'],
                'jawaban_benar' => 1,
                'pembahasan' => 'Model::all() mengembalikan semua records dari tabel.',
            ],
            [
                'pertanyaan' => 'Relationship one-to-many menggunakan method?',
                'tipe_soal' => 'single',
                'pilihan' => ['hasOne', 'hasMany', 'belongsTo', 'belongsToMany'],
                'jawaban_benar' => 1,
                'pembahasan' => 'hasMany() untuk one-to-many dari sisi "one".',
            ],
        ]);

        // MODUL 4: Blade Template
        $modul4 = Modul::create([
            'kursus_id' => $kursusLaravel->id,
            'judul' => 'Blade Template Engine',
            'deskripsi' => 'Membuat tampilan dengan Blade templating',
            'urutan' => 4,
        ]);

        Video::create([
            'modul_id' => $modul4->id,
            'judul' => 'Dasar Blade Template',
            'deskripsi' => 'Syntax dan fitur Blade',
            'file_video' => 'https://www.youtube.com/watch?v=blade1',
            'urutan' => 1,
        ]);
        Video::create([
            'modul_id' => $modul4->id,
            'judul' => 'Layouts dan Components',
            'deskripsi' => 'Membuat layout dan reusable components',
            'file_video' => 'https://www.youtube.com/watch?v=blade2',
            'urutan' => 2,
        ]);

        Materi::create([
            'modul_id' => $modul4->id,
            'judul' => 'Blade Syntax Reference',
            'deskripsi' => 'Referensi lengkap syntax Blade',
            'konten' => '<h1>Blade Template Syntax</h1>

<h2>Menampilkan Data</h2>
<pre><code>{{ $variable }}
{!! $htmlContent !!}</code></pre>

<h2>Kondisi</h2>
<pre><code>@if($condition)
    Content if true
@elseif($anotherCondition)
    Another content
@else
    Else content
@endif</code></pre>

<h2>Perulangan</h2>
<pre><code>@foreach($items as $item)
    {{ $item->name }}
@endforeach

@forelse($items as $item)
    {{ $item->name }}
@empty
    No items found
@endforelse</code></pre>

<h2>Layout</h2>
<pre><code>{{-- layouts/app.blade.php --}}
&lt;html&gt;
&lt;body&gt;
    @yield("content")
&lt;/body&gt;
&lt;/html&gt;

{{-- page.blade.php --}}
@extends("layouts.app")

@section("content")
    Page content here
@endsection</code></pre>

<h2>Components</h2>
<pre><code>&lt;x-alert type="success" message="Data saved!" /&gt;</code></pre>',
            'urutan' => 1,
        ]);

        // MODUL 5: Authentication
        $modul5 = Modul::create([
            'kursus_id' => $kursusLaravel->id,
            'judul' => 'Authentication & Authorization',
            'deskripsi' => 'Implementasi login, register, dan hak akses',
            'urutan' => 5,
        ]);

        Video::create([
            'modul_id' => $modul5->id,
            'judul' => 'Laravel Breeze Setup',
            'deskripsi' => 'Install dan konfigurasi authentication',
            'file_video' => 'https://www.youtube.com/watch?v=auth1',
            'urutan' => 1,
        ]);
        Video::create([
            'modul_id' => $modul5->id,
            'judul' => 'Roles & Permissions',
            'deskripsi' => 'Implementasi role-based access control',
            'file_video' => 'https://www.youtube.com/watch?v=auth2',
            'urutan' => 2,
        ]);

        Materi::create([
            'modul_id' => $modul5->id,
            'judul' => 'Panduan Authentication',
            'deskripsi' => 'Setup authentication di Laravel',
            'konten' => '<h1>Authentication di Laravel</h1>

<h2>Install Laravel Breeze</h2>
<pre><code>composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate</code></pre>

<h2>Middleware Auth</h2>
<pre><code>// Di routes
Route::middleware("auth")->group(function () {
    Route::get("/dashboard", [DashboardController::class, "index"]);
});

// Di controller
public function __construct()
{
    $this->middleware("auth");
}</code></pre>

<h2>Check Auth di Blade</h2>
<pre><code>@auth
    Welcome, {{ auth()->user()->name }}
@endauth

@guest
    Please login
@endguest</code></pre>

<h2>Authorization</h2>
<pre><code>// Gate
Gate::define("update-post", function ($user, $post) {
    return $user->id === $post->user_id;
});

// Di controller
$this->authorize("update-post", $post);</code></pre>',
            'urutan' => 1,
        ]);

        // MODUL 6: Final Project
        $modul6 = Modul::create([
            'kursus_id' => $kursusLaravel->id,
            'judul' => 'Final Project',
            'deskripsi' => 'Membangun aplikasi web lengkap',
            'urutan' => 6,
        ]);

        Video::create([
            'modul_id' => $modul6->id,
            'judul' => 'Project Overview',
            'deskripsi' => 'Gambaran project yang akan dibangun',
            'file_video' => 'https://www.youtube.com/watch?v=final1',
            'urutan' => 1,
        ]);

        Materi::create([
            'modul_id' => $modul6->id,
            'judul' => 'Panduan Final Project',
            'deskripsi' => 'Requirement dan panduan pengerjaan',
            'konten' => '<h1>Final Project: Blog Application</h1>

<h2>Requirements</h2>
<ul>
<li>User authentication (register, login, logout)</li>
<li>CRUD posts dengan kategorisasi</li>
<li>Komentar pada posts</li>
<li>User profile management</li>
<li>Admin dashboard</li>
<li>Image upload untuk posts</li>
<li>Search dan filter posts</li>
<li>Responsive design</li>
</ul>

<h2>Tech Stack</h2>
<ul>
<li>Laravel 10</li>
<li>MySQL</li>
<li>Tailwind CSS</li>
<li>Alpine.js (optional)</li>
</ul>

<h2>Kriteria Penilaian</h2>
<ol>
<li>Fungsionalitas (40%)</li>
<li>Code quality (25%)</li>
<li>UI/UX (20%)</li>
<li>Documentation (15%)</li>
</ol>

<h2>Deadline</h2>
<p>Kumpulkan project melalui GitHub repository.</p>',
            'urutan' => 1,
        ]);

        // UJIAN AKHIR
        $ujianAkhir = Ujian::create([
            'kursus_id' => $kursusLaravel->id,
            'modul_id' => $modul6->id,
            'judul' => 'Ujian Akhir: Fullstack Laravel',
            'deskripsi' => 'Ujian komprehensif mencakup semua materi',
            'tipe' => 'exam',
            'waktu_pengerjaan' => 60,
            'minimum_score' => 75,
        ]);

        $this->createSoalWithPilihan($ujianAkhir, [
            [
                'pertanyaan' => 'Artisan adalah?',
                'tipe_soal' => 'single',
                'pilihan' => ['Database driver', 'Command line interface Laravel', 'Template engine', 'ORM'],
                'jawaban_benar' => 1,
                'pembahasan' => 'Artisan adalah CLI Laravel untuk menjalankan berbagai commands.',
            ],
            [
                'pertanyaan' => 'File .env digunakan untuk?',
                'tipe_soal' => 'single',
                'pilihan' => ['Menyimpan routes', 'Menyimpan environment variables', 'Menyimpan views', 'Menyimpan models'],
                'jawaban_benar' => 1,
                'pembahasan' => 'File .env menyimpan konfigurasi environment seperti database credentials.',
            ],
            [
                'pertanyaan' => 'Middleware berfungsi untuk?',
                'tipe_soal' => 'single',
                'pilihan' => ['Membuat database', 'Filter HTTP requests', 'Render views', 'Handle errors'],
                'jawaban_benar' => 1,
                'pembahasan' => 'Middleware memfilter HTTP requests yang masuk ke aplikasi.',
            ],
            [
                'pertanyaan' => 'Pilih fitur-fitur yang ada di Laravel (pilih semua yang benar):',
                'tipe_soal' => 'multiple',
                'pilihan' => ['Eloquent ORM', 'Blade Template', 'Artisan CLI', 'Built-in Authentication', 'Queue System', 'Task Scheduling'],
                'jawaban_benar' => [0, 1, 2, 3, 4, 5],
                'pembahasan' => 'Semua opsi adalah fitur bawaan Laravel.',
            ],
            [
                'pertanyaan' => 'Untuk menjalankan server development Laravel:',
                'tipe_soal' => 'single',
                'pilihan' => ['php artisan start', 'php artisan serve', 'php artisan run', 'php artisan server'],
                'jawaban_benar' => 1,
                'pembahasan' => 'php artisan serve menjalankan development server di localhost:8000.',
            ],
            [
                'pertanyaan' => 'Apa itu Seeder di Laravel?',
                'tipe_soal' => 'single',
                'pilihan' => ['Tool untuk testing', 'Tool untuk mengisi database dengan data dummy', 'Tool untuk migrasi', 'Tool untuk authentication'],
                'jawaban_benar' => 1,
                'pembahasan' => 'Seeder digunakan untuk mengisi database dengan data sample/dummy.',
            ],
            [
                'pertanyaan' => 'Mass assignment protection di Eloquent menggunakan property?',
                'tipe_soal' => 'single',
                'pilihan' => ['$protected', '$fillable atau $guarded', '$mass', '$assign'],
                'jawaban_benar' => 1,
                'pembahasan' => '$fillable untuk whitelist, $guarded untuk blacklist.',
            ],
            [
                'pertanyaan' => 'Cara menjalankan semua migration?',
                'tipe_soal' => 'single',
                'pilihan' => ['php artisan migrate:all', 'php artisan migrate', 'php artisan db:migrate', 'php artisan run:migrate'],
                'jawaban_benar' => 1,
                'pembahasan' => 'php artisan migrate menjalankan semua migration yang belum dijalankan.',
            ],
            [
                'pertanyaan' => 'Validasi di Laravel bisa dilakukan di?',
                'tipe_soal' => 'multiple',
                'pilihan' => ['Controller', 'Form Request', 'Model', 'Middleware'],
                'jawaban_benar' => [0, 1],
                'pembahasan' => 'Validasi umumnya dilakukan di Controller atau Form Request class.',
            ],
            [
                'pertanyaan' => 'Laravel menggunakan pattern arsitektur?',
                'tipe_soal' => 'single',
                'pilihan' => ['MVP', 'MVVM', 'MVC', 'Microservices'],
                'jawaban_benar' => 2,
                'pembahasan' => 'Laravel menggunakan MVC (Model-View-Controller) pattern.',
            ],
        ]);

        $this->command->info('✓ Pelatihan Laravel lengkap berhasil dibuat!');
        $this->command->info('  - 6 Modul');
        $this->command->info('  - 12 Video');
        $this->command->info('  - 6 Bacaan/Materi');
        $this->command->info('  - 3 Quiz');
        $this->command->info('  - 1 Ujian Akhir');
    }

    /**
     * Helper untuk membuat soal dengan pilihan jawaban
     */
    private function createSoalWithPilihan($ujian, $soalList)
    {
        foreach ($soalList as $data) {
            $soal = Soal::create([
                'kursus_id' => $ujian->kursus_id,
                'ujian_id' => $ujian->id,
                'pertanyaan' => $data['pertanyaan'],
                'tipe_soal' => $data['tipe_soal'],
                'kunci_jawaban' => is_array($data['jawaban_benar']) 
                    ? implode(',', array_map(fn($i) => $data['pilihan'][$i], $data['jawaban_benar']))
                    : $data['pilihan'][$data['jawaban_benar']],
                'pembahasan' => $data['pembahasan'] ?? null,
            ]);

            foreach ($data['pilihan'] as $index => $teks) {
                $isCorrect = is_array($data['jawaban_benar'])
                    ? in_array($index, $data['jawaban_benar'])
                    : $index === $data['jawaban_benar'];

                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'pilihan' => $teks,
                    'is_correct' => $isCorrect,
                ]);
            }
        }
    }
}
