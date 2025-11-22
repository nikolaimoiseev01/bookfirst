<?php

namespace App\Filament\Resources\Chats;

use App\Enums\ChatStatusEnums;
use App\Filament\Resources\Chats\Pages\CreateChat;
use App\Filament\Resources\Chats\Pages\EditChat;
use App\Filament\Resources\Chats\Pages\ListChats;
use App\Filament\Resources\Chats\Pages\ViewChat;
use App\Filament\Resources\Chats\Schemas\ChatForm;
use App\Filament\Resources\Chats\Schemas\ChatInfolist;
use App\Filament\Resources\Chats\Tables\ChatsTable;
use App\Models\Chat\Chat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenter;

    protected static ?string $label = 'Чаты';
    protected static ?string $navigationLabel = 'Чаты';
    protected static ?string $pluralLabel = 'Чаты';

    public static function form(Schema $schema): Schema
    {
        return ChatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChatsTable::configure($table);
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
            'index' => ListChats::route('/'),
            'create' => CreateChat::route('/create'),
            'view' => ViewChat::route('/{record}'),
            'edit' => EditChat::route('/{record}/edit'),
        ];
    }


    public static function getNavigationBadge(): ?string
    {
        return Chat::where('status', ChatStatusEnums::WAIT_FOR_ADMIN)->count();
    }
}
