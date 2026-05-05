<?php

namespace App\Filament\Resources\Works\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
class WorkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                    Select::make('user_id')
                        ->label('Пользователь')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),

                    TextInput::make('title')
                        ->label('Название')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('text')
                        ->label('Текст')
                        ->rows(6)
                        ->columnSpanFull()
                        ->required(),

                    TextInput::make('symbols')
                        ->label('Символы')
                        ->numeric()
                        ->required(),

                    TextInput::make('rows')
                        ->label('Строки')
                        ->numeric()
                        ->required(),

                    TextInput::make('pages')
                        ->label('Страницы')
                        ->numeric()
                        ->required(),

                    Select::make('work_type_id')
                        ->label('Тип работы')
                        ->relationship(
                            name: 'workType',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query->orderBy('created_at', 'desc')
                        ),

                    Select::make('work_topic_id')
                        ->label('Тема работы')
                        ->relationship(
                            name: 'workTopic',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query->orderBy('created_at', 'desc')
                        ),

                    Select::make('upload_type')
                        ->label('Тип загрузки')
                        ->options([
                            'file' => 'Файл',
                            'text' => 'Текст',
                            'link' => 'Ссылка',
                        ])
                        ->nullable(),
                ])
            ]);
    }
}
