<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Monitor;
use App\Jobs\RunMonitorCheck;

// use Illuminate\Foundation\Inspiring;
// use Illuminate\Support\Facades\Artisan;
// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');


Schedule::call(function () {
    Monitor::query()
        ->where('enabled', true)
        ->chunkById(200, function ($batch) {
            foreach ($batch as $m) {
                $last = $m->lastCheck()?->checked_at;
                $due = !$last || now()->diffInSeconds($last) >= $m->interval_seconds;

                if ($due) {
                    RunMonitorCheck::dispatch($m->id)->onQueue('default');
                }
            }
        });
})->everyMinute();