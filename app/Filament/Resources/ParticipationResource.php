<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParticipationResource\Pages;
use App\Filament\Resources\ParticipationResource\RelationManagers;
use App\Models\Participation;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipationResource extends Resource
{
    protected static ?string $model = Participation::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Custom Page Title';




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)->schema([
                    Tabs::make('Общая информация')
                        ->tabs([
                            Tabs\Tab::make('Общая информация')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('surname')
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('nickname')
                                        ->maxLength(255),
                                ]),
                            Tabs\Tab::make('Произведения')
                                ->schema([
                                    // ...
                                ]),
                            Tabs\Tab::make('Label 3')
                                ->schema([
                                    // ...
                                ]),
                        ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('collection_id'),
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('surname'),
                Tables\Columns\TextColumn::make('nickname'),
                Tables\Columns\TextColumn::make('works_number'),
                Tables\Columns\TextColumn::make('rows'),
                Tables\Columns\TextColumn::make('pages'),
                Tables\Columns\TextColumn::make('pat_status_id'),
                Tables\Columns\TextColumn::make('promocode'),
                Tables\Columns\TextColumn::make('part_price'),
                Tables\Columns\TextColumn::make('print_price'),
                Tables\Columns\TextColumn::make('check_price'),
                Tables\Columns\TextColumn::make('send_price'),
                Tables\Columns\TextColumn::make('total_price'),
                Tables\Columns\TextColumn::make('file'),
                Tables\Columns\TextColumn::make('printorder_id'),
                Tables\Columns\TextColumn::make('chat_id'),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('comment'),
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
            'index' => Pages\ListParticipations::route('/'),
            'view' => Pages\ViewParticipation::route('/{record}/view'),
            'create' => Pages\CreateParticipation::route('/create'),
            'edit' => Pages\EditParticipation::route('/{record}/edit'),
            'custom' => Pages\ParticipationPage::route('/{record}/part_page'),
        ];
    }
}
