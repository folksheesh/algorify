<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Fixing thumbnail paths...\n";

$updated = DB::table('kursus')
    ->where('thumbnail', 'like', 'storage/%')
    ->update([
        'thumbnail' => DB::raw("REPLACE(thumbnail, 'storage/', '')")
    ]);

echo "Updated {$updated} rows\n";

// Show current paths
echo "\nCurrent thumbnail paths:\n";
$kursus = DB::table('kursus')->select('id', 'judul', 'thumbnail')->get();
foreach ($kursus as $k) {
    echo "{$k->id} | {$k->judul} | {$k->thumbnail}\n";
}
