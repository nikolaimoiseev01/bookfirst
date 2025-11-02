<?php

namespace App\Filament\Resources\Collection\Collections\RelationManagers;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PrintOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'printOrders';

    protected static ?string $relatedResource = PrintOrderResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
