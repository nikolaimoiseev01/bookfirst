<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Tables;

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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class OwnBooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Grid::make()->schema([
                    Split::make([
//                        SpatieMediaLibraryImageColumn::make('cover')
//                            ->width(150)
//                            ->height(210)
//                            ->collection('cover_front'),
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
                                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("<h1 class='text-2xl'>{$state}</h1>"))
                                    ->searchable(),
                                TextColumn::make('author')
                                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("<h1 class='text-base'>{$state}</h1>"))
                                    ->searchable(),
                            ]),
                            Stack::make([
                                TextColumn::make('ownBookStatus.name')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("<h1 class='text-sm'>Статус: {$state}</h1>"))
                                    ->color(fn(string $state): string => match ($state) {
                                        'рассмотрение заявки', 'идёт работа с файлами' => 'warning',
                                        'необходима оплата (кроме печати)', 'необходима оплата печати', 'идеть печать книги', 'Подготовка к печати', 'неактуально' => 'gray',
                                        'печать оплачена, скоро начнется' => 'danger',
                                        'процесс завершен' => 'success',
                                        default => 'secondary',
                                    }),
                                TextColumn::make('ownBookInsideStatus.name')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("<h1 class='text-sm'>ВБ: {$state}</h1>"))
                                    ->color(fn(string $state): string => match ($state) {
                                        'ожидание автора в чате' => 'warning',
                                        'на проверке автором', 'готов от автора' => 'gray',
                                        'в разработке', 'внесение исправлений' => 'danger',
                                        'готов к изданию' => 'success',
                                        default => 'secondary',
                                    }),
                                TextColumn::make('ownBookCoverStatus.name')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("<h1 class='text-sm'>Обложка: {$state}</h1>"))
                                    ->color(fn(string $state): string => match ($state) {
                                        'ожидание автора в чате' => 'warning',
                                        'на проверке автором', 'готова от автора' => 'gray',
                                        'в разработке', 'внесение исправлений' => 'danger',
                                        'готова к изданию' => 'success',
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
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
