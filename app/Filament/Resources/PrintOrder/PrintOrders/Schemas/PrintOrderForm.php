<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Schemas;

use App\Enums\PrintOrderStatusEnums;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PrintOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()->schema([
                    Tabs::make()->tabs([
                        Tab::make('Основное')->schema([
                            Section::make()->schema([
                                Select::make('status')
                                    ->label('Статус')
                                    ->options(
                                        collect(PrintOrderStatusEnums::cases())
                                            ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                            ->toArray()
                                    ),
                                TextInput::make('books_cnt')
                                    ->label('Количество')
                                    ->numeric(),
                                TextInput::make('price_print')
                                    ->label('Стоимость печати')
                                    ->numeric(),
                                TextInput::make('price_send')
                                    ->label('Стоимость отправки')
                                    ->numeric(),
                                TextInput::make('track_number')->label('Номер отслеживания'),
                                Select::make('printing_company_id')
                                    ->label('Компания печати')
                                    ->relationship(name: 'printingCompany', titleAttribute: 'name'),
                                Select::make('logistic_company_id')
                                    ->label('Номер отправки')
                                    ->relationship(name: 'logisticCompany', titleAttribute: 'name'),
                                Select::make('inside_color')
                                    ->label('Цветность ВБ')
                                    ->options([
                                        'Цветной' => 'Цветной',
                                        'Черно-белый' => 'Черно-белый'
                                    ]),
                                TextInput::make('pages_color')
                                    ->label('Цветных страниц')
                                    ->numeric()
                                    ->default(0),
                                TextInput::make('cover_type')->label('Тип обложки'),
                                Fieldset::make('Получатель')->schema([
                                    TextEntry::make('receiver_name')->label('ФИО')->columnSpan(1),
                                    TextEntry::make('receiver_telephone')->label('Телефон')->columnSpan(1),
                                    TextEntry::make('country')->label('Страна')->columnSpan(1),
                                    TextEntry::make('address_type')->label('Тип')->columnSpan(1),
                                    TextEntry::make('address_json.string')->label('Подробнее')->columnSpan(4),
                                ])->columnSpanFull()->columns(8)

                            ])->columns(5)
                        ]),
                        Tab::make('Чат')->schema([
                            Livewire::make('components.account.chat', ['chat' => $schema->getRecord()->chat])
                        ])
                    ])->columnSpan(4),
                    Section::make()->schema([
                        TextEntry::make('type')
                            ->label('Тип'),
                        TextEntry::make('created_at')
                            ->date()
                            ->label('Создан'),
                        TextEntry::make('paid_at')
                            ->date()
                            ->label('Оплачен'),
                    ])->columnSpan(1)
                ])->columns(5)->columnSpanFull()
            ]);
    }
}
