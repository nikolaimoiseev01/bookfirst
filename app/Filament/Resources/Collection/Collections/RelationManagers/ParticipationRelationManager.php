<?php

namespace App\Filament\Resources\Collection\Collections\RelationManagers;

use App\Filament\Resources\Collection\Participations\ParticipationResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ParticipationRelationManager extends RelationManager
{
    protected static string $relationship = 'participations';

    protected static ?string $relatedResource = ParticipationResource::class;

    public function table(Table $table): Table
    {
        return $table;

    }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Участия')
            ->badge($ownerRecord->approvedParticipations->count())
            ->badgeTooltip('Подтвержденных участий');
    }
}
