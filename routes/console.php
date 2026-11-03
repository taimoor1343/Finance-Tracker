
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Generate due recurring transactions every day at 12:05 AM.
Schedule::command('recurring:generate')
    ->dailyAt('00:05')
    ->withoutOverlapping();
