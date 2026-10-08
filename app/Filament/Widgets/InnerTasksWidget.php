<?php

namespace App\Filament\Widgets;

use App\Enums\InnerTaskTypeEnums;
use App\Filament\Resources\Collection\Collections\Pages\EditCollection;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use App\Models\InnerTask;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class InnerTasksWidget extends TableWidget
{
    use HasWidgetShield;

    protected static ?string $heading = '';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => InnerTask::query())
            ->columns([
                TextColumn::make('type')
                    ->formatStateUsing(callback: function ($state, InnerTask $record) {

                        $icon = match ($state) {
                            InnerTaskTypeEnums::OWN_BOOK_GENERAL => '✒️',
                            InnerTaskTypeEnums::OWN_BOOK_INSIDE => '📖',
                            InnerTaskTypeEnums::OWN_BOOK_COVER => '📕',
                            InnerTaskTypeEnums::CUSTOM => '🛠️',
                            InnerTaskTypeEnums::COLLECTION => '📚',
                        };
                        return "$icon $state->value";
                    })
                    ->label('Тип'),
                TextColumn::make('model.title')
                    ->limit(20)
                    ->label('Издание')
                    ->extraAttributes(['class' => 'fi-color fi-color-primary fi-text-color-700 dark:fi-color dark:fi-color-primary dark:fi-text-color-700'])
                    ->getStateUsing(function($record) {
                        return match ($record['model_type']) {
                            'Collection' => $record->model['title_short'],
                            'OwnBook' => $record->model['title'],
                            default => null,
                        };
                    })->url(function ($record) {
                        return match ($record['model_type']) {
                            'Collection' => EditCollection::getUrl(['record' => $record->model]),
                            'OwnBook' => EditOwnBook::getUrl(['record' => $record->model]),
                            default => null,
                        };
                    }),
                TextColumn::make('title')
                    ->label('Название'),
                TextColumn::make('deadline')
                    ->label('Срок')
                    ->formatStateUsing(function ($state, InnerTask $record) {
                        $date = Carbon::parse($state);
                        $days = now()->diffInDays($date, false);

                        // Выбираем иконку
                        $icon = match (true) {
                            $days < 0   => '🔥',
                            $days <= 3  => '⚠️',
                            default     => '',
                        };
                        $formattedDate = $date->locale('ru')->translatedFormat('j F');
                        return "$icon $formattedDate";
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('deadline', 'asc')
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

}
