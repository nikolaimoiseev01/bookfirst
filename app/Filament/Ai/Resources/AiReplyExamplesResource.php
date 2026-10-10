<?php

namespace App\Filament\Ai\Resources;

use App\Models\Ai\AiReplyExample;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AiReplyExamplesResource extends Resource
{
    protected static ?string $model = AiReplyExample::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static UnitEnum|string|null $navigationGroup = 'Для админа';
    protected static ?string $label = 'Пример ответа ИИ';
    protected static ?string $navigationLabel = 'Примеры ответов ИИ';
    protected static ?string $pluralLabel = 'Примеры ответов ИИ';

    public static function canViewAny(): bool { return auth()->user()?->hasAnyRole('admin|super_admin') ?? false; }
    public static function canCreate(): bool { return static::canViewAny(); }
    public static function canEdit(Model $record): bool { return static::canViewAny(); }
    public static function canDelete(Model $record): bool { return static::canViewAny(); }
    public static function canDeleteAny(): bool { return static::canViewAny(); }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Textarea::make('customer_question')->label('Вопрос пользователя')->required()->rows(4),
            Textarea::make('approved_answer')->label('Проверенный ответ')->required()->rows(8),
            Toggle::make('is_active')->label('Использовать как пример')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('customer_question')->label('Вопрос')->limit(80)->searchable(),
            TextColumn::make('approved_answer')->label('Ответ')->limit(100),
            IconColumn::make('is_active')->label('Активен')->boolean(),
            TextColumn::make('updated_at')->label('Обновлён')->dateTime('d.m.Y H:i')->sortable(),
        ])->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Ai\Resources\AiReplyExamplesResource\Pages\ListAiReplyExamples::route('/'),
            'create' => \App\Filament\Ai\Resources\AiReplyExamplesResource\Pages\CreateAiReplyExample::route('/create'),
            'edit' => \App\Filament\Ai\Resources\AiReplyExamplesResource\Pages\EditAiReplyExample::route('/{record}/edit'),
        ];
    }
}
