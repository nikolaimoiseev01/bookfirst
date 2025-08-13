<?php

namespace App\Filament\Resources\Collection\Participations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ParticipationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('collection_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('author_name')
                    ->searchable(),
                TextColumn::make('works_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rows')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pages')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('participation_status_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('print_order_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('promocode_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_part')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_print')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_check')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_send')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
