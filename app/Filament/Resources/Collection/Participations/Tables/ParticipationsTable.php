<?php

namespace App\Filament\Resources\Collection\Participations\Tables;

use App\Enums\ParticipationStatusEnums;
use App\Filament\Resources\Collection\Participations\ParticipationResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ParticipationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('collection.title_short')
                    ->label('Сборник')
                    ->sortable(),
                TextColumn::make('author_name')
                    ->label('Автор')
                    ->sortable()
                    ->searchable(),
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
                TextColumn::make('status')
                    ->label('Статус')
                    ->sortable()
                    ->badge()
                    ->color(fn(ParticipationStatusEnums $state): string => match ($state) {
                        ParticipationStatusEnums::APPROVE_NEEDED => 'warning',
                        ParticipationStatusEnums::PAYMENT_REQUIRED,
                        ParticipationStatusEnums::NOT_ACTUAL => 'gray',
                        ParticipationStatusEnums::APPROVED => 'success',
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
                TextColumn::make('printOrder.price_print')
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
                TextColumn::make('priceTotal')
                    ->label('Цена общая')
                    ->getStateUsing(fn($record) => $record->priceTotal())
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
            ->defaultSort(function (Builder $query): Builder {
                return $query
                    ->orderByRaw("
                        CASE status
                            WHEN 'Ожидается подтверждение заявки' THEN 1
                            WHEN 'Заявка подтверждена, ожидается оплата' THEN 2
                            WHEN 'Участие подтверждено' THEN 3
                            WHEN 'Заявка неактуальна' THEN 9
                            ELSE 99
                        END ASC
                    ")
                    ->orderBy('created_at', 'desc');
            })
            ->recordActions([
                Action::make('edit')
                    ->url(fn(Model $record): string => ParticipationResource::getUrl('edit', ['user' => $record->user_id, 'record' => $record->id]))
                    ->openUrlInNewTab()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
