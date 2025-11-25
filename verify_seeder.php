<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Modul;
use App\Models\Video;
use App\Models\Materi;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\PilihanJawaban;

echo "=== Verifikasi Data Web Development Course ===\n\n";

$moduls = Modul::whereHas('kursus', function($q) {
    $q->where('judul', 'Web Development');
})->get();

echo "Modul: " . $moduls->count() . "\n";

$videos = Video::whereHas('modul.kursus', function($q) {
    $q->where('judul', 'Web Development');
})->count();
echo "Video: " . $videos . "\n";

$materis = Materi::whereHas('modul.kursus', function($q) {
    $q->where('judul', 'Web Development');
})->count();
echo "Materi: " . $materis . "\n";

$ujians = Ujian::whereHas('modul.kursus', function($q) {
    $q->where('judul', 'Web Development');
})->count();
echo "Ujian: " . $ujians . "\n";

$soals = Soal::whereHas('ujian.modul.kursus', function($q) {
    $q->where('judul', 'Web Development');
})->count();
echo "Soal: " . $soals . "\n";

$pilihans = PilihanJawaban::whereHas('soal.ujian.modul.kursus', function($q) {
    $q->where('judul', 'Web Development');
})->count();
echo "Pilihan Jawaban: " . $pilihans . "\n";

echo "\n=== Detail Modul ===\n";
foreach ($moduls as $i => $modul) {
    echo ($i+1) . ". " . $modul->judul . "\n";
    echo "   - Video: " . $modul->video()->count() . "\n";
    echo "   - Materi: " . $modul->materi()->count() . "\n";
    echo "   - Ujian: " . $modul->ujian()->count() . "\n";
}

echo "\n✓ Seeder verification completed!\n";
