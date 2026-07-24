<?php

namespace App\Filament\Resources\EmailMarketing\EmailRecipientLists\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EmailRecipientListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make()->schema([
                    TextInput::make('name')
                        ->label('Название списка')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('utm_campaign')
                        ->label('UTM Campaign')
                        ->required()
                        ->maxLength(255),
                    Select::make('promocode_id')
                        ->label('Промокод')
                        ->relationship('promocode', 'name')
                        ->preload()
                        ->searchable()
                        ->nullable(),

                    Textarea::make('description')
                        ->label('Описание')
                        ->rows(3)
                        ->nullable(),
                ]),
                Grid::make()->schema([
                    FileUpload::make('csv_file')
                        ->label('Загрузить CSV файл')
                        ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', 'text/plain'])
                        ->helperText('CSV формат: email,name (одна запись на строку)')
                        ->reactive()
                        ->disk('public')
                        ->directory('csv-uploads')
                        ->dehydrated(false)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($state) {
                                // Process CSV file and add recipients
                                // This will be handled in the model's boot method or a custom action
                            }
                        }),
                    Textarea::make('manual_recipients')
                        ->label('Или добавить вручную')
                        ->rows(5)
                        ->helperText('Один email адрес на строку')
                        ->dehydrated(false)
                        ->nullable(),
                ]),
            ]);
    }
}
