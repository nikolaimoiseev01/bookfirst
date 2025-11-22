<?php

namespace App\Filament\Resources\User\Users\RelationManagers;

use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OwnBooksRelationManager extends RelationManager
{
    protected static string $relationship = 'ownBooks';

    protected static ?string $relatedResource = OwnBookResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
            ]);
    }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Собственные книги')
            ->badge($ownerRecord->ownBooks->count());
    }
}
