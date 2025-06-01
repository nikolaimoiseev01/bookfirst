<?php

namespace App\Filament\Resources\Collection;

use App\Filament\Resources\Collection\ParticipationResource\Pages;
use App\Filament\Resources\Collection\ParticipationResource\RelationManagers;
use App\Models\Collection\Participation;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipationResource extends Resource
{
    protected static ?string $model = Participation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()->tabs([
                    Forms\Components\Tabs\Tab::make('Основное')->schema([
                        Select::make('collection_id')
                            ->label('Сборник')
                            ->relationship(name: 'collection', titleAttribute: 'name'),
                    ])
                ])->columnSpanFull(),

                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('author_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('works_number')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('rows')
                    ->numeric(),
                Forms\Components\TextInput::make('pages')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('participation_status_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('print_order_id')
                    ->numeric(),
                Forms\Components\TextInput::make('promocode_id')
                    ->numeric(),
                Forms\Components\TextInput::make('price_part')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('price_print')
                    ->numeric(),
                Forms\Components\TextInput::make('price_check')
                    ->numeric(),
                Forms\Components\TextInput::make('price_send')
                    ->numeric(),
                Forms\Components\TextInput::make('price_total')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('collection.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('works_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rows')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pages')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('participation_status_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('print_order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('promocode_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_part')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_print')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_check')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_send')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListParticipations::route('/'),
            'create' => Pages\CreateParticipation::route('/create'),
            'edit' => Pages\EditParticipation::route('/{record}/edit'),
        ];
    }
}
