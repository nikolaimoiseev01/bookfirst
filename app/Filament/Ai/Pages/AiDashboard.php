<?php

namespace App\Filament\Ai\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard;

class AiDashboard extends Dashboard
{
    protected static ?string $title = 'Панель ИИ';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('admin')
                ->label('Основная админка')
                ->icon('heroicon-o-arrow-left')
                ->url('/admin'),
        ];
    }
}
