<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = App\Models\User::all();

echo "=== DAFTAR USER DENGAN KODE UNIK ===" . PHP_EOL;
echo str_repeat('-', 60) . PHP_EOL;

foreach ($users as $user) {
    $roles = $user->getRoleNames()->implode(', ');
    echo sprintf("%-3s | %-25s | %-12s | %s", $user->id, $user->name, $user->kode_unik, $roles) . PHP_EOL;
}

echo str_repeat('-', 60) . PHP_EOL;
echo "Total: " . $users->count() . " users" . PHP_EOL;
