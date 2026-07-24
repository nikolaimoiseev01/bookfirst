<?php

namespace App\Filament\Resources\EmailMarketing\EmailCampaigns\Pages;

use App\Filament\Resources\EmailMarketing\EmailCampaigns\EmailCampaignResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Artisan;

class ViewEmailCampaign extends ViewRecord
{
    protected static string $resource = EmailCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('fetchStatistics')
                ->label('Обновить статистику')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function () {
                    Artisan::call('email:fetch-campaign-statistics', [
                        '--campaign-id' => $this->record->id,
                    ]);

                    Notification::make()
                        ->title('Статистика обновляется')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Статистика еще не загружена')
                    ->description('Для этой кампании пока нет статистики.')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->iconColor('warning')
                    ->visible(fn () => $this->record->statistic->isEmpty())
                    ->columnSpanFull(),

                Section::make('Статистика')
                    ->schema([
                        TextEntry::make('statistics')
                            ->label('')
                            ->view('filament.resources.email-marketing.email-campaigns.components.statistics', ['record' => $this->record]),
                    ])
                    ->columnSpanFull()
                    ->visible(fn () => $this->record->statistic->isNotEmpty())
                    ->collapsible(),

                Section::make('Основная информация')
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Название'),

                        TextEntry::make('subject')
                            ->label('Тема письма'),

                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'draft' => 'gray',
                                'scheduled' => 'warning',
                                'sent' => 'success',
                                'sending' => 'info',
                                default => 'gray',
                            }),

                        TextEntry::make('scheduled_at')
                            ->label('Запланировано')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('sent_at')
                            ->label('Отправлено')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('recipientList.name')
                            ->label('Список получателей'),

                        TextEntry::make('emailTemplate.name')
                            ->label('Шаблон письма'),

                        TextEntry::make('creator.name')
                            ->label('Создал'),
                    ])
                    ->columns(2),

                Section::make('Содержание письма')
                    ->schema([
                        TextEntry::make('html_content')
                            ->label('')
                            ->html()
                            ->view('filament.resources.email-marketing.email-campaigns.components.email-preview'),
                    ])
                    ->collapsible(),

                Section::make('Получатели')
                    ->schema([
                        TextEntry::make('recipients_summary')
                            ->label('')
                            ->view('filament.resources.email-marketing.email-campaigns.components.recipients-summary', ['record' => $this->record]),
                    ])
                    ->collapsible(),
            ]);
    }
}
