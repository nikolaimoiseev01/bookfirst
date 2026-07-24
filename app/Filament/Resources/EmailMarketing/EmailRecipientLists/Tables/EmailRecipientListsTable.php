<?php

namespace App\Filament\Resources\EmailMarketing\EmailRecipientLists\Tables;

use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class EmailRecipientListsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('utm_campaign')
                    ->label('UTM Campaign')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('promocode.name')
                    ->label('Промокод')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Описание')
                    ->limit(50)
                    ->toggleable(),
                TextColumn::make('recipients_count')
                    ->label('Получателей')
                    ->counts('recipients')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }
}
