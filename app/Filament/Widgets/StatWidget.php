<?php

namespace App\Filament\Widgets;

use App\Enums\InnerTaskTypeEnums;
use App\Models\InnerTask;
use App\Models\User\User;
use App\Models\Work\Work;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $users = User::count();
        $works = Work::count();
        $coverTasks = InnerTask::where('type', InnerTaskTypeEnums::OWN_BOOK_COVER)->count();
        $tasks = InnerTask::count();
        return [
            Stat::make('Пользователей', $users),
            Stat::make('Работ на сайте', $works),
            Stat::make('Задач всего', $tasks)->color('danger'),
            Stat::make('Задач на обложку', $coverTasks),
        ];
    }
}
