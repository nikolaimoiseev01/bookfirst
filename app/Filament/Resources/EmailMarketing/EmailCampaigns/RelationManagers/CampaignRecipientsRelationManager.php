<?php

namespace App\Filament\Resources\EmailMarketing\EmailCampaigns\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CampaignRecipientsRelationManager extends RelationManager
{
    protected static string $relationship = 'campaignRecipients';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('recipient.email')
            ->columns([
                TextColumn::make('recipient.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('recipient.name')
                    ->label('Имя')
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'pending' => 'gray',
                        'sent' => 'info',
                        'opened' => 'success',
                        'clicked' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('sent_at')
                    ->label('Отправлено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('opened_at')
                    ->label('Открыто')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('clicked_at')
                    ->label('Кликнуто')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
