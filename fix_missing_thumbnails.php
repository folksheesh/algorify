<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Fixing missing thumbnails...\n";

// Set null for missing files
$missingFiles = [
    'thumbnails/kDgEigqjBPWt1LoCCtoMEBPOgxNOx2p716HQBLmA.png',
    'thumbnails/xmcWmPC2DqZjvSogm9svnu8ybGPmkFHdPTT5UWnk.png'
];

foreach ($missingFiles as $file) {
    $updated = DB::table('kursus')
        ->where('thumbnail', $file)
        ->update(['thumbnail' => null]);
    
    echo "Set thumbnail to null for: {$file} ({$updated} rows)\n";
}

echo "\nDone!\n";
