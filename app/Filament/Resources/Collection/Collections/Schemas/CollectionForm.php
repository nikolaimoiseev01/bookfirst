<?php

namespace App\Filament\Resources\Collection\Collections\Schemas;

use App\Enums\CollectionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Enums\PrintOrderTypeEnums;
use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use App\Models\PrintOrder\PrintOrder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
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
                                ->columnSpan(3)
                                ->required()
                                ->maxLength(255),
                            Select::make('status')
                                ->label('Статус')
                                ->columnSpan(3)
                                ->visibleOn('edit')
                                ->options(
                                    collect(CollectionStatusEnums::cases())
                                        ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                        ->toArray()
                                ),
                            TextInput::make('pages')
                                ->label('Страниц')
                                ->visibleOn('edit')
                                ->columnSpan(1)
                                ->numeric(),
                            Placeholder::make('prints')
                                ->label('Экземпляров')
                                ->content(function (?Collection $record) {
                                    $totalPrints = $record->approvedParticipations()
                                        ->withSum('printOrder', 'books_cnt')
                                        ->get()
                                        ->sum('print_order_sum_books_cnt');
                                    return $totalPrints ?? '—';
                                })
                                ->visibleOn('edit')
                                ->columnSpan(1),
                            Textarea::make('description')
                                ->label('Описание')
                                ->columnSpanFull(),
                        ])->columns(8)->columnSpan(2),
                        \Filament\Schemas\Components\Grid::make()->schema([
                            DatePicker::make('date_apps_end')->label('Конец приема заявок'),
                            DatePicker::make('date_preview_start')->label('Начало проверки'),
                            DatePicker::make('date_preview_end')->label('Конец  проверки'),
                            DatePicker::make('date_print_start')->label('Начало печати'),
                            DatePicker::make('date_print_end')->label('Отправка экземпляров'),
                        ])->columns(5)->columnSpanFull(),
                    ]),
                    Tab::make('Конкурс')
                        ->visibleOn('edit')
                        ->schema([
                        Repeater::make('winner_participations')
//                            ->dehydrated(false)
                            ->label('Победители')
                            ->simple(
                                Select::make('participation_id')
                                    ->label('Пользователь')
                                    ->options(fn(Collection $collection) => $collection->approvedParticipations()->pluck('author_name', 'id')->toArray())
                                    ->required(),
                            )
                            ->columnSpan(1),
//                            ->mutateDehydratedStateUsing(function (array $state): array {
//                                $intConverted = array_values(array_map(fn($item) => (int)$item['user_id'], $state));
//                                return $intConverted;
//                            }),
                        ViewField::make('rating')
                            ->dehydrated(false)
                            ->view('filament.components.collection-votes')
                            ->viewData(function (Collection $collection) {
                                $collection = $collection->load('collectionVotes');
                                $candidates = DB::table('collection_votes')
                                    ->select(DB::raw('count(*) as votes_count, participations.author_name, participations.id'))
                                    ->join('participations', 'participations.id', '=', 'collection_votes.participation_id_to')
                                    ->where('collection_votes.collection_id', $collection->id)
                                    ->groupBy(DB::raw('participations.author_name, participations.id'))
                                    ->orderBy('votes_count', 'desc')
                                    ->get();
                                return [
                                    'collection' => $collection,
                                    'candidates' => $candidates
                                ];
                            })
                    ]),
                    Tab::make('Распределение печати')->schema([
                        ViewField::make('rating')
                            ->dehydrated(false)
                            ->view('filament.components.collection-print-distribution')
                            ->viewData(function (Collection $collection) {
                                $distribution = Participation::query()
                                    ->where('participations.collection_id', $collection['id'])
                                    ->where('participations.status', ParticipationStatusEnums::APPROVED)
                                    ->join('print_orders', 'print_orders.id', '=', 'participations.print_order_id')
                                    ->select('print_orders.books_cnt', DB::raw('COUNT(participations.id) as total'))
                                    ->orderBy('print_orders.books_cnt')
                                    ->groupBy('print_orders.books_cnt')
                                    ->pluck('total', 'print_orders.books_cnt')
                                    ->toArray();
                                return [
                                    'distribution' => $distribution
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
