<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InnerTaskResource\Pages;
use App\Filament\Resources\InnerTaskResource\RelationManagers;
use App\Models\Collection;
use App\Models\InnerTask;
use App\Models\InnerTaskStatus;
use App\Models\own_book;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InnerTaskResource extends Resource
{
    protected static ?string $model = InnerTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-check';

    protected static ?string $navigationGroup = 'Остальное';
    protected static ?string $navigationLabel = 'Внутренние задачи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Заголовок')
                            ->maxLength(255)
                            ->columnSpan(3),
                        Forms\Components\Select::make('responsible')
                            ->label('Ответственный')
                            ->options([
                                'Ксения' => 'Ксения',
                                'Николай' => 'Николай',
                                'Кристина' => 'Кристина',
                            ])
                    ])->columns(4),
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->maxLength(65535)
                            ->columnSpan(1),
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\DateTimePicker::make('deadline')
                                ->label('Оригинальный срок'),
                            Forms\Components\DateTimePicker::make('deadline_inner')
                                ->label('Срок выполнения'),
                        ])->columns(1)->columnSpan(1)
                    ])->columns(2),
                    Forms\Components\Grid::make()->schema([
                        Select::make('collection_id')
                            ->label('Сборник')
                            ->options(Collection::orderBy('id', 'desc')->pluck('title', 'id'))
                            ->searchable(),
                        Select::make('own_book_id')
                            ->label('Собственная книга')
                            ->options(own_book::orderBy('id', 'desc')
                                ->get(['id', 'title', 'author'])
                                ->mapWithKeys(function ($book) {
                                    return [$book->id => "{$book->author}: {$book->title}"];
                                }))
                            ->searchable(),
                    ])

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->getStateUsing(function (InnerTask $record) {
                        return $record['title'];
                    })
                    ->label('Заголовок'),
                Tables\Columns\TextColumn::make('own_book_id')
                    ->label('Для чего')
                    ->url(function (InnerTask $record) {
                        if ($record['own_book_id']) {
                            return "/admin_panel/own_books/{$record['own_book_id']}";
                        } elseif ($record['collection_id']) {
                            return $record['title'];
                        }
                    })
                    ->getStateUsing(function (InnerTask $record) {
                        if ($record['own_book_id']) {
                            return "{$record->own_book['author']}: {$record->own_book['title']}";
                        } elseif ($record['collection_id']) {
                            return $record['title'];
                        }
                    })
                    ->limit(25),
                SelectColumn::make('responsible')
                    ->label('Ответственный')
                    ->options([
                        'Ксения' => 'Ксения',
                        'Николай' => 'Николай',
                        'Кристина' => 'Кристина',
                    ]),
                SelectColumn::make('inner_task_status_id')
                    ->label('Статус задачи')
                    ->options(InnerTaskStatus::all()->pluck('title', 'id')),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Срок')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deadline_inner')
                    ->label('Оригинальный срок')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->dateTime(),
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
            'index' => Pages\ListInnerTasks::route('/'),
            'create' => Pages\CreateInnerTask::route('/create'),
            'edit' => Pages\EditInnerTask::route('/{record}/edit'),
        ];
    }
}
