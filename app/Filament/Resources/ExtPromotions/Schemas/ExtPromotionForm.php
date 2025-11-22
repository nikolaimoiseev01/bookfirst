<?php

namespace App\Filament\Resources\ExtPromotions\Schemas;

use App\Enums\ExtPromotionStatusEnums;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ExtPromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()->schema([
                    Tab::make('Основное')->schema([
                        Select::make('status')
                            ->label('Статус')
                            ->visibleOn('edit')
                            ->options(
                                collect(ExtPromotionStatusEnums::cases())
                                    ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                    ->toArray()
                            ),
                        TextEntry::make('userName')->getStateUsing(fn($record)=>$record->user->getUserFullName()),
                        TextEntry::make('days'),
                        TextEntry::make('login'),
                        TextEntry::make('password'),
                        TextEntry::make('promocode.name'),
                        TextEntry::make('price_executor'),
                        TextEntry::make('price_our'),
                        TextEntry::make('price_total')
                    ]),
                    Tab::make('Чат')->schema([
                        Livewire::make('components.account.chat', ['chat' => $schema->getRecord()->chat])->columnSpanFull()
                    ])
                ])->columnSpanFull()->columns(4)
            ]);
    }
}
