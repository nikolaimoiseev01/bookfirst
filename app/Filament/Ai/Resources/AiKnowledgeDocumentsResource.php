<?php

namespace App\Filament\Ai\Resources;

use App\Models\Ai\AiKnowledgeDocument;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AiKnowledgeDocumentsResource extends Resource
{
    protected static ?string $model = AiKnowledgeDocument::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static UnitEnum|string|null $navigationGroup = 'Для админа';
    protected static ?string $label = 'Материал базы знаний';
    protected static ?string $navigationLabel = 'База знаний ИИ';
    protected static ?string $pluralLabel = 'База знаний ИИ';

    public static function canViewAny(): bool { return auth()->user()?->hasAnyRole('admin|super_admin') ?? false; }
    public static function canCreate(): bool { return static::canViewAny(); }
    public static function canEdit(Model $record): bool { return static::canViewAny(); }
    public static function canDelete(Model $record): bool { return static::canViewAny(); }
    public static function canDeleteAny(): bool { return static::canViewAny(); }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('title')->label('Заголовок')->required()->maxLength(255),
            TextInput::make('category')->label('Раздел')->maxLength(100),
            Textarea::make('content')->label('Содержание')->required()->rows(12)->columnSpanFull(),
            TextInput::make('route_name')->label('Имя маршрута для ссылки')->helperText('Укажите именованный маршрут Laravel, например portal.help.account. Оставьте пустым, если ссылка не нужна.'),
            KeyValue::make('route_parameters')->label('Параметры маршрута'),
            Toggle::make('is_active')->label('Использовать при подготовке ответов')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->label('Материал')->searchable()->sortable(),
            TextColumn::make('category')->label('Раздел')->searchable(),
            IconColumn::make('is_active')->label('Активен')->boolean(),
            TextColumn::make('updated_at')->label('Обновлён')->dateTime('d.m.Y H:i')->sortable(),
        ])->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Ai\Resources\AiKnowledgeDocumentsResource\Pages\ListAiKnowledgeDocuments::route('/'),
            'create' => \App\Filament\Ai\Resources\AiKnowledgeDocumentsResource\Pages\CreateAiKnowledgeDocument::route('/create'),
            'edit' => \App\Filament\Ai\Resources\AiKnowledgeDocumentsResource\Pages\EditAiKnowledgeDocument::route('/{record}/edit'),
        ];
    }
}
