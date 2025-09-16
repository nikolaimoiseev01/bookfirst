<?php

namespace App\Filament\Resources\Collection\Collections\Tables;

use App\Models\Collection\Collection;
use App\Models\Work\Work;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class CollectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Grid::make()->schema([
                    Split::make([
                        SpatieMediaLibraryImageColumn::make('cover')
                            ->width(150)
                            ->height(210)
                            ->collection('cover_front'),
                        Stack::make([
                            TextColumn::make('title')
                                ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("<h1 class='text-xl'>{$state}</h1>"))
                                ->extraAttributes(['class' => 'text-3xl'])
                                ->searchable(),
                            TextColumn::make('CollectionStatus.name')
                                ->badge()
                                ->color(fn(string $state): string => match ($state) {
                                    'Идет прием заявок' => 'primary',
                                    'Предварительная проверка', 'Подготовка к печати' => 'warning',
                                    'Идет печать' => 'danger',
                                    'Сборник издан' => 'success',
                                }),
                            Split::make([
                                TextColumn::make('participations_count')
                                    ->icon('heroicon-o-user-group')
                                    ->tooltip('Участников')
                                    ->size(TextSize::Large)
                                    ->counts([
                                        'participations' => fn(Builder $query) => $query->where('participation_status_id', '>', 2),
                                    ]),
                                TextColumn::make('participations_sum_price_total')
                                    ->icon('heroicon-o-banknotes')
                                    ->size(TextSize::Large)
                                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("{$state}Р"))
                                    ->tooltip('Выручка')
                                    ->sum([
                                        'participations' => fn(Builder $query) => $query->where('participation_status_id', '>', 2),
                                    ], 'price_total'),
                                TextColumn::make('getTotalWorkPagesAttribute')
                                    ->icon('heroicon-o-user-group')
                                    ->tooltip('Страниц работ')
                                    ->size(TextSize::Large)
                                    ->getStateUsing(function (Collection $collection): int {
                                        $works = Work::whereIn(
                                            'id',
                                            $collection->participationWorks()->pluck('work_id')
                                        )->sum('pages');
                                        return $works;
                                    })
                            ])
                        ])
                            ->extraAttributes(['class' => 'gap-4'])
                    ])
                ])
            ])
            ->contentGrid([
                'xl' => 2,
                '2xl' => 3
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('collection_status_id')
                    ->label('Статус')
                    ->multiple()
                    ->relationship('CollectionStatus', 'name')
            ])
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
