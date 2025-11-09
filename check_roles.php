<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFIKASI ROLE SPATIE ===\n\n";

$adminCount = \App\Models\User::role('admin')->count();
$superAdminCount = \App\Models\User::role('super admin')->count();
$pengajarCount = \App\Models\User::role('pengajar')->count();
$pesertaCount = \App\Models\User::role('peserta')->count();

echo "Jumlah User per Role:\n";
echo "- Admin: {$adminCount}\n";
echo "- Super Admin: {$superAdminCount}\n";
echo "- Pengajar: {$pengajarCount}\n";
echo "- Peserta: {$pesertaCount}\n\n";

echo "Admin Users:\n";
foreach (\App\Models\User::role('admin')->get() as $user) {
    echo "  - {$user->name} ({$user->email})\n";
}

echo "\nSuper Admin Users:\n";
foreach (\App\Models\User::role('super admin')->get() as $user) {
    echo "  - {$user->name} ({$user->email})\n";
}

echo "\nPengajar Users:\n";
foreach (\App\Models\User::role('pengajar')->get() as $user) {
    echo "  - {$user->name} ({$user->email})\n";
}

echo "\nPeserta Users:\n";
foreach (\App\Models\User::role('peserta')->get() as $user) {
    echo "  - {$user->name} ({$user->email})\n";
}

echo "\n=== SELESAI ===\n";
