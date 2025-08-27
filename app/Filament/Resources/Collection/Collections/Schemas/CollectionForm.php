<?php

namespace App\Filament\Resources\Collection\Collections\Schemas;

use App\Models\Collection\Collection;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class CollectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Общее')
                            ->schema([
                                \Filament\Schemas\Components\Grid::make()->schema([
                                    SpatieMediaLibraryFileUpload::make('cover')
                                        ->label('Обложка')
                                        ->collection('cover_2d')->columnSpan(1),
                                    \Filament\Schemas\Components\Grid::make()->schema([
                                        TextInput::make('name')
                                            ->label('Название')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('collection_status_id')
                                            ->label('Статус')
                                            ->relationship(name: 'collectionStatus', titleAttribute: 'name', modifyQueryUsing: fn ($query) => $query->orderBy('id')),
                                        \Filament\Schemas\Components\Grid::make()->schema([
                                            TextInput::make('slug')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('name_short')
                                                ->label('Краткое название')
                                                ->required()
                                                ->columnSpan(1)
                                                ->maxLength(255),
                                            TextInput::make('pages')
                                                ->label('Страниц')
                                                ->columnSpan(1)
                                                ->numeric(),
                                        ])->columns(3),
                                        Textarea::make('description')
                                            ->columnSpanFull(),
                                    ])->columns(2)->columnSpan(2),
                                    \Filament\Schemas\Components\Grid::make()->schema([
                                        DatePicker::make('date_apps_end'),
                                        DatePicker::make('date_preview'),
                                        DatePicker::make('date_voting_end'),
                                        DatePicker::make('date_print_start'),
                                        DatePicker::make('date_print_end'),
                                    ])->columns(2)->columnSpan(1)
                                ])->columns(4),
                            ]),
                        Tab::make('Победители')
                            ->schema([
                                \Filament\Schemas\Components\Grid::make()->schema([
                                    Repeater::make('winners')
                                        ->dehydrated(false)
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
                                        ->dehydrated(false)
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
}
