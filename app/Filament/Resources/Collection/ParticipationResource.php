<?php

namespace App\Filament\Resources\Collection;

use App\Filament\Resources\Collection\ParticipationResource\Pages;
use App\Filament\Resources\Collection\ParticipationResource\RelationManagers;
use App\Models\Collection\Participation;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipationResource extends Resource
{
    protected static ?string $model = Participation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Участия';
    protected static ?string $navigationGroup = 'Сборники';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()->tabs([
                    Forms\Components\Tabs\Tab::make('Основное')->schema([
                        Grid::make()->schema([
                            Forms\Components\TextInput::make('author_name')
                                ->label('Имя в сборнике')
                                ->required()
                                ->maxLength(255),
                            Select::make('collection_id')
                                ->label('Сборник')
                                ->relationship(name: 'collection', titleAttribute: 'name'),
                            Select::make('participation_status_id')
                                ->label('Статус')
                                ->relationship(name: 'participationStatus', titleAttribute: 'name'),
                        ])->columns(3),
                        Grid::make()->schema([
                            Forms\Components\TextInput::make('price_part')
                                ->label('Цена участия')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('price_print')
                                ->label('Цена печати')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                            Forms\Components\TextInput::make('price_check')
                                ->label('Цена проверки')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                            Forms\Components\TextInput::make('price_send')
                                ->label('Цена отправки')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                            Forms\Components\TextInput::make('price_total')
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
                            Forms\Components\TextInput::make('works_number')
                                ->label('Произведений')
                                ->required()
                                ->disabled()
                                ->numeric(),
                            Forms\Components\TextInput::make('rows')
                                ->disabled()
                                ->numeric(),
                            Forms\Components\TextInput::make('pages')
                                ->disabled()
                                ->required()
                                ->numeric(),
                        ])->columns(4),
                    ]),
                    Tab::make('Печать')->schema([
                        Forms\Components\Placeholder::make('print_order_id')
                            ->label('Печать')
                    ]),
                    Tab::make('Чат')->schema([
                        Livewire::make('components.account.chat', ['chat' => $form->getRecord()->chat])
                    ])
                ])->columnSpanFull(),

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
