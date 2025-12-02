<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Pages;

use App\Enums\OwnBookStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Enums\PrintOrderTypeEnums;
use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use App\Models\OwnBook\OwnBook;
use App\Models\PrintOrder\PrintOrder;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListPrintOrders extends ListRecords
{
    protected static string $resource = PrintOrderResource::class;

    public $printOrders;
    public function __construct()
    {
        $this->printOrders = PrintOrder::select('type', DB::raw('count(*) as count_print_orders'))
            ->whereNot('status', PrintOrderStatusEnums::SENT)
            ->groupBy('type')
            ->pluck('count_print_orders', 'type');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [

            // --- ПУБЛИКАЦИЯ СОБСТВЕННОЙ КНИГИ ---
            PrintOrderTypeEnums::OWN_BOOK_PUBLISH->value => Tab::make()
                ->badge($this->printOrders[PrintOrderTypeEnums::OWN_BOOK_PUBLISH->value] ?? 0)
                ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('type', PrintOrderTypeEnums::OWN_BOOK_PUBLISH)
                ),

            // --- УЧАСТИЕ В СБОРНИКЕ ---
            PrintOrderTypeEnums::COLLECTION_PARTICIPATION->value => Tab::make()
                ->badge($this->printOrders[PrintOrderTypeEnums::COLLECTION_PARTICIPATION->value] ?? 0)
                ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('type', PrintOrderTypeEnums::COLLECTION_PARTICIPATION)
                ),

            // --- ОСТАЛЬНЫЕ (все, кроме тех двух) ---
            'Остальные' => Tab::make()
                ->badge(
                    PrintOrder::whereNotIn('type', [
                        PrintOrderTypeEnums::OWN_BOOK_PUBLISH->value,
                        PrintOrderTypeEnums::COLLECTION_PARTICIPATION->value,
                    ])->count()
                )
                ->modifyQueryUsing(fn (Builder $query) =>
                $query->whereNotIn('type', [
                    PrintOrderTypeEnums::OWN_BOOK_PUBLISH,
                    PrintOrderTypeEnums::COLLECTION_PARTICIPATION,
                ])
                )
        ];
    }
}
