<?php

namespace App\Filament\Resources\SurveyCompleteds\Pages;

use App\Filament\Resources\SurveyCompleteds\SurveyCompletedResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSurveyCompleted extends ViewRecord
{
    protected static string $resource = SurveyCompletedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
