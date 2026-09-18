<?php

namespace App\Filament\Resources\WorkComments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class WorkCommentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('Пользователь')
                    ->formatStateUsing(fn ($state, Model $record): string => $record->user?->getUserFullName() ?? (string) $state),
                TextEntry::make('work.title')
                    ->label('Произведение'),
                TextEntry::make('created_at')
                    ->label('Дата'),
                TextEntry::make('text')
                    ->label('Комментарий')
                    ->html()
                    ->state(fn (Model $record): string => nl2br(e($record->text)))
                    ->columnSpanFull(),
            ]);
    }
}
