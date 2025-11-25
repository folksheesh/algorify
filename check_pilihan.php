<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PilihanJawaban;
use App\Models\Soal;

echo "Total Pilihan Jawaban: " . PilihanJawaban::count() . "\n";
echo "Total Soal: " . Soal::count() . "\n";

$soals = Soal::with('pilihanJawaban')->get();
echo "\nDetail Soal dan Pilihan:\n";
foreach ($soals as $soal) {
    echo "- Soal ID {$soal->id}: {$soal->pertanyaan}\n";
    echo "  Pilihan: " . $soal->pilihanJawaban()->count() . "\n";
}
