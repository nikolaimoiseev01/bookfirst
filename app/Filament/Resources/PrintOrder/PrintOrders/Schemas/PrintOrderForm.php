<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PrintOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('print_order_status_id')
                    ->numeric(),
                TextInput::make('model_type'),
                TextInput::make('model_id')
                    ->numeric(),
                TextInput::make('books_cnt')
                    ->numeric(),
                TextInput::make('inside_color'),
                TextInput::make('pages_color')
                    ->numeric(),
                TextInput::make('cover_type'),
                TextInput::make('price_print')
                    ->numeric(),
                TextInput::make('price_send')
                    ->numeric(),
                TextInput::make('receiver_name'),
                TextInput::make('receiver_telephone')
                    ->tel(),
                TextInput::make('country'),
                TextInput::make('address_type_id')
                    ->numeric(),
                TextInput::make('address_json')
                    ->required(),
                DateTimePicker::make('paid_at'),
                TextInput::make('track_number'),
                TextInput::make('logistic_company_id')
                    ->numeric(),
                TextInput::make('printing_company_id')
                    ->numeric(),
            ]);
    }
}
