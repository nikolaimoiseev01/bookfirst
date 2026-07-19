<?php

namespace App\Filament\Resources\EmailMarketing\EmailRecipientLists\RelationManagers;

use App\Models\EmailMarketing\Recipient;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmailRecipientsRelationManager extends RelationManager
{
    protected static string $relationship = 'recipients';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('email')
            ->columns([
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Имя')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Добавлен')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
