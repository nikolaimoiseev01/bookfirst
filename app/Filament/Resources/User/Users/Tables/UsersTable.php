<?php

namespace App\Filament\Resources\User\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('name')
                    ->getStateUsing(function ($record) {
                        return $record->getUserFullName();
                    })
                    ->label('ФИО')
                    ->searchable(query: function ($query, $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('surname', 'like', "%{$search}%")
                                ->orWhereRaw("CONCAT(name, ' ', surname) like ?", ["%{$search}%"]);
                        });
                    }),
                TextColumn::make('nickname')
                    ->label('Псевдоним')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Дата регистрации')
                    ->dateTime('M d H:i')
                    ->sortable(),
                TextColumn::make('last_seen')
                    ->label('Последний онлайн')
                    ->dateTime('M d H:i')
                    ->sortable(),
                TextColumn::make('participations_count')
                    ->counts('participations')
                    ->sortable()
                    ->label('Сборников'),
                TextColumn::make('own_books_count')
                    ->counts('ownBooks')
                    ->sortable()
                    ->label('Книг'),
                TextColumn::make('ext_promotions_count')
                    ->counts('extPromotions')
                    ->sortable()
                    ->label('Продвижений'),
                TextColumn::make('chats_created_count')
                    ->counts('chatsCreated')
                    ->sortable()
                    ->label('Чатов'),
                TextColumn::make('works_count')
                    ->counts('works')
                    ->sortable()
                    ->label('Работ'),
                TextColumn::make('reg_utm_source')
                    ->searchable(),
                TextColumn::make('reg_utm_medium')
                    ->searchable(),
                TextColumn::make('reg_type')
                    ->label('Тип регистрации')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->paginated([20, 50, 100])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
//                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
//                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
