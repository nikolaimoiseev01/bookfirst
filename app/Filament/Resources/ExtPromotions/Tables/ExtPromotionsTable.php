<?php

namespace App\Filament\Resources\ExtPromotions\Tables;

use App\Enums\ExtPromotionStatusEnums;
use App\Models\ExtPromotion\ExtPromotion;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ExtPromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('name')
                    ->label('Пользователь')
                    ->getStateUsing(fn($record) => $record->user->getUserFullName()),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
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
//                TextColumn::make('price_out')
//                    ->label('Издательство')
//                    ->numeric(),
//                TextColumn::make('price_total')
//                    ->label('Общая сумма')
//                    ->numeric(),
                IconColumn::make('executor_got_payment')
                    ->label('Оплачен исполнителю')

                    ->icon(function ($record): Heroicon {
                        return match (true) {

                            // 👉 если статус не тот — показываем, например, pause/ban
                            in_array($record->status, [
                                ExtPromotionStatusEnums::PAYMENT_REQUIRED,
                                ExtPromotionStatusEnums::REVIEW,
                                ExtPromotionStatusEnums::NOT_ACTUAL,
                                ExtPromotionStatusEnums::START_REQUIRED,
                            ]) => Heroicon::OutlinedMinusCircle,

                            // 👉 если можно платить, но не оплачено
                            $record->executor_got_payment == 0 => Heroicon::OutlinedClock,

                            // 👉 оплачено
                            $record->executor_got_payment == 1 => Heroicon::OutlinedCheckCircle,
                        };
                    })
                    ->tooltip(function ($record): string {
                        return match (true) {

                            // не актуально
                            in_array($record->status, [
                                ExtPromotionStatusEnums::PAYMENT_REQUIRED,
                                ExtPromotionStatusEnums::REVIEW,
                                ExtPromotionStatusEnums::NOT_ACTUAL,
                                ExtPromotionStatusEnums::START_REQUIRED,
                            ]) => 'Оплата не требуется',

                            // ждёт оплату
                            $record->executor_got_payment == 0 => 'Необходима оплата',

                            // оплачено
                            $record->executor_got_payment == 1 => 'Оплачено',
                        };
                    })

                    ->color(function ($record): string {
                        return match (true) {

                            // не актуально
                            in_array($record->status, [
                                ExtPromotionStatusEnums::PAYMENT_REQUIRED,
                                ExtPromotionStatusEnums::REVIEW,
                                ExtPromotionStatusEnums::NOT_ACTUAL,
                                ExtPromotionStatusEnums::START_REQUIRED,
                            ]) => 'gray',

                            // ждёт оплату
                            $record->executor_got_payment == 0 => 'primary',

                            // оплачено
                            $record->executor_got_payment == 1 => 'success',
                        };
                    }),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('executor_got_payment')
                    ->label('Оплачено исполнителю')
                    ->options([
                        1 => 'Да',
                        0 => 'Нет',
                    ]),
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(
                        collect(ExtPromotionStatusEnums::cases())
                            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
                            ->toArray()
                    )
                    ->multiple()

                    // 👉 дефолт: все КРОМЕ одного
                    ->default(
                        collect(ExtPromotionStatusEnums::cases())
                            ->pluck('value')
                            ->reject(fn ($value) => $value === ExtPromotionStatusEnums::NOT_ACTUAL->value)
                            ->values()
                            ->toArray()
                    )
            ])
            ->recordActions([
            ])
            ->headerActions([
                Action::make('pay')
                    ->label(function () {
                        $extPromotionsToPayment = ExtPromotion::query()
                            ->whereIn('status', [ExtPromotionStatusEnums::IN_PROGRESS, ExtPromotionStatusEnums::DONE])
                            ->where('executor_got_payment', false)
                            ->get();
                        $sumToPayment = $extPromotionsToPayment->sum('price_executor');

                        return Auth::user()->hasRole('admin')
                            ? "Оплатить {$sumToPayment} ₽"
                            : "К оплате: {$sumToPayment} ₽";
                    })
                    ->color('success')
                    ->icon(Heroicon::OutlinedCreditCard)

                    // 👇 доступ только для роли
                    ->disabled(fn() => !Auth::user()->hasRole('admin'))

                    // 👇 можно скрыть совсем (если нужно)
                    // ->visible(fn () => Auth::user()->hasRole('admin'))

                    ->requiresConfirmation()
                    ->modalHeading('Подтверждение оплаты')
                    ->modalDescription('Вы уверены, что хотите отметить записи как оплаченные?')
                    ->action(function () {
                        $count = ExtPromotion::query()
                            ->whereIn('status', [
                                ExtPromotionStatusEnums::IN_PROGRESS,
                                ExtPromotionStatusEnums::DONE
                            ])
                            ->where('executor_got_payment', false)
                            ->update([
                                'executor_got_payment' => 1,
                            ]);

                        Notification::make()
                            ->title('Оплата выполнена')
                            ->body("Оплачено продвижений: {$count}")
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
