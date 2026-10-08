<?php

namespace App\Filament\Resources\InnerTasks;

use App\Enums\InnerTaskTypeEnums;
use App\Filament\Resources\Collection\Collections\Pages\EditCollection;
use App\Filament\Resources\InnerTasks\Pages\ManageInnerTasks;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use App\Models\InnerTask;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InnerTaskResource extends Resource
{
    protected static ?string $model = InnerTask::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Check;
    protected static ?string $label = 'Задачи';
    protected static ?string $navigationLabel = 'Задачи';
    protected static ?string $pluralLabel = 'Задачи';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('model.title')
                    ->limit(20)
                    ->label('Издание')
                    ->html()
                    ->state(function ($record) {
                        if ($record) {
                            $url = match ($record['model_type']) {
                                'Collection' => EditCollection::getUrl(['record' => $record->model]),
                                'OwnBook' => EditOwnBook::getUrl(['record' => $record->model]),
                                default => null,
                            };
                            $name = match ($record['model_type']) {
                                'Collection' => $record->model['title_short'],
                                'OwnBook' => $record->model['title'],
                                default => null,
                            };

                            if (!$url) {
                                return '—';
                            }

                            $safeUrl = e($url);

                            return <<<HTML
                                            <a href="{$safeUrl}" target="_blank" rel="noopener noreferrer" class="text-primary-600 underline">
                                                {$name}
                                            </a>
                                            HTML;
                        } else {
                            return '';
                        }
                    }),
                Select::make('responsible')
                    ->options([
                        'Коля' => 'Коля',
                        'Ксю' => 'Ксю',
                        'Крис' => 'Крис',
                    ]),
                Hidden::make('type')
                    ->default(InnerTaskTypeEnums::CUSTOM->value)
                    ->dehydrated(fn ($operation) => $operation === 'create'),
                TextInput::make('title'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('comment')
                    ->columnSpanFull(),
                DateTimePicker::make('deadline'),
                DateTimePicker::make('deadline_inner'),
                Toggle::make('flg_custom_task')
                    ->default(true)
                    ->dehydrated(fn ($operation) => $operation === 'create'),
                Toggle::make('flg_custom_finished'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type')
                    ->placeholder('-'),
                TextEntry::make('model_type')
                    ->placeholder('-'),
                TextEntry::make('model_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('responsible')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deadline')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deadline_inner')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('flg_custom_task')
                    ->boolean()
                    ->placeholder('-'),
                IconEntry::make('flg_custom_finished')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->formatStateUsing(callback: function ($state, InnerTask $record) {

                        $icon = match ($state) {
                            InnerTaskTypeEnums::OWN_BOOK_GENERAL => '✒️',
                            InnerTaskTypeEnums::OWN_BOOK_INSIDE => '📖',
                            InnerTaskTypeEnums::OWN_BOOK_COVER => '📕',
                            InnerTaskTypeEnums::COLLECTION => '📚',
                            InnerTaskTypeEnums::CUSTOM => '🛠️',
                        };
                        return "$icon $state->value";
                    })
                    ->sortable()
                    ->label('Тип')
                    ->searchable(),
                TextColumn::make('model.title')
                    ->limit(20)
                    ->sortable()
                    ->label('Издание')
                    ->extraAttributes(['class' => 'fi-color fi-color-primary fi-text-color-700'])
                    ->getStateUsing(function ($record) {
                        return match ($record['model_type']) {
                            'Collection' => $record->model['title_short'],
                            'OwnBook' => $record->model->user->surname . ': ' . $record->model['title'],
                            default => null,
                        };
                    })->url(function ($record) {
                        return match ($record['model_type']) {
                            'Collection' => EditCollection::getUrl(['record' => $record->model]),
                            'OwnBook' => EditOwnBook::getUrl(['record' => $record->model]),
                            default => null,
                        };
                    }),
                TextColumn::make('responsible')
                    ->label('Ответственный')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Название')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('deadline')
                    ->label('Срок')
                    ->formatStateUsing(function ($state, InnerTask $record) {
                        $date = Carbon::parse($state);
                        $days = now()->diffInDays($date, false);

                        // Выбираем иконку
                        $icon = match (true) {
                            $days < 0 => '🔥',
                            $days <= 3 => '⚠️',
                            default => '',
                        };
                        $formattedDate = $date->locale('ru')->translatedFormat('j F');
                        return "$icon $formattedDate";
                    })
                    ->sortable(),
                TextColumn::make('deadline_inner')
                    ->label('Срок внутренний')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Комментарий')
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('flg_custom_task')
                    ->label('Кастомная задача')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                IconColumn::make('flg_custom_finished')
                    ->label('Кастомная задача выполнена')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Дата обновления')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('deadline', 'asc')
            ->recordActions([
                EditAction::make()->iconButton(''),
            ])
            ->recordAction('edit')
            ->paginated([20, 50, 'all'])
            ->toolbarActions([
                BulkActionGroup::make([
//                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInnerTasks::route('/'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return InnerTask::where('deadline', '<', now()->addDays(5))->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Задачи с дедлайном < 5 дней';
    }
}
