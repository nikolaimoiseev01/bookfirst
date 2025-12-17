<?php

namespace App\Filament\Resources\Collection\Participations\Schemas;

use App\Enums\ParticipationStatusEnums;
use App\Enums\TransactionStatusEnums;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
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
                            Select::make('status')
                                ->label('Статус')
                                ->options(
                                    collect(ParticipationStatusEnums::cases())
                                        ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                        ->toArray()
                                )
                        ])->columns(3),
                        Grid::make()->schema([
                            TextInput::make('price_part')
                                ->label('Цена участия')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->required()
                                ->numeric(),
                            TextInput::make('price_check')
                                ->label('Цена проверки')
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
                        Section::make('Произведения в заявке')->schema([
                            RepeatableEntry::make('participationWorks')
                                ->label('')
                                ->schema([
                                    TextEntry::make('work.title'),
                                    TextEntry::make('work.text')->formatStateUsing(fn (?string $state) => nl2br(e($state)))
                                        ->html(),
                                ])
                                ->grid(2)
                        ])->collapsed(),
                        Section::make('Транзакции в заявке')->schema([
                            RepeatableEntry::make('transactions')
                                ->url('/fixed')
                                ->label('')
                                ->schema([
                                    Grid::make()->schema([
                                        TextEntry::make('created_at')->date(),
                                        IconEntry::make('status')
                                            ->color(fn(string $state): string => match ($state) {
                                                TransactionStatusEnums::CREATED->value => 'info',
                                                TransactionStatusEnums::CONFIRMED->value => 'success',
                                                default => 'gray',
                                            })
                                            ->icon(fn(string $state): string => match ($state) {
                                                TransactionStatusEnums::CREATED->value => 'heroicon-o-clock',
                                                TransactionStatusEnums::CONFIRMED->value => 'heroicon-o-check-circle',
                                            }),
                                        TextEntry::make('amount'),
                                        TextEntry::make('payment_method'),
                                        TextEntry::make('yoo_id'),
                                    ])->columns(5)
                                ])
                        ])->collapsed()
                    ]),
                    Tab::make('Печать')->schema([
                        Grid::make()->schema([
                            TextEntry::make('printOrder.price_print')
                                ->label('Цена печати')
                                ->numeric(),
                            TextEntry::make('printOrder.price_send')
                                ->label('Цена отправки')
                                ->numeric(),
                            TextEntry::make('printOrder.books_cnt')
                                ->label('Экземпляров')
                                ->numeric(),
                            TextEntry::make('printOrder.address_json')
                                ->state(fn(\App\Models\Collection\Participation $record) => $record->printOrder?->address_json['string'] ?? '—'
                                )
                                ->label('Адрес'),
                            TextEntry::make('printOrder.receiver_name')
                                ->label('ФИО')
                                ->numeric(),
                            TextEntry::make('printOrder.receiver_telephone')
                                ->label('Телефон')
                                ->numeric(),
                        ])
                    ]),
                    Tab::make('Чат')->schema([
                        Livewire::make('components.account.chat', ['chat' => $schema->getRecord()->chat])
                    ])
                ])->columnSpanFull(),
            ]);
    }
}
