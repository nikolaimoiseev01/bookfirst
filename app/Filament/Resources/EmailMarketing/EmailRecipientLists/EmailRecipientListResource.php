<?php

namespace App\Filament\Resources\EmailMarketing\EmailRecipientLists;

use App\Filament\Resources\EmailMarketing\EmailRecipientLists\Pages\CreateEmailRecipientList;
use App\Filament\Resources\EmailMarketing\EmailRecipientLists\Pages\EditEmailRecipientList;
use App\Filament\Resources\EmailMarketing\EmailRecipientLists\Pages\ListEmailRecipientLists;
use App\Filament\Resources\EmailMarketing\EmailRecipientLists\RelationManagers\EmailRecipientsRelationManager;
use App\Filament\Resources\EmailMarketing\EmailRecipientLists\Schemas\EmailRecipientListForm;
use App\Filament\Resources\EmailMarketing\EmailRecipientLists\Tables\EmailRecipientListsTable;
use App\Models\EmailMarketing\EmailRecipientList;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class EmailRecipientListResource extends Resource
{
    protected static ?string $model = EmailRecipientList::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'Список получателей';
    protected static ?string $navigationLabel = 'Списки получателей';
    protected static ?string $pluralLabel = 'Списки получателей';

    public static function form(Schema $schema): Schema
    {
        return EmailRecipientListForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailRecipientListsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            EmailRecipientsRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailRecipientLists::route('/'),
            'create' => CreateEmailRecipientList::route('/create'),
            'edit' => EditEmailRecipientList::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Получателей' => $record->recipients()->count(),
        ];
    }
}
