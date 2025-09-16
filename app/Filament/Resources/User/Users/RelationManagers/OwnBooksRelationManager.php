<?php

namespace App\Filament\Resources\User\Users\RelationManagers;

use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

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
}
