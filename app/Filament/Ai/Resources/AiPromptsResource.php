<?php

namespace App\Filament\Ai\Resources;

use App\Filament\Ai\Resources\Pages\CreateAiPrompt;
use App\Filament\Ai\Resources\Pages\EditAiPrompt;
use App\Filament\Ai\Resources\Pages\ListAiPrompts;
use App\Models\Ai\AiPrompt;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AiPromptsResource extends Resource
{
    protected static ?string $model = AiPrompt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;
    protected static UnitEnum|string|null $navigationGroup = 'Для пользователя';
    protected static ?string $label = 'ИИ сценарий';
    protected static ?string $navigationLabel = 'ИИ сценарии';
    protected static ?string $pluralLabel = 'ИИ сценарии';
    protected static ?string $recordTitleAttribute = 'title';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole('admin|super_admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDeleteAny(): bool
    {
        return static::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Сценарий')->schema([
                    TextInput::make('title')
                        ->label('Название для пользователя')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Описание')
                        ->rows(2)
                        ->columnSpanFull(),
                    TextInput::make('input_label')
                        ->label('Подпись поля ввода')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('input_placeholder')
                        ->label('Подсказка в поле ввода')
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('sort_order')
                        ->label('Порядок отображения')
                        ->numeric()
                        ->required()
                        ->default(0),
                    Toggle::make('is_active')
                        ->label('Показывать пользователям')
                        ->default(true),
                ])->columns(2),
                Section::make('Инструкции для ИИ')->schema([
                    Textarea::make('system_prompt')
                        ->label('Системная инструкция')
                        ->required()
                        ->rows(8)
                        ->columnSpanFull(),
                    Textarea::make('user_prompt_template')
                        ->label('Запрос с текстом пользователя')
                        ->helperText('В месте, куда нужно подставить ввод пользователя, укажите {{input}}.')
                        ->required()
                        ->rows(6)
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Сценарий')->searchable()->sortable(),
                TextColumn::make('description')->label('Описание')->limit(60),
                IconColumn::make('is_active')->label('Активен')->boolean(),
                TextColumn::make('sort_order')->label('Порядок')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAiPrompts::route('/'),
            'create' => CreateAiPrompt::route('/create'),
            'edit' => EditAiPrompt::route('/{record}/edit'),
        ];
    }
}
