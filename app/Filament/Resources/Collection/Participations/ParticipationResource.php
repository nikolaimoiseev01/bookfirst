<?php

namespace App\Filament\Resources\Collection\Participations;

use App\Filament\Resources\Collection\Participations\Pages\CreateParticipation;
use App\Filament\Resources\Collection\Participations\Pages\EditParticipation;
use App\Filament\Resources\Collection\Participations\Pages\ListParticipations;
use App\Filament\Resources\Collection\Participations\Schemas\ParticipationForm;
use App\Filament\Resources\Collection\Participations\Tables\ParticipationsTable;
use App\Filament\Resources\User\Users\UserResource;
use App\Models\Collection\Participation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ParticipationResource extends Resource
{
    protected static ?string $model = Participation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QueueList;

    protected static ?string $label = 'Участия';
    protected static ?string $navigationLabel = 'Участия';
    protected static ?string $pluralLabel = 'Участия в сборниках';


//    protected static ?string $parentResource = UserResource::class;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Schema $schema): Schema
    {
        return ParticipationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ParticipationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListParticipations::route('/'),
            'create' => CreateParticipation::route('/create'),
            'edit' => EditParticipation::route('/{record}/edit'),
        ];
    }
}
