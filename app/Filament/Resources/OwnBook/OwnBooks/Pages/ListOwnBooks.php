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

    public function getTabs(): array
    {
        $tabs = [
            'В работе' => Tab::make()
                ->badge($this->ownBookStatuses[OwnBookStatusEnums::WORK_IN_PROGRESS->value] ?? 0)
                ->modifyQueryUsing(fn(Builder $query) =>
                $query
                    ->where('status_general', OwnBookStatusEnums::WORK_IN_PROGRESS)
                    ->where(function ($q) {
                        $q->where('status_inside', OwnBookInsideStatusEnums::DEVELOPMENT)
                            ->orWhere('status_inside', OwnBookInsideStatusEnums::CORRECTIONS)
                            ->orWhere('status_cover', OwnBookCoverStatusEnums::DEVELOPMENT)
                            ->orWhere('status_cover', OwnBookCoverStatusEnums::CORRECTIONS)

                        ;
                    })
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
