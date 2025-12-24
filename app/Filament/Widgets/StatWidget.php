<?php

namespace App\Filament\Widgets;

use App\Enums\InnerTaskTypeEnums;
use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use App\Models\InnerTask;
use App\Models\OwnBook\OwnBook;
use App\Models\User\User;
use App\Models\Work\Work;
use App\Models\Work\WorkComment;
use App\Models\Work\WorkLike;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatWidget extends StatsOverviewWidget
{

    use HasWidgetShield;
    protected function getStats(): array
    {
        $users = User::count();
        $works = Work::count();
        $ownBooks = OwnBook::count();
        $collections = Collection::count();
        $participations = Participation::count();
        $workComments = WorkComment::count();
        $workLikes = WorkLike::count();
        $tasks = InnerTask::count();
        return [
            Stat::make('Задач всего', $tasks)->color('danger'),
            Stat::make('Пользователей', $users),
            Stat::make('Книг', $ownBooks),
            Stat::make('Сборников', $collections),
            Stat::make('Участий', $participations),
            Stat::make('Работ на сайте', $works),
            Stat::make('Комментариев', $workComments),
            Stat::make('Лайков', $workLikes)
        ];
    }

    protected function getColumns(): int
    {
        return 8;
    }
}
