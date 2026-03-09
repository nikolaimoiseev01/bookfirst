<?php

namespace App\Filament\Resources\Chat\Messages;

use App\Filament\Resources\Chat\Chats\ChatResource;
use App\Filament\Resources\Chat\Chats\Pages\ViewChat;
use App\Filament\Resources\Chat\Messages\Pages\CreateMessage;
use App\Filament\Resources\Chat\Messages\Pages\EditMessage;
use App\Filament\Resources\Chat\Messages\Pages\ListMessages;
use App\Filament\Resources\Chat\Messages\Schemas\MessageForm;
use App\Filament\Resources\Chat\Messages\Tables\MessagesTable;
use App\Models\Chat\Message;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'text';

    protected static ?string $label = 'Сообщения';
    protected static ?string $navigationLabel = 'Сообщения';
    protected static ?string $pluralLabel = 'Сообщения';

    public static function form(Schema $schema): Schema
    {
        return MessageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MessagesTable::configure($table);
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
            'index' => ListMessages::route('/'),
            'create' => CreateMessage::route('/create'),
            'edit' => EditMessage::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['text'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Чат' => $record->chat->title,
            'От' => $record->chat->userCreated->surname,
            'Кому' => $record->chat->userCreated->surname,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['chat', 'chat.userCreated', 'chat.userTo']);
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        $chat = $record->chat;

        if (!$chat) {
            return '#';
        }

        return match ($chat->model_type) {

            'Participation',
            'OwnBook',
            'ExtPromotion',
            'PrintOrder'
            => $chat->model?->adminEditPageWithoutLogin()
                ?? ViewChat::getUrl(['record' => $chat]),

            default
            => ViewChat::getUrl(['record' => $chat]),
        };
    }

    protected static ?int $globalSearchSort = 9999;

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return Str::limit($record->text, 20);
    }
}
