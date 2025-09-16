<?php

namespace App\Filament\Resources\Collection\Participations\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class ParticipationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()->tabs([
                    Tab::make('Основное')->schema([
                        Grid::make()->schema([
                            TextInput::make('author_name')
                                ->label('Имя в сборнике')
                                ->required()
                                ->maxLength(255),
                            Select::make('collection_id')
                                ->label('Сборник')
                                ->relationship(name: 'collection', titleAttribute: 'title'),
                            Select::make('participation_status_id')
                                ->label('Статус')
                                ->relationship(name: 'participationStatus', titleAttribute: 'name'),
                        ])->columns(3),
                        Grid::make()->schema([
                            TextInput::make('price_part')
                                ->label('Цена участия')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->required()
                                ->numeric(),
                            TextInput::make('price_print')
                                ->label('Цена печати')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                            TextInput::make('price_check')
                                ->label('Цена проверки')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                            TextInput::make('price_send')
                                ->label('Цена отправки')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                            TextInput::make('price_total')
                                ->label('Итого')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->required()
                                ->numeric(),
                        ])->columns(5),
                        Grid::make()->schema([
                            Select::make('promocode_id')
                                ->label('Промокод')
                                ->relationship(name: 'promocode', titleAttribute: 'name'),
                            TextInput::make('works_number')
                                ->label('Произведений')
                                ->required()
                                ->disabled()
                                ->numeric(),
                            TextInput::make('rows')
                                ->disabled()
                                ->numeric(),
                            TextInput::make('pages')
                                ->disabled()
                                ->required()
                                ->numeric(),
                        ])->columns(4),
                    ]),
                    Tab::make('Печать')->schema([
                        Placeholder::make('print_order_id')
                            ->label('Печать')
                    ]),
                    Tab::make('Чат')->schema([
                        Livewire::make('components.account.chat', ['chat' => $schema->getRecord()->chat])
                    ])
                ])->columnSpanFull(),
            ]);
    }
}
