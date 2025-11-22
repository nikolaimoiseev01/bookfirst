<?php

namespace App\Filament\Resources\SurveyCompleteds\Pages;

use App\Filament\Resources\SurveyCompleteds\SurveyCompletedResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSurveyCompleteds extends ListRecords
{
    protected static string $resource = SurveyCompletedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
