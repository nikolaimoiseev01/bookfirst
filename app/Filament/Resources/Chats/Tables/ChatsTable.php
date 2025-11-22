<?php

namespace App\Filament\Resources\Chats\Tables;

use App\Enums\ChatStatusEnums;
use App\Filament\Resources\Chats\Pages\EditChat;
use App\Filament\Resources\Chats\Pages\ViewChat;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ChatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('userCreated.name')->getStateUsing(function ($record) {
                    return $record->userCreated->getUserFullName();
                })->label('Пользователь')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->label('Статус')
                    ->color(function($state) {
                        $status = match ($state) {
                            ChatStatusEnums::EMPTY, ChatStatusEnums::PERSONAL_CHAT => 'primary',
                            ChatStatusEnums::WAIT_FOR_USER => 'warning',
                            ChatStatusEnums::WAIT_FOR_ADMIN => 'danger',
                            ChatStatusEnums::ANSWERED, ChatStatusEnums::CLOSED => 'success'
                        };
                        return $status;
                    }),
                TextColumn::make('title')->limit(20)
                    ->getStateUsing(function ($record) {
                        $model = $record->model;
                        return match ($record['model_type']) {
                            'Collection', 'OwnBook' => $model['title'],
                            'ExtPromotion' => 'Чат по продвижению',
                            default => $record['title']
                        };
                    })
                    ->label('Тема')
                    ->searchable(),
                TextColumn::make('messages.0.text')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->messages
                            ->sortByDesc('created_at')
                            ->first()
                            ?->text;
                    })
                    ->limit(40)
                    ->label('Последнее сообщение'),
                TextColumn::make('created_at')->label('Создан')->dateTime()->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('Подробнее')
                    ->url(function ($record) {
                        return match ($record['model_type']) {
                            'Collection', 'OwnBook', 'ExtPromotion' => $record->model->adminEditPage(),
                            default => ViewChat::getUrl(['record' => $record])
                        };
                    })
            ])
            ->defaultSort(function (Builder $query): Builder {
                return $query
                    ->orderByRaw("
                        CASE status
                            WHEN 'Ждет ответа поддержки' THEN 1
                            ELSE 99
                        END ASC
                    ")
                    ->orderBy('created_at', 'desc');
            })
            ->toolbarActions([
                BulkActionGroup::make([
//                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
