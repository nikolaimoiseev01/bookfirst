<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSurveys extends ManageRecords
{
    protected static string $resource = SurveyResource::class;

    protected static ?string $title = 'Опросы';

//    protected static string $view = 'filament.resources.survey-resource.pages.view-custom';

    protected function getActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [50, 100, 150, 200];
    }
}
