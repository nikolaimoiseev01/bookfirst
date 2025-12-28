<?php

namespace App\Filament\Resources\User\Users;

use App\Filament\Resources\Collection\Participations\ParticipationResource;
use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use App\Filament\Resources\User\Users\Pages\CreateUser;
use App\Filament\Resources\User\Users\Pages\EditUser;
use App\Filament\Resources\User\Users\Pages\ListUsers;
use App\Filament\Resources\User\Users\RelationManagers\ChatsAllRelationManager;
use App\Filament\Resources\User\Users\RelationManagers\ExtPromotionsRelationManager;
use App\Filament\Resources\User\Users\RelationManagers\OwnBooksRelationManager;
use App\Filament\Resources\User\Users\RelationManagers\ParticipationsRelationManager;
use App\Filament\Resources\User\Users\Schemas\UserForm;
use App\Filament\Resources\User\Users\Tables\UsersTable;
use App\Models\User\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Пользователи';
    protected static ?string $navigationLabel = 'Пользователи';
    protected static ?string $pluralLabel = 'Пользователи';


    protected static ?string $relatedResource = OwnBookResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ParticipationsRelationManager::class,
            OwnBooksRelationManager::class,
            ExtPromotionsRelationManager::class,
            ChatsAllRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
