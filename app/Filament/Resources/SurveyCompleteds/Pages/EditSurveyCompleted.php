<?php

namespace App\Filament\Resources\SurveyCompleteds\Pages;

use App\Filament\Resources\SurveyCompleteds\SurveyCompletedResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSurveyCompleted extends EditRecord
{
    protected static string $resource = SurveyCompletedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
