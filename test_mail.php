<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Tes email SMTP dari Algorify - ini adalah test email.', function ($message) {
        $message->to('nayendraajidiwantojailani@gmail.com')
                ->subject('Tes SMTP Algorify');
    });
    
    echo "Email berhasil dikirim!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
