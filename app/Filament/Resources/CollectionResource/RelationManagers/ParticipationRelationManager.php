<?php

namespace App\Filament\Resources\CollectionResource\RelationManagers;

use App\Models\Participation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipationRelationManager extends RelationManager
{
    protected static string $relationship = 'participation';

    protected static ?string $recordTitleAttribute = 'collection_id';

    protected static ?string $title = 'Участники';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('collection_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('surname')->label('Фамилия')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Имя')->searchable(),
                Tables\Columns\TextColumn::make('nickname')->label('Псевдоним')->searchable(),
                Tables\Columns\TextColumn::make('total_price')->label('Общая стоимость'),
                Tables\Columns\TextColumn::make('printorder.books_needed')->label('Печать')->placeholder('Ну нежна'),
                Tables\Columns\TextColumn::make('check_price')->label('Проверка'),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->actions([
                Action::make('edit')
                    ->url(fn(Participation $record): string => '/admin/participations/' . $record['id'] . '/part_page')
                    ->openUrlInNewTab()
                    ->label('')
            ])
            ->bulkActions([
            ]);
    }


}
