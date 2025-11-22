<?php

namespace App\Filament\Resources\SurveyCompleteds\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveyCompletedsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user')->label('Пользователь')->getStateUsing(fn($record) => $record->user?->getUserFullName() ?? '—'),
                TextColumn::make('title')->label('Название'),
                TextColumn::make('rating')
                    ->label('Первая оценка')
                    ->getStateUsing(function ($record) {
                        return $record->surveyAnswers->first()['stars'] . '/5';
                    }),
                TextColumn::make('created_at')->label('Создан')->dateTime()

            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
            ])
            ->toolbarActions([
            ]);
    }
}
