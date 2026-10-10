<?php

namespace App\Filament\Ai\Resources;

use App\Filament\Ai\Resources\AiAssistantConversationsResource\Pages\ListAiAssistantConversations;
use App\Filament\Resources\User\Users\Pages\EditUser;
use App\Models\Ai\AiAssistantConversation;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AiAssistantConversationsResource extends Resource
{
    protected static ?string $model = AiAssistantConversation::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static UnitEnum|string|null $navigationGroup = 'Для пользователя';
    protected static ?string $label = 'Запрос пользователя';
    protected static ?string $navigationLabel = 'История ИИ-помощника';
    protected static ?string $pluralLabel = 'История ИИ-помощника';
    protected static ?string $recordTitleAttribute = 'prompt_title';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole('admin|super_admin') ?? false;
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; }
    public static function canDelete(Model $record): bool { return false; }
    public static function canDeleteAny(): bool { return false; }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextEntry::make('user.email')->label('Пользователь'),
            TextEntry::make('prompt_title')->label('Цель'),
            TextEntry::make('generation_type')
                ->label('Тип генерации')
                ->formatStateUsing(fn ($state) => $state === 'image' ? 'Изображение' : 'Текст'),
            TextEntry::make('model')->label('Модель')->placeholder('Не указана'),
            TextEntry::make('openrouter_cost_usd')
                ->label('Стоимость OpenRouter')
                ->formatStateUsing(fn ($state) => filled($state) ? '$'.number_format((float) $state, 10, '.', '').' USD' : 'Не получена'),
            TextEntry::make('openrouter_generation_id')->label('ID генерации OpenRouter')->placeholder('Не указан'),
            ImageEntry::make('image_path')->label('Сохранённое изображение')->disk('public')->height(320),
            TextEntry::make('created_at')->label('Дата')->dateTime('d.m.Y H:i'),
            TextEntry::make('question')->label('Запрос пользователя')->columnSpanFull(),
            TextEntry::make('answer')->label('Ответ ИИ')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Дата')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->getStateUsing(fn (AiAssistantConversation $record): string => $record->user?->getUserFullName() ?? '—')
                    ->searchable(query: function ($query, string $search): void {
                        $query->whereHas('user', fn ($userQuery) => $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('surname', 'like', "%{$search}%"));
                    })
                    ->url(fn (AiAssistantConversation $record): ?string => $record->user
                        ? EditUser::getUrl(['record' => $record->user])
                        : null),
                TextColumn::make('prompt_title')->label('Цель')->searchable(),
                TextColumn::make('generation_type')
                    ->label('Тип')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'image' ? 'Изображение' : 'Текст'),
                ImageColumn::make('image_path')->label('Картинка')->disk('public')->height(48),
                TextColumn::make('openrouter_cost_usd')
                    ->label('Стоимость, USD')
                    ->formatStateUsing(fn ($state) => filled($state) ? '$'.number_format((float) $state, 10, '.', '') : 'Не получена')
                    ->sortable(),
                TextColumn::make('question')->label('Запрос')->limit(100)->searchable(),
                TextColumn::make('answer')->label('Ответ ИИ')->limit(100),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([ViewAction::make()->modalWidth('4xl')]);
    }

    public static function getPages(): array
    {
        return ['index' => ListAiAssistantConversations::route('/')];
    }
}
