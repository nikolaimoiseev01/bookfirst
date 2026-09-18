<?php

namespace App\Filament\Resources\Works\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WorkCommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Комментарии';

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('Пользователь'),
                TextEntry::make('created_at')
                    ->label('Дата'),
                TextEntry::make('text')
                    ->label('Комментарий')
                    ->html()
                    ->state(fn (Model $record): string => nl2br(e($record->text)))
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->formatStateUsing(fn ($state, Model $record): string => $record->user?->getUserFullName() ?? (string) $state)
                    ->searchable(),
                TextColumn::make('text')
                    ->label('Комментарий')
                    ->limit(100)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Комментарии')
            ->badge($ownerRecord->comments()->count());
    }
}
