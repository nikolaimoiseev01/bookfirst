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
                    })
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
