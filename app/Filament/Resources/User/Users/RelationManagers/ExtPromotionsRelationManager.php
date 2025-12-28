<?php

namespace App\Filament\Resources\User\Users\RelationManagers;

use App\Filament\Resources\ExtPromotions\ExtPromotionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ExtPromotionsRelationManager extends RelationManager
{
    protected static string $relationship = 'extPromotions';

    protected static ?string $relatedResource = ExtPromotionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
            ]);
    }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Продвижения')
            ->badge($ownerRecord->extPromotions->count());
    }
}
