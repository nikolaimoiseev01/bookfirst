<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use App\Models\Survey;
use Filament\Resources\Pages\Page;

class ViewCustom extends Page
{
    protected static string $resource = SurveyResource::class;

    protected static string $view = 'filament.resources.survey-resource.pages.view-custom';

    public $survey;

    public function mount($record) {
        $this->survey = Survey::where('id', $record)->first();
    }
}
