<?php

namespace App\Filament\Resources\EmailMarketing\EmailTemplates;

use App\Filament\Resources\EmailMarketing\EmailTemplates\Pages\CreateEmailTemplate;
use App\Filament\Resources\EmailMarketing\EmailTemplates\Pages\EditEmailTemplate;
use App\Filament\Resources\EmailMarketing\EmailTemplates\Pages\ListEmailTemplates;
use App\Filament\Resources\EmailMarketing\EmailTemplates\Schemas\EmailTemplateForm;
use App\Filament\Resources\EmailMarketing\EmailTemplates\Tables\EmailTemplatesTable;
use App\Models\EmailMarketing\EmailTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'Email шаблон';
    protected static ?string $navigationLabel = 'Email шаблоны';
    protected static ?string $pluralLabel = 'Email шаблоны';

    public static function form(Schema $schema): Schema
    {
        return EmailTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailTemplatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailTemplates::route('/'),
            'create' => CreateEmailTemplate::route('/create'),
            'edit' => EditEmailTemplate::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Тема' => $record->subject,
        ];
    }
}
