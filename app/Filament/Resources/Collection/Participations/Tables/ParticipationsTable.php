<?php

namespace App\Filament\Resources\Collection\Participations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ParticipationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('collection.title')
                    ->label('Сборник')
                    ->sortable(),
//                TextColumn::make('author_name')
//                    ->searchable(),
                TextColumn::make('works_number')
                    ->label('Произведений')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('rows')
                    ->label('Строк')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pages')
                    ->label('Страниц')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('participationStatus.name')
                    ->label('Статус')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Ожидается подтверждение заявки' => 'warning',
                        'Заявка подтверждена, ожидается оплата', 'Подготовка к печати' => 'primary',
                        'Участие подтверждено' => 'success',
                        'Заявка неактуальна' => 'primary',
                    }),
                TextColumn::make('printOrder.books_cnt')
                    ->label('Экземпляров')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('promocode.name')
                    ->label('Промокод')
                    ->sortable(),
                TextColumn::make('price_part')
                    ->label('Цена участия')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('price_print')
                    ->label('Цена печати')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('price_check')
                    ->label('Цена проверки')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('price_send')
                    ->label('Цена отправки')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('price_total')
                    ->label('Цена общая')
                    ->formatStateUsing(fn (string $state): string => makeMoney($state, 0, true))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
