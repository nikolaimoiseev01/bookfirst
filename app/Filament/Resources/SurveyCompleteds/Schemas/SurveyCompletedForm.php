<?php

namespace App\Filament\Resources\SurveyCompleteds\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SurveyCompletedForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('surveyAnswers')
                    ->disabled()
                    ->relationship('surveyAnswers')
                    ->schema([
                        TextEntry::make('step'),
                        TextEntry::make('stars'),
                        TextEntry::make('question'),
                        TextEntry::make('text')
                    ])->columns(4)
            ]);
    }
}
