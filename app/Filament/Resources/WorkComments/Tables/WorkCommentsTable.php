<?php

namespace App\Filament\Resources\WorkComments\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WorkCommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->formatStateUsing(fn ($state, Model $record): string => $record->user?->getUserFullName() ?? (string) $state)
                    ->searchable(),
                TextColumn::make('work.title')
                    ->label('Произведение')
                    ->searchable(),
                TextColumn::make('text')
                    ->label('Комментарий')
                    ->limit(100)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
