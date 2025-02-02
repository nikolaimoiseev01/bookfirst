<?php

namespace App\Filament\Resources\CollectionResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrintorderRelationManager extends RelationManager
{
    protected static string $relationship = 'printorder';

    protected static ?string $recordTitleAttribute = 'collection_id';

    protected static ?string $title = 'Печатные заказы';

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
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('Автор')
                    ->getStateUsing(function ($record) {
                        return prefer_name($record->participation->name, $record->participation->surname, $record->participation->nickname);
                    }),
                Tables\Columns\TextColumn::make('books_needed')->label('Экземпляров'),
                Tables\Columns\TextColumn::make('Адрес')
                    ->getStateUsing(function ($record) {
                    return print_address($record);
                })
                    ->limit(25),
                Tables\Columns\BadgeColumn::make('paid_at')->date()
                    ->label('Оплачен?')
                    ->placeholder('Нет')
                    ->colors([
                    'success',
                    'danger' => static fn ($state): bool => $state === null,
                ]),
                TextInputColumn::make('track_number')->label('Трек-номер')->placeholder('Еще нет'),
                TextInputColumn::make('send_price')->label('Цена отправки')->placeholder('Еще нет'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
            ]);
    }
}
