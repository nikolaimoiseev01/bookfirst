<?php

namespace App\Filament\Resources\Collection\Collections\RelationManagers;

use App\Filament\Resources\Collection\Participations\ParticipationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ParticipationRelationManager extends RelationManager
{
    protected static string $relationship = 'participations';

    protected static ?string $relatedResource = ParticipationResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
            ]);
    }
}
