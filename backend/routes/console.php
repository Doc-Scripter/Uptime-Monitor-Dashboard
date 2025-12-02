<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Monitor;
use App\Jobs\CheckMonitorHealth;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule health checks for all active monitors
Schedule::call(function () {
    $monitors = Monitor::dueForCheck()->get();
    
    foreach ($monitors as $monitor) {
        CheckMonitorHealth::dispatch($monitor);
    }
})->everyMinute()->name('check-monitor-health');
