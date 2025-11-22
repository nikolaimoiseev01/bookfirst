<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Tables;

use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Models\Collection\Collection;
use App\Models\OwnBook\OwnBook;
use App\Models\Work\Work;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class OwnBooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Grid::make()->schema([
                    Split::make([
                        ImageColumn::make('cover')
                            ->width(150)
                            ->height(210)
                            ->getStateUsing(function (Model $record) {
                                $cover = $record->getFirstMediaUrl('cover_front');
                                return $cover ?: url('/fixed/cover_wip.png');
                            }),
                        Stack::make([
                            Stack::make([
                                TextColumn::make('title')
                                    ->formatStateUsing(fn(string $state): HtmlString =>  new HtmlString("<h1 class='text-2xl'>" . e(Str::limit($state, 15)) . "</h1>"))
                                    ->searchable(),
                                TextColumn::make('author')
                                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("<h1 class='text-base'>{$state}</h1>"))
                                    ->searchable(),
                            ]),
                            Stack::make([
                                TextColumn::make('status_general')
                                    ->badge()
                                    ->formatStateUsing(function(OwnBookStatusEnums $state): HtmlString {
                                        return new HtmlString("<h1 class='text-sm'>Статус: {$state->value}</h1>");
                                    })
                                    ->color(fn($state): string => match ($state) {
                                        OwnBookStatusEnums::REVIEW, OwnBookStatusEnums::WORK_IN_PROGRESS => 'warning',
                                        OwnBookStatusEnums::PAYMENT_REQUIRED, OwnBookStatusEnums::PRINT_PAYMENT_REQUIRED, OwnBookStatusEnums::PRINTING, OwnBookStatusEnums::NOT_ACTUAL => 'gray',
                                        OwnBookStatusEnums::PRINT_WAITING => 'danger',
                                        OwnBookStatusEnums::DONE => 'success',
                                        default => 'secondary',
                                    }),
                                TextColumn::make('status_inside')
                                    ->badge()
                                    ->formatStateUsing(fn(OwnBookInsideStatusEnums $state): HtmlString => new HtmlString("<h1 class='text-sm'>ВБ: {$state->value}</h1>"))
                                    ->color(fn(OwnBookInsideStatusEnums $state): string => match ($state) {
                                        OwnBookInsideStatusEnums::PREVIEW, OwnBookInsideStatusEnums::READY_FROM_AUTHOR, OwnBookInsideStatusEnums::WAITING_FOR_AUTHOR_IN_CHAT => 'gray',
                                        OwnBookInsideStatusEnums::DEVELOPMENT, OwnBookInsideStatusEnums::CORRECTIONS => 'danger',
                                        OwnBookInsideStatusEnums::READY_FOR_PUBLICATION => 'success',
                                        default => 'secondary',
                                    }),
                                TextColumn::make('status_cover')
                                    ->badge()
                                    ->formatStateUsing(fn(OwnBookCoverStatusEnums $state): HtmlString => new HtmlString("<h1 class='text-sm'>Обложка: {$state->value}</h1>"))
                                    ->color(fn(OwnBookCoverStatusEnums $state): string => match ($state) {
                                        OwnBookCoverStatusEnums::PREVIEW, OwnBookCoverStatusEnums::READY_FROM_AUTHOR, OwnBookCoverStatusEnums::WAITING_FOR_AUTHOR_IN_CHAT => 'gray',
                                        OwnBookCoverStatusEnums::DEVELOPMENT, OwnBookCoverStatusEnums::CORRECTIONS => 'danger',
                                        OwnBookCoverStatusEnums::READY_FOR_PUBLICATION => 'success',
                                        default => 'secondary',
                                    }),
                            ])->extraAttributes(['class' => 'gap-2']),

                            Split::make([
                                TextColumn::make('price_total')
                                    ->icon('heroicon-o-banknotes')
                                    ->size(TextSize::Large)
                                    ->extraAttributes(['class' => 'flex gap-2 text-nowrap items-center'])
                                    ->formatStateUsing(fn(string $state) => makeMoney($state, 0, true))
                                    ->tooltip('Выручка'),
                                TextColumn::make('books_cnt')
                                    ->getStateUsing(function (OwnBook $record) {
                                        $books_cnt = optional($record->firstPrintOrder())->books_cnt;
                                        return $books_cnt;
                                    })
                                    ->extraAttributes(['class' => 'flex gap-2 text-nowrap items-center'])
                                    ->icon('heroicon-o-book-open')
                                    ->tooltip(function(Model $record) {
                                        $cover = optional($record->firstPrintOrder())->cover_type;
                                        $inside = optional($record->firstPrintOrder())->inside_color;
                                        return "Обложка: {$cover}, ВБ: {$inside}";
                                    })
                                    ->size(TextSize::Large)
                            ])->extraAttributes(['class' => 'gap-8'])
                        ])
                            ->extraAttributes(['class' => 'gap-4'])
                    ])
                ])
            ])
            ->contentGrid([
                'xl' => 2,
                '2xl' => 3
            ])
            ->filters([
                SelectFilter::make('status_general')
                    ->label('Общий статус')
                    ->options([
                        collect(OwnBookStatusEnums::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->value])
                            ->toArray()
                    ])
                    ->multiple()
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
//                EditAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
