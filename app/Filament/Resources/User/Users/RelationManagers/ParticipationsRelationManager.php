<?php

namespace App\Filament\Resources\User\Users\RelationManagers;

use App\Filament\Resources\Collection\Participations\ParticipationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ParticipationsRelationManager extends RelationManager
{
    protected static string $relationship = 'participations';

    protected static ?string $relatedResource = ParticipationResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
//                CreateAction::make(),
            ]);
    }
}
