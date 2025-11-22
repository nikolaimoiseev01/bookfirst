<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OwnBookResource\Pages;
use App\Filament\Resources\OwnBookResource\RelationManagers;
use App\Models\own_book;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OwnBookResource extends Resource
{
    protected static ?string $model = own_book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOwnBooks::route('/'),
            'create' => Pages\CreateOwnBook::route('/create'),
            'edit' => Pages\EditOwnBook::route('/{record}/edit'),
        ];
    }
}
