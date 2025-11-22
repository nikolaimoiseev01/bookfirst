<?php

namespace App\Filament\Resources\SurveyCompleteds;

use App\Filament\Resources\SurveyCompleteds\Pages\CreateSurveyCompleted;
use App\Filament\Resources\SurveyCompleteds\Pages\EditSurveyCompleted;
use App\Filament\Resources\SurveyCompleteds\Pages\ListSurveyCompleteds;
use App\Filament\Resources\SurveyCompleteds\Pages\ViewSurveyCompleted;
use App\Filament\Resources\SurveyCompleteds\Schemas\SurveyCompletedForm;
use App\Filament\Resources\SurveyCompleteds\Schemas\SurveyCompletedInfolist;
use App\Filament\Resources\SurveyCompleteds\Tables\SurveyCompletedsTable;
use App\Models\Survey\SurveyCompleted;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurveyCompletedResource extends Resource
{
    protected static ?string $model = SurveyCompleted::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHandThumbUp;

    protected static ?string $label = 'Опросы';
    protected static ?string $navigationLabel = 'Опросы';
    protected static ?string $pluralLabel = 'Опросы';

    public static function form(Schema $schema): Schema
    {
        return SurveyCompletedForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SurveyCompletedInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveyCompletedsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurveyCompleteds::route('/'),
            'create' => CreateSurveyCompleted::route('/create'),
            'view' => ViewSurveyCompleted::route('/{record}'),
            'edit' => EditSurveyCompleted::route('/{record}/edit'),
        ];
    }
}
