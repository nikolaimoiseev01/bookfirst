<?php

namespace App\Filament\Resources\Collection\Collections;

use App\Enums\ParticipationStatusEnums;
use App\Filament\Resources\Collection\Collections\Pages\CreateCollection;
use App\Filament\Resources\Collection\Collections\Pages\EditCollection;
use App\Filament\Resources\Collection\Collections\Pages\ListCollections;
use App\Filament\Resources\Collection\Collections\RelationManagers\ParticipationRelationManager;
use App\Filament\Resources\Collection\Collections\RelationManagers\PreviewCommentsRelationManager;
use App\Filament\Resources\Collection\Collections\RelationManagers\PrintOrdersRelationManager;
use App\Filament\Resources\Collection\Collections\Schemas\CollectionForm;
use App\Filament\Resources\Collection\Collections\Tables\CollectionsTable;
use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Collection';

    protected static ?string $label = 'Сборники';
    protected static ?string $navigationLabel = 'Сборники';
    protected static ?string $pluralLabel = 'Сборники';

    public static function form(Schema $schema): Schema
    {
        return CollectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CollectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ParticipationRelationManager::make(),
            PreviewCommentsRelationManager::make(),
            PrintOrdersRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCollections::route('/'),
            'create' => CreateCollection::route('/create'),
            'edit' => EditCollection::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Participation::where('status', ParticipationStatusEnums::APPROVE_NEEDED)->count();
    }

}
