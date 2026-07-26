<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('app:internal-tasks-notification')->dailyAt('19:30');
Schedule::command('app:almost-complete-actions-notification')->dailyAt('20:00');
Schedule::command('app:ext-promotion-stat-update')->dailyAt('21:00');
Schedule::command('app:ext-promotion-finish')->dailyAt('21:15');
Schedule::command('email:fetch-campaign-statistics')->everyThirtyMinutes();
Schedule::command('app:send-scheduled-email-campaigns')->everyMinute();
Schedule::command('email:sync-new-users')->dailyAt('01:00');

