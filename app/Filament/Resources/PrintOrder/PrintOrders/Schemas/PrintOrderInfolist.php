<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PrintOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('print_order_status_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('model_type')
                    ->placeholder('-'),
                TextEntry::make('model_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('books_cnt')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('inside_color')
                    ->placeholder('-'),
                TextEntry::make('pages_color')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('cover_type')
                    ->placeholder('-'),
                TextEntry::make('price_print')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('price_send')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('receiver_name')
                    ->placeholder('-'),
                TextEntry::make('receiver_telephone')
                    ->placeholder('-'),
                TextEntry::make('country')
                    ->placeholder('-'),
                TextEntry::make('address_type_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('paid_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('track_number')
                    ->placeholder('-'),
                TextEntry::make('logistic_company_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('printing_company_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
