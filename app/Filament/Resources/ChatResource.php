<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatResource\Pages;
use App\Filament\Resources\ChatResource\RelationManagers;
use App\Models\Chat;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_created')
                    ->required(),
                Forms\Components\TextInput::make('user_to')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('chat_status_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('collection_id'),
                Forms\Components\TextInput::make('own_book_id'),
                Forms\Components\TextInput::make('pre_comment_flag')
                    ->required(),
                Forms\Components\TextInput::make('flag_hide_question'),
                Forms\Components\TextInput::make('flg_admin_chat')
                    ->required(),
                Forms\Components\TextInput::make('flg_chat_read')
                    ->required(),
                Forms\Components\TextInput::make('ext_promotion_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_created'),
                Tables\Columns\TextColumn::make('user_to'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('chat_status_id'),
                Tables\Columns\TextColumn::make('collection_id'),
                Tables\Columns\TextColumn::make('own_book_id'),
                Tables\Columns\TextColumn::make('pre_comment_flag'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('flag_hide_question'),
                Tables\Columns\TextColumn::make('flg_admin_chat'),
                Tables\Columns\TextColumn::make('flg_chat_read'),
                Tables\Columns\TextColumn::make('ext_promotion_id'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListChats::route('/'),
            'create' => Pages\CreateChat::route('/create'),
            'edit' => Pages\EditChat::route('/{record}/edit'),
        ];
    }
}
