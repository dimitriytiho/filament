<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Health\Commands\{DispatchQueueCheckJobsCommand, RunHealthChecksCommand, ScheduleCheckHeartbeatCommand};

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Health checks
        $schedule->command(RunHealthChecksCommand::class)->everyFiveMinutes();
        $schedule->command(ScheduleCheckHeartbeatCommand::class)->everyFiveMinutes();
        $schedule->command(DispatchQueueCheckJobsCommand::class)->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
