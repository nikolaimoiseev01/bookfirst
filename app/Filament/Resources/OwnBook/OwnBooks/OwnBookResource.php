<?php

namespace App\Filament\Resources\OwnBook\OwnBooks;

use App\Enums\OwnBookStatusEnums;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\CreateOwnBook;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\ListOwnBooks;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\ViewOwnBook;
use App\Filament\Resources\OwnBook\OwnBooks\Schemas\OwnBookForm;
use App\Filament\Resources\OwnBook\OwnBooks\Schemas\OwnBookInfolist;
use App\Filament\Resources\OwnBook\OwnBooks\Tables\OwnBooksTable;
use App\Filament\Resources\User\Users\UserResource;
use App\Models\OwnBook\OwnBook;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OwnBookResource extends Resource
{
    protected static ?string $model = OwnBook::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Собственные книги';
    protected static ?string $navigationLabel = 'Собственные книги';
    protected static ?string $pluralLabel = 'Собственные книги';

    protected static ?string $recordTitleAttribute = 'title';

//    protected static ?string $parentResource = UserResource::class;


    public static function form(Schema $schema): Schema
    {
        return OwnBookForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OwnBookInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OwnBooksTable::configure($table);
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
            'index' => ListOwnBooks::route('/'),
            'create' => CreateOwnBook::route('/create'),
            'edit' => EditOwnBook::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return OwnBook::where('status_general', OwnBookStatusEnums::REVIEW)->count();
    }
}
