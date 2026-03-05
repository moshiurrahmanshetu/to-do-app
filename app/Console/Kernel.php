<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('tasks:send-reminders')
            ->dailyAt('08:00')
            ->timezone(config('app.timezone'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
