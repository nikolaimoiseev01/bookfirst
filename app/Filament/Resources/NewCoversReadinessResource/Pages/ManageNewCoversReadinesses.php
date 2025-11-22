<?php

namespace App\Filament\Resources\NewCoversReadinessResource\Pages;

use App\Filament\Resources\NewCoversReadinessResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNewCoversReadinesses extends ManageRecords
{
    protected static string $resource = NewCoversReadinessResource::class;

    protected static ?string $title = 'Готовность обложек';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
