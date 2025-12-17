<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('app:internal-tasks-notification')->dailyAt('19:30');
Schedule::command('app:ext-promotion-stat-update')->dailyAt('21:00');
Schedule::command('app:ext-promotion-stat-update')->dailyAt('21:15');

