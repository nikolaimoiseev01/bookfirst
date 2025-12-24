<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\InnerTasksWidget;
use App\Filament\Widgets\OwnBookChart;
use App\Filament\Widgets\ParticipationChart;
use App\Filament\Widgets\StatWidget;
use App\Filament\Widgets\UsersRegistrationWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BaseDashboard
{

    public function getWidgets(): array
    {
        return collect([
            StatWidget::class,
            UsersRegistrationWidget::class,
            ParticipationChart::class,
            OwnBookChart::class,
            InnerTasksWidget::class,
        ])
            ->filter(fn ($widget) => $widget::canView())
            ->all();
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
