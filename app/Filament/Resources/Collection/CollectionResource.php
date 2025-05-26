<?php

namespace App\Filament\Resources\Collection;

use App\Filament\Resources\Collection\CollectionResource\Pages;
use App\Filament\Resources\Collection\CollectionResource\RelationManagers;
use App\Models\Collection\Collection;
use App\Models\Work\Work;
use Filament\Forms;
use Filament\Forms\Components\Grid as GridForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Grid as GridTable;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use PHPUnit\Framework\TestSize\Large;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_short')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Select::make('collection_status_id')
                    ->relationship(name: 'CollectionStatus', titleAttribute: 'name'),
                Forms\Components\TextInput::make('pages')
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                GridForm::make()->schema([
                    Forms\Components\DatePicker::make('date_apps_end'),
                    Forms\Components\DatePicker::make('date_preview'),
                    Forms\Components\DatePicker::make('date_voting_end'),
                    Forms\Components\DatePicker::make('date_print_start'),
                    Forms\Components\DatePicker::make('date_print_end'),
                ])->columns(5)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Grid::make()->schema([
                    Tables\Columns\Layout\Split::make([
                        SpatieMediaLibraryImageColumn::make('cover')
                            ->width(150)
                            ->height(210)
                            ->collection('cover_2d'),
                        Stack::make([
                            Tables\Columns\TextColumn::make('name')
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
                                    ->size(TextColumn\TextColumnSize::Large)
                                    ->counts([
                                        'participations' => fn(Builder $query) => $query->where('participation_status_id', '>', 2),
                                    ]),
                                TextColumn::make('participations_sum_price_total')
                                    ->icon('heroicon-o-banknotes')
                                    ->size(TextColumn\TextColumnSize::Large)
                                    ->formatStateUsing(fn(string $state): HtmlString => new HtmlString("{$state}Р"))
                                    ->tooltip('Выручка')
                                    ->sum([
                                        'participations' => fn(Builder $query) => $query->where('participation_status_id', '>', 2),
                                    ], 'price_total'),
                                TextColumn::make('getTotalWorkPagesAttribute')
                                    ->icon('heroicon-o-user-group')
                                    ->tooltip('Страниц работ')
                                    ->size(TextColumn\TextColumnSize::Large)
                                    ->formatStateUsing(function (Collection $collection): int {
                                        return Work::whereIn(
                                            'id',
                                            $collection->participationWorks()->pluck('work_id')
                                        )->sum('pages');
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
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }
}
