<?php

namespace App\Filament\Resources\ExtPromotions\Tables;

use App\Enums\ExtPromotionStatusEnums;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExtPromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Пользователь')
                    ->getStateUsing(fn($record) => $record->user->getUserFullName()),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        ExtPromotionStatusEnums::PAYMENT_REQUIRED, ExtPromotionStatusEnums::IN_PROGRESS, ExtPromotionStatusEnums::WAITING_FOR_AUTHOR_IN_CHAT, ExtPromotionStatusEnums::NOT_ACTUAL => 'primary',
                        ExtPromotionStatusEnums::REVIEW => 'warning',
                        ExtPromotionStatusEnums::START_REQUIRED => 'danger',
                        ExtPromotionStatusEnums::DONE => 'success',
                    }),
                TextColumn::make('site')
                    ->label('Сайт'),
                TextColumn::make('days')
                    ->label('Дней'),
                TextColumn::make('promocode.name')
                    ->label('Промокод')
                    ->default('Без промокода'),
                TextColumn::make('price_executor')
                    ->label('Исполнитель')
                    ->numeric(),
                TextColumn::make('price_out')
                    ->label('Издательство')
                    ->numeric(),
                TextColumn::make('price_total')
                    ->label('Общая сумма')
                    ->numeric(),
                IconColumn::make('executor_got_payment')
                    ->label('Оплачен исполнителю')
                    ->icon(fn (int $state): Heroicon => match ($state) {
                        0 => Heroicon::OutlinedClock,
                        1 => Heroicon::OutlinedCheckCircle,
                    })
                    ->color(fn($state): string => match ($state) {
                        0 => 'primary',
                        1 => 'success',
                    }),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
