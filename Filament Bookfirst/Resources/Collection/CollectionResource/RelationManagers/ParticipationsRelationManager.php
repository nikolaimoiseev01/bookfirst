<?php

namespace App\Filament\Resources\Collection\CollectionResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use App\Filament\Resources\Collection\ParticipationResource;
use App\Filament\Resources\TrackResource;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipationsRelationManager extends RelationManager
{
    protected static string $relationship = 'participations';

    protected static ?string $title = 'Участники';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('author_name')
                ->label('Автор'),
                TextColumn::make('pages')
                    ->label('Страниц'),
                TextColumn::make('printOrder.books_cnt')
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
                TextColumn::make('price_part')
                    ->label('Стоимость участия'),
                TextColumn::make('price_print')
                    ->label('Стоимость печати'),
                TextColumn::make('price_check')
                    ->label('Стоимость проверки'),
                TextColumn::make('price_total')
                    ->label('Общая стоимость'),
            ])
            ->defaultSort('participation_status_id')
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (Model $record): string => ParticipationResource::getUrl('edit', ['record' => $record->id]))
                    ->openUrlInNewTab()
            ])
            ->headerActions([
            ])
            ->heading('')
            ->defaultPaginationPageOption(50)
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
