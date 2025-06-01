<?php

namespace App\Filament\Resources\Collection\CollectionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipationsRelationManager extends RelationManager
{
    protected static string $relationship = 'participations';

    protected static ?string $title = 'Участники';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('author_name')
                ->label('Автор'),
                Tables\Columns\TextColumn::make('pages')
                    ->label('Страниц'),
                Tables\Columns\TextColumn::make('printOrder.books_cnt')
                    ->label('Печатных экземпляров'),
                TextColumn::make('participationStatus.name')
                    ->badge()
                    ->label('Статус участия')
                    ->color(fn(string $state): string => match ($state) {
                        'Ожидается подтверждение заявки' => 'warning',
                        'Заявка подтверждена, ожидается оплата' => 'primary',
                        'Участие подтверждено' => 'success',
                        'Заявка неактуальна' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('price_part')
                    ->label('Стоимость участия'),
                Tables\Columns\TextColumn::make('price_print')
                    ->label('Стоимость печати'),
                Tables\Columns\TextColumn::make('price_check')
                    ->label('Стоимость проверки'),
                Tables\Columns\TextColumn::make('price_total')
                    ->label('Общая стоимость'),
            ])
            ->defaultSort('participation_status_id')
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->heading('')
            ->actions([
            ])
            ->defaultPaginationPageOption(50)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
