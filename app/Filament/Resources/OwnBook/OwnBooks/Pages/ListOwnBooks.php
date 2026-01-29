<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Pages;

use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use App\Models\OwnBook\OwnBook;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListOwnBooks extends ListRecords
{
    protected static string $resource = OwnBookResource::class;

    public $ownBookStatuses;

    public function __construct()
    {
        $this->ownBookStatuses = OwnBook::select('status_general', DB::raw('count(*) as count_ownbooks'))
            ->groupBy('status_general')
            ->pluck('count_ownbooks', 'status_general');
    }

    protected function getHeaderActions(): array
    {
        return [
//            CreateAction::make(),
        ];
    }

    protected function workInProgressQuery(Builder $query): Builder
    {
        return $query
            ->where('status_general', OwnBookStatusEnums::WORK_IN_PROGRESS)
            ->where(function ($q) {
                $q->whereIn('status_inside', [
                    OwnBookInsideStatusEnums::DEVELOPMENT,
                    OwnBookInsideStatusEnums::CORRECTIONS,
                ])
                    ->orWhereIn('status_cover', [
                        OwnBookCoverStatusEnums::DEVELOPMENT,
                        OwnBookCoverStatusEnums::CORRECTIONS,
                    ]);
            });
    }

    public function getTabs(): array
    {
        $tabs = [
            'В работе' => Tab::make()
                ->badge(fn () =>
                $this->workInProgressQuery(
                    OwnBook::query()
                )->count()
                )
                ->modifyQueryUsing(fn (Builder $query) =>
                $this->workInProgressQuery($query)
                ),
            'На отправку' => Tab::make()
                ->badge($this->ownBookStatuses[OwnBookStatusEnums::PRINT_WAITING->value] ?? 0)
                ->badgeColor($this->ownBookStatuses[OwnBookStatusEnums::PRINT_WAITING->value] ?? 0 > 0 ? 'danger' : 'primary')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_general'
                    , OwnBookStatusEnums::PRINT_WAITING)),
            'Идет печать' => Tab::make()
                ->badge($this->ownBookStatuses[OwnBookStatusEnums::PRINTING->value] ?? 0)
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_general'
                    , OwnBookStatusEnums::PRINTING)),
            'Все' => Tab::make()
        ];
        if($this->ownBookStatuses[OwnBookStatusEnums::REVIEW->value] ?? 0 > 0) {
            $reviewTab = [
                'На рассмотрении' => Tab::make()
                    ->badge($this->ownBookStatuses[OwnBookStatusEnums::REVIEW->value] ?? 0)
                    ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status_general', OwnBookStatusEnums::REVIEW)),
            ];

            $tabs = $reviewTab + $tabs;
        }

        return $tabs;
    }
}
