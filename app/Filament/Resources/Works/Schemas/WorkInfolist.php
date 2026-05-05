<?php

namespace App\Filament\Resources\Works\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WorkInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('Пользователь'),

                TextEntry::make('title')
                    ->label('Название'),

                TextEntry::make('text')
                    ->label('Текст')
                    ->columnSpanFull(),

                TextEntry::make('symbols')
                    ->label('Символы'),

                TextEntry::make('rows')
                    ->label('Строки'),

                TextEntry::make('pages')
                    ->label('Страницы'),

                TextEntry::make('workType.name')
                    ->label('Тип работы'),

                TextEntry::make('workTopic.name')
                    ->label('Тема')
                    ->placeholder('Не указана'),

                TextEntry::make('upload_type')
                    ->label('Тип загрузки')
                    ->placeholder('Не указан'),
            ]);
    }
}
