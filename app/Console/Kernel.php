<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your participation.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the participation's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('TaskUpdate')->timezone('Europe/Moscow')->dailyAt('19:30');
        $schedule->command('PayReminder')->timezone('Europe/Moscow')->dailyAt('19:00');
        $schedule->command('DangerTasks')
            ->timezone('Europe/Moscow')
            ->dailyAt('09:30')
            ->dailyAt('15:00')
            ->dailyAt('18:00')
            ->dailyAt('21:00');
    }

    /**
     * Register the commands for the participation.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
