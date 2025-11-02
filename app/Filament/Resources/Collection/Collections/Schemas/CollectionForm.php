<?php

namespace App\Filament\Resources\Collection\Collections\Schemas;

use App\Enums\CollectionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Models\Collection\Collection;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class CollectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(5)
            ->components([
                Tabs::make()->schema([
                    Tab::make('Основная информация')->schema([
                        \Filament\Schemas\Components\Grid::make()->schema([
                            TextInput::make('title')
                                ->label('Название')
                                ->required()
                                ->maxLength(255),
                            Select::make('status')
                                ->label('Статус')
                                ->options(
                                    collect(CollectionStatusEnums::cases())
                                        ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                        ->toArray()
                                ),
                            \Filament\Schemas\Components\Grid::make()->schema([
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('title_short')
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
                            DatePicker::make('date_preview_start'),
                            DatePicker::make('date_preview_end'),
                            DatePicker::make('date_print_start'),
                            DatePicker::make('date_print_end'),
                        ])->columns(5)->columnSpanFull(),
                    ]),
                    Tab::make('Конкурс')->schema([
                        Repeater::make('winner_participations')
//                            ->dehydrated(false)
                            ->label('Победители')
                            ->simple(
                                Select::make('participation_id')
                                    ->label('Пользователь')
                                    ->options(fn(Collection $collection) => $collection->participations()->pluck('author_name', 'id')->toArray())
                                    ->required(),
                            )
                            ->columnSpan(1),
//                            ->mutateDehydratedStateUsing(function (array $state): array {
//                                $intConverted = array_values(array_map(fn($item) => (int)$item['user_id'], $state));
//                                dd(123);
//                                return $intConverted;
//                            }),
                        ViewField::make('rating')
                            ->dehydrated(false)
                            ->view('filament.components.collection-votes')
                            ->viewData(function (Collection $collection) {
                                $collection = $collection->load('collectionVotes');
                                $candidates = DB::table('collection_votes')
                                    ->select(DB::raw('count(*) as votes_count, participations.author_name'))
                                    ->join('participations', 'participations.id', '=', 'collection_votes.participation_id_to')
                                    ->where('collection_votes.collection_id', $collection->id)
                                    ->groupBy('participations.author_name')
                                    ->orderBy('votes_count', 'desc')
                                    ->get();
                                return [
                                    'collection' => $collection,
                                    'candidates' => $candidates
                                ];
                            })
                    ])
                ])->columnSpan(4),
                Tabs::make()->schema([
                    Tab::make('Обложка')->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->hiddenLabel()
                            ->disk('media')
                            ->collection('cover_front'),
                    ]),
                    Tab::make('Внутренний блок')->schema([
                        SpatieMediaLibraryFileUpload::make('inside_file')
                            ->hiddenLabel()
                            ->collection('inside_file'),
                    ])
                ])->columnSpan(1)
            ]);
    }
}
