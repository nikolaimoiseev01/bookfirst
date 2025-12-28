<?php

namespace App\Filament\Resources\User\Users\RelationManagers;

use App\Enums\ChatStatusEnums;
use App\Filament\Resources\Chat\Chats\Pages\ViewChat;
use App\Filament\Resources\User\Users\Pages\EditUser;
use App\Models\Chat\Message;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ChatsAllRelationManager extends RelationManager
{
    protected static string $relationship = 'chatsAll';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Чаты')
            ->badge($ownerRecord->chatsAll->count());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
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
                        $model = $record->model ?? null;
                        if ($model) {
                            return match ($record['model_type']) {
                                'OwnBook' => "Книга: " . $model['title'] ?? 'Нет такой книги',
                                'Participation' => "Сборник: {$model->collection['title_short']}",
                                'ExtPromotion' => 'Чат по продвижению',
                                default => $record['title']
                            };
                        } else {
                            return 'Не определили заголовок';
                        }
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
            ->recordUrl(function ($record) {
                return match ($record['model_type']) {
                    'Participation', 'OwnBook', 'ExtPromotion'
                    => $record->model?->adminEditPageWithoutLogin()
                        ?? ViewChat::getUrl(['record' => $record]),

                    default => ViewChat::getUrl(['record' => $record]),
                };
            })
            ->filters([
                //
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
            ->headerActions([
            ])
            ->recordActions([
            ])
            ->toolbarActions([
            ]);
    }
}
