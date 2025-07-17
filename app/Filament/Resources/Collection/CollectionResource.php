<?php

namespace App\Filament\Resources\Collection;

use App\Filament\Resources\Collection\CollectionResource\Pages;
use App\Filament\Resources\Collection\CollectionResource\RelationManagers;
use App\Filament\Resources\Collection\CollectionResource\RelationManagers\ParticipationsRelationManager;
use App\Filament\Resources\Collection\CollectionResource\RelationManagers\PreviewCommentsRelationManager;
use App\Livewire\Components\Filament\Collection\Votes;
use App\Models\Collection\Collection;
use App\Models\Work\Work;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid as GridForm;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Сборники';
    protected static ?string $navigationGroup = 'Сборники';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Общее')
                            ->schema([
                                GridForm::make()->schema([
                                    Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                                        ->label('Обложка')
                                        ->collection('cover_2d')->columnSpan(1),
                                    GridForm::make()->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Название')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('collection_status_id')
                                            ->label('Статус')
                                            ->relationship(name: 'collectionStatus', titleAttribute: 'name'),
                                        GridForm::make()->schema([
                                            Forms\Components\TextInput::make('slug')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('name_short')
                                                ->label('Краткое название')
                                                ->required()
                                                ->columnSpan(1)
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('pages')
                                                ->label('Страниц')
                                                ->columnSpan(1)
                                                ->numeric(),
                                        ])->columns(3),
                                        Forms\Components\Textarea::make('description')
                                            ->columnSpanFull(),
                                    ])->columns(2)->columnSpan(2),
                                    GridForm::make()->schema([
                                        Forms\Components\DatePicker::make('date_apps_end'),
                                        Forms\Components\DatePicker::make('date_preview'),
                                        Forms\Components\DatePicker::make('date_voting_end'),
                                        Forms\Components\DatePicker::make('date_print_start'),
                                        Forms\Components\DatePicker::make('date_print_end'),
                                    ])->columns(2)->columnSpan(1)
                                ])->columns(4),
                            ]),
                        Tabs\Tab::make('Победители')
                            ->schema([
                                GridForm::make()->schema([
                                    Forms\Components\Repeater::make('winners')
                                        ->label('Победители')
                                        ->simple(
                                            Select::make('participation_id')
                                                ->label('Пользователь')
                                                ->options(fn(Collection $collection) => $collection->participations()->pluck('author_name', 'id')->toArray())
                                                ->required(),
                                        )
                                        ->columnSpan(1)
                                        ->mutateDehydratedStateUsing(function (array $state): array {
                                            $intConverted = array_values(array_map(fn($item) => (int)$item['user_id'], $state));
                                            return $intConverted;
                                        }),
                                    ViewField::make('rating')
                                        ->view('filament.components.collection-votes')
                                        ->viewData(function(Collection $collection) {
                                            $collection = $collection->load('collectionVotes');
                                            $candidates = DB::table('collection_votes')
                                                ->select(DB::raw('count(*) as votes_count, participations.author_name'))
                                                ->join('participations', 'participations.id', '=', 'collection_votes.participation_id_to')
                                                ->where('collection_votes.collection_id', $collection->id)
                                                ->groupBy('participations.author_name')
                                                ->orderBy('votes_count', 'desc')
                                                ->get();
//                                            dd($candidates);
                                            return [
                                                'collection' => $collection,
                                                'candidates' => $candidates
                                            ];
                                        })
                                ])->columns(3)
                            ])
                    ])->columnSpanFull()

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
            ParticipationsRelationManager::class,
            PreviewCommentsRelationManager::class
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
