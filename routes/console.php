<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the unpaid order cancellation command
Schedule::command('orders:cancel-unpaid')
    ->dailyAt('02:00')  // Run at 2 AM daily
    ->onOneServer()     // Prevent duplicate runs in multi-server setups
    ->withoutOverlapping(120);  // Prevent concurrent runs, timeout after 2 hours
