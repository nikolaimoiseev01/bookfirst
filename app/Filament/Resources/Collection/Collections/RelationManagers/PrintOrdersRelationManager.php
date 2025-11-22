<?php

namespace App\Filament\Resources\Collection\Collections\RelationManagers;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PrintOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'printOrders';

    protected static ?string $title = 'Заказы печати';

    protected static ?string $relatedResource = PrintOrderResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Заказы печати')
            ->badge($ownerRecord->printOrders->count());
    }
}
