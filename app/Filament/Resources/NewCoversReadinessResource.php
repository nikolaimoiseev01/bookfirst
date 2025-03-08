<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewCoversReadinessResource\Pages;
use App\Filament\Resources\NewCoversReadinessResource\RelationManagers;
use App\Models\New_covers_readiness;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewCoversReadinessResource extends Resource
{
    protected static ?string $model = New_covers_readiness::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Настройки';

    protected static ?string $navigationLabel = 'Готовность обложек';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('flg_ready')
                    ->label('Статус')
                    ->options([
                        'Новые обложки готовы' => 'Новые обложки готовы',
                        'Ждем новых обложек' => 'Ждем новых обложек',
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('flg_ready')->label('Статус'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNewCoversReadinesses::route('/'),
        ];
    }
}
