<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageTemplateResource\Pages;
use App\Filament\Resources\MessageTemplateResource\RelationManagers;
use App\Models\MessageTemplate;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageTemplateResource extends Resource
{
    protected static ?string $model = MessageTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Настройки';

    protected static ?string $navigationLabel = 'Шаблоны сообщений';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('template_type')
                    ->required()
                    ->label('Тип')
                    ->maxLength(255),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Название')
                    ->maxLength(255),
                Forms\Components\Textarea::make('text')
                    ->required()
                    ->label('Текст'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('template_type')->label('Тип'),
                Tables\Columns\TextColumn::make('title')->label('Название'),
                Tables\Columns\TextColumn::make('text')->label('Текст'),
                Tables\Columns\TextColumn::make('created_at')->label('Создан')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])

            ->defaultSort('template_type')
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMessageTemplates::route('/'),
        ];
    }
}
