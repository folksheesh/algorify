<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Auto-check DOKU payment status every minute (for production with webhook)
Schedule::command('doku:check-status')->everyMinute();

// Auto-approve pending payments after 1 minute (for sandbox/testing) - FASTER!
Schedule::command('payment:auto-approve --minutes=1')->everyMinute();

// Expire old pending payments (after 24 hours)
Schedule::command('payment:expire-old --hours=24')->hourly();
