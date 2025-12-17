<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('app:internal-tasks-notification')->dailyAt('17:30');;

