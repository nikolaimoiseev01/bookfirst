<?php

namespace App\Filament\Resources\Chat\Chats\Tables;

use App\Enums\ChatStatusEnums;
use App\Filament\Resources\Chat\Chats\Pages\EditChat;
use App\Filament\Resources\Chat\Chats\Pages\ViewChat;
use App\Filament\Resources\Collection\Collections\Pages\EditCollection;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use App\Filament\Resources\User\Users\Pages\EditUser;
use App\Models\Chat\Message;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ChatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable(),
                TextColumn::make('userCreated.name')
                    ->limit(20)
                    ->label('Пользователь')
                    ->extraAttributes(['class' => 'fi-color fi-color-primary fi-text-color-700'])
                    ->getStateUsing(function ($record) {
                        return $record->userCreated->getUserFullName();
                    })
                    ->label('Пользователь')->searchable()
                    ->url(function ($record) {
                        return EditUser::getUrl(['record' => $record->userCreated]);
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->label('Статус')
                    ->color(function ($state) {
                        $status = match ($state) {
                            ChatStatusEnums::EMPTY, ChatStatusEnums::PERSONAL_CHAT => 'primary',
                            ChatStatusEnums::WAIT_FOR_USER => 'warning',
                            ChatStatusEnums::WAIT_FOR_ADMIN => 'danger',
                            ChatStatusEnums::ANSWERED, ChatStatusEnums::CLOSED => 'success'
                        };
                        return $status;
                    }),
                TextColumn::make('title')->limit(30)
                    ->getStateUsing(function ($record) {
                        $model = $record->model;
                        return match ($record['model_type']) {
                            'OwnBook' => "Книга: {$model['title']}",
                            'Participation' => "Сборник: {$model->collection['title_short']}",
                            'ExtPromotion' => 'Чат по продвижению',
                            default => $record['title']
                        };
                    })
                    ->label('Тема')
                    ->searchable(),
                TextColumn::make('messages.0.text')
                    ->getStateUsing(function ($record) {
                        return $record->messages
                            ->sortByDesc('created_at')
                            ->first()
                            ?->text;
                    })
                    ->limit(40)
                    ->label('Последнее сообщение'),
                TextColumn::make('messages.0.created_at')
                    ->getStateUsing(function ($record) {
                        return $record->messages
                            ->sortByDesc('created_at')
                            ->first()
                            ?->created_at;
                    })
                    ->dateTime('j M H:i')
                    ->tooltip('Время последнего сообщения')
                    ->label('Время'),
                TextColumn::make('created_at')->label('Создан')->tooltip('Время создания чата')->dateTime('j M H:i')->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        collect(ChatStatusEnums::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->value])
                            ->toArray()
                    ])
                    ->multiple()
                    ->default([ChatStatusEnums::WAIT_FOR_ADMIN->value])
            ])
            ->recordActions([
                Action::make('Подробнее')
                    ->url(function ($record) {
                        return match ($record['model_type']) {
                            'Participation', 'OwnBook', 'ExtPromotion' => $record->model->adminEditPageWithoutLogin(),
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
                    ->orderByDesc(
                        Message::select('created_at')
                            ->whereColumn('chat_id', 'chats.id')
                            ->latest()
                            ->limit(1)
                    );
            })
            ->recordUrl(function ($record) {
                return match ($record['model_type']) {
                    'Participation', 'OwnBook', 'ExtPromotion' => $record->model->adminEditPageWithoutLogin(),
                    default => ViewChat::getUrl(['record' => $record])
                };
            })
            ->paginated([50, 100])
            ->toolbarActions([
                BulkActionGroup::make([
//                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
