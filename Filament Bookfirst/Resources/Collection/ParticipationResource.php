<?php

namespace App\Filament\Resources\Collection;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Livewire;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Collection\ParticipationResource\Pages\ListParticipations;
use App\Filament\Resources\Collection\ParticipationResource\Pages\CreateParticipation;
use App\Filament\Resources\Collection\ParticipationResource\Pages\EditParticipation;
use App\Filament\Resources\Collection\ParticipationResource\Pages;
use App\Filament\Resources\Collection\ParticipationResource\RelationManagers;
use App\Models\Collection\Participation;
use Filament\Forms;
use Filament\Forms\Components\Select;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Участия';
    protected static string | \UnitEnum | null $navigationGroup = 'Сборники';

    public static function form(Schema $schema): Schema
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
                                ->relationship(name: 'collection', titleAttribute: 'name'),
                            Select::make('participation_status_id')
                                ->label('Статус')
                                ->relationship(name: 'participationStatus', titleAttribute: 'name'),
                        ])->columns(3),
                        Grid::make()->schema([
                            TextInput::make('price_part')
                                ->label('Цена участия')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->required()
                                ->numeric(),
                            TextInput::make('price_print')
                                ->label('Цена печати')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                            TextInput::make('price_check')
                                ->label('Цена проверки')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                            TextInput::make('price_send')
                                ->label('Цена отправки')
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
                    ]),
                    Tab::make('Печать')->schema([
                        Placeholder::make('print_order_id')
                            ->label('Печать')
                    ]),
                    Tab::make('Чат')->schema([
                        Livewire::make('components.account.chat', ['chat' => $schema->getRecord()->chat])
                    ])
                ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('collection.name')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListParticipations::route('/'),
            'create' => CreateParticipation::route('/create'),
            'edit' => EditParticipation::route('/{record}/edit'),
        ];
    }
}
