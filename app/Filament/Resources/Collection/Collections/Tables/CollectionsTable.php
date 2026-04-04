<?php

namespace App\Filament\Resources\Collection\Collections\Tables;

use App\Enums\CollectionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Work\Work;
use Filament\Actions\Action;
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
                            TextColumn::make('status')
                                ->badge()
                                ->color(fn($state): string => match ($state) {
                                    CollectionStatusEnums::APPS_IN_PROGRESS => 'primary',
                                    CollectionStatusEnums::PREVIEW, CollectionStatusEnums::PRINT_PREPARE => 'warning',
                                    CollectionStatusEnums::PRINTING => 'danger',
                                    CollectionStatusEnums::DONE => 'success',
                                }),
                            Split::make([
                                TextColumn::make('participations_count')
                                    ->icon('heroicon-o-user-group')
                                    ->tooltip('Участников')
                                    ->size(TextSize::Large)
                                    ->counts([
                                        'participations' => function(Builder $query) {
                                             return $query->where('status', ParticipationStatusEnums::APPROVED);
                                        } ,
                                    ]),
                                TextColumn::make('participations_sum_price_total')
                                    ->icon('heroicon-o-banknotes')
                                    ->size(TextSize::Large)
                                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("{$state}"))
                                    ->getStateUsing(function(Collection $collection) {
                                        $paricipations = Participation::query()
                                            ->where('collection_id', $collection->id)
                                            ->where('status', ParticipationStatusEnums::APPROVED)
                                            ->get();
                                        $printSum = 0;

                                        foreach ($paricipations as $participation) {
                                            $printSum += $participation->printOrder?->price_print ?? 0;
                                        }

                                        return $paricipations->sum('price_total') + $printSum;
                                    })
                                    ->tooltip(function(Collection $collection) {
                                        $participations = Participation::query()
                                            ->where('collection_id', $collection->id)
                                            ->where('status', ParticipationStatusEnums::APPROVED)
                                            ->get();
                                        $printSum = 0;
                                        $printCount = 0;
                                        foreach ($participations as $participation) {
                                            $printSum += $participation->printOrder?->price_print ?? 0;
                                            $printCount += $participation->printOrder?->books_cnt ?? 0;
                                        }

                                        $partSum = $participations->sum('price_total');

                                        return new HtmlString("Участия: $partSum руб. <br>
                                                Печать: $printSum руб. ($printCount шт.)
                                        ");
                                    }),
                                TextColumn::make('getTotalWorkPagesAttribute')
                                    ->icon('heroicon-o-document-duplicate')
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
                SelectFilter::make('status')
                    ->label('Статус')
                    ->multiple()
            ])
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
