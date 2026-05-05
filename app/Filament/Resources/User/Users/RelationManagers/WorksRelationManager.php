<?php

namespace App\Filament\Resources\User\Users\RelationManagers;

use App\Filament\Resources\Works\WorkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WorksRelationManager extends RelationManager
{
    protected static string $relationship = 'works';

    protected static ?string $relatedResource = WorkResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Произведения')
            ->badge($ownerRecord->works->count());
    }
}
