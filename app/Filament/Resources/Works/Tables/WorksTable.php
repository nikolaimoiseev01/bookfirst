<?php

namespace App\Filament\Resources\Works\Tables;

use App\Filament\Resources\User\Users\Pages\EditUser;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->label('Название'),
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->limit(20)
                    ->label('Пользователь')
                    ->extraAttributes(['class' => 'fi-color fi-color-primary fi-text-color-700'])
                    ->getStateUsing(function ($record) {
                        return $record->user->getUserFullName();
                    })
                    ->label('Пользователь')->searchable()
                    ->url(function ($record) {
                        return EditUser::getUrl(['record' => $record->user]);
                    }),
                TextColumn::make('workType.name')
                    ->label('Тип работы')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('workTopic.name')
                    ->label('Тема')
                    ->placeholder('Не указана')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('symbols')
                    ->label('Символы')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rows')
                    ->label('Строки')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pages')
                    ->label('Страницы')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('upload_type')
                    ->label('Загрузка')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'file' => 'Файл',
                        'text' => 'Текст',
                        'link' => 'Ссылка',
                        default => 'Не указан',
                    }),
                TextColumn::make('comments_count')
                    ->label('Комментарии')
                    ->counts('comments')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
