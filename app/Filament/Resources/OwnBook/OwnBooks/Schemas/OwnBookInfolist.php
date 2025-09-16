<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OwnBookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('author'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('own_book_status_id')
                    ->numeric(),
                TextEntry::make('own_book_cover_status_id')
                    ->numeric(),
                TextEntry::make('own_book_inside_status_id')
                    ->numeric(),
                TextEntry::make('deadline_inside')
                    ->date(),
                TextEntry::make('deadline_cover')
                    ->date(),
                TextEntry::make('pages')
                    ->numeric(),
                TextEntry::make('inside_type'),
                TextEntry::make('internal_promo_type')
                    ->numeric(),
                TextEntry::make('price_text_design')
                    ->numeric(),
                TextEntry::make('price_text_check')
                    ->numeric(),
                TextEntry::make('price_cover')
                    ->numeric(),
                TextEntry::make('price_print')
                    ->numeric(),
                TextEntry::make('price_promo')
                    ->numeric(),
                TextEntry::make('price_total')
                    ->numeric(),
                TextEntry::make('paid_at_without_print')
                    ->dateTime(),
                TextEntry::make('paid_at_print_only')
                    ->dateTime(),
                TextEntry::make('old_author_email'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
