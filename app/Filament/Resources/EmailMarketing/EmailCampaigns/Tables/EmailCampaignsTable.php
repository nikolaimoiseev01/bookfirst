<?php

namespace App\Filament\Resources\EmailMarketing\EmailCampaigns\Tables;

use App\Models\EmailMarketing\EmailCampaign;
use App\Services\EmailMarketing\SendEmailService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
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
            ])
            ->defaultSort('created_at', 'desc');
    }
}
