<?php

namespace App\Filament\Resources\ExtPromotions;

use App\Enums\ExtPromotionStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Filament\Resources\ExtPromotions\Pages\CreateExtPromotion;
use App\Filament\Resources\ExtPromotions\Pages\EditExtPromotion;
use App\Filament\Resources\ExtPromotions\Pages\ListExtPromotions;
use App\Filament\Resources\ExtPromotions\Schemas\ExtPromotionForm;
use App\Filament\Resources\ExtPromotions\Tables\ExtPromotionsTable;
use App\Models\ExtPromotion\ExtPromotion;
use App\Models\OwnBook\OwnBook;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExtPromotionResource extends Resource
{
    protected static ?string $model = ExtPromotion::class;


    protected static ?string $label = 'Продвижения';
    protected static ?string $navigationLabel = 'Продвижения';
    protected static ?string $pluralLabel = 'Продвижения';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowTrendingUp;

    public static function form(Schema $schema): Schema
    {
        return ExtPromotionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExtPromotionsTable::configure($table);
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
            'index' => ListExtPromotions::route('/'),
            'create' => CreateExtPromotion::route('/create'),
            'edit' => EditExtPromotion::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return ExtPromotion::where('status', ExtPromotionStatusEnums::REVIEW)->count();
    }
}
