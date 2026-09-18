<?php

namespace App\Filament\Resources\WorkComments;

use App\Filament\Resources\WorkComments\Pages\ListWorkComments;
use App\Filament\Resources\WorkComments\Pages\ViewWorkComment;
use App\Filament\Resources\WorkComments\Schemas\WorkCommentInfolist;
use App\Filament\Resources\WorkComments\Tables\WorkCommentsTable;
use App\Models\Work\WorkComment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkCommentResource extends Resource
{
    protected static ?string $model = WorkComment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $recordTitleAttribute = 'text';

    protected static ?string $label = 'Комментарий к произведению';
    protected static ?string $navigationLabel = 'Комментарии к произведениям';
    protected static ?string $pluralLabel = 'Комментарии к произведениям';

    public static function infolist(Schema $schema): Schema
    {
        return WorkCommentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkCommentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkComments::route('/'),
            'view' => ViewWorkComment::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['text'];
    }
}
