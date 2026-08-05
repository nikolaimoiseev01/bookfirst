<?php

namespace App\Filament\Resources\EmailMarketing\EmailCampaigns\Tables;

use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailCampaignStatistic;
use App\Services\EmailMarketing\SendEmailService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;

class EmailCampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label('Тема')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('recipientList.name')
                    ->label('Список получателей')
                    ->sortable(),
                TextColumn::make('recipientList.utm_campaign')
                    ->label('UTM Campaign')
                    ->sortable(),
                TextColumn::make('recipientList.promocode.name')
                    ->label('Промокод')
                    ->sortable(),
                TextColumn::make('campaignRecipients_count')
                    ->label('Получателей')
                    ->getStateUsing(function ($record) {
                       return $record->campaignRecipients()->count();
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'info',
                        'sending' => 'warning',
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                ViewColumn::make('send_ok')
                    ->label('Отправлено')
                    ->view('filament.tables.email-campaign-pie-chart', ['field' => 'send_ok', 'color' => '#10b981'])
                    ->default('-'),
                ViewColumn::make('open_msg')
                    ->label('Открыто')
                    ->view('filament.tables.email-campaign-pie-chart', ['field' => 'open_msg', 'color' => '#3b82f6'])
                    ->default('-'),
                ViewColumn::make('click_link')
                    ->label('Клики')
                    ->view('filament.tables.email-campaign-pie-chart', ['field' => 'click_link', 'color' => '#f59e0b'])
                    ->default('-'),
                ViewColumn::make('send_fail')
                    ->label('Ошибка')
                    ->view('filament.tables.email-campaign-pie-chart', ['field' => 'send_fail', 'color' => '#ef4444'])
                    ->default('-'),
                ViewColumn::make('unsubscribe')
                    ->label('Отписки')
                    ->view('filament.tables.email-campaign-pie-chart', ['field' => 'unsubscribe', 'color' => '#6b7280'])
                    ->default('-'),
                TextColumn::make('scheduled_at')
                    ->label('Запланировано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('sent_at')
                    ->label('Отправлено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Action::make('sendNow')
                    ->label('Отправить сейчас')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Отправить рассылку сейчас?')
                    ->modalDescription('Рассылка будет отправлена всем получателям.')
                    ->visible(fn (EmailCampaign $record): bool => in_array($record->status, [
                        'draft',
                        'scheduled',
                        'failed',
                    ], true))
                    ->action(function (EmailCampaign $record): void {
                        app(SendEmailService::class, ['emailCampaign' => $record])->sendSingle();

                        Notification::make()
                            ->title('Рассылка запущена')
                            ->success()
                            ->send();
                    }),
                Action::make('delete')
                    ->label('Удалить')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Удалить рассылку?')
                    ->modalDescription('Все получатели и статистика будут удалены.')
                    ->action(function (EmailCampaign $record): void {
                        $record->campaignRecipients()->delete();
                        $record->statistic()->delete();
                        $record->delete();

                        Notification::make()
                            ->title('Рассылка удалена')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
