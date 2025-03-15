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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InnerTaskResource extends Resource
{
    use Tables\Concerns\InteractsWithTable;

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
                            ]),
                        Forms\Components\Toggle::make('flg_finished')
                            ->label('Выполнено')->columnSpan(1)
                    ])->columns(5),
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->maxLength(65535)
                            ->columnSpan(1),
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\DateTimePicker::make('deadline_inner')
                                ->label('Cрок'),
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
                Tables\Columns\TextColumn::make('InnerTaskType.name')
                    ->label('Тип')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable(),
                Tables\Columns\TextColumn::make('own_book.title')
                    ->label('Книга')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->searchable(),
                Tables\Columns\TextColumn::make('own_book.author')
                    ->label('Автор')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->searchable(),
                Tables\Columns\TextColumn::make('collection.title')
                    ->label('Сборник')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->searchable(),
                Tables\Columns\TextColumn::make('own_book_id')
                    ->label('Издание')
                    ->url(function (InnerTask $record) {
                        if ($record['own_book_id']) {
                            return "/admin_panel/own_books/{$record['own_book_id']}";
                        } elseif ($record['collection_id']) {
                            return "/admin_panel/collection/{$record['collection_id']}/edit";
                        }
                    })
                    ->getStateUsing(function (InnerTask $record) {
                        if ($record['own_book_id']) {
                            return "{$record->own_book['author']}: {$record->own_book['title']}";
                        } elseif ($record['collection_id']) {
                            $collection = Collection::find($record['collection_id']);
                            $title_short = str_replace(array('Современный', 'Поэзии', 'Сокровенные', '.', ' '), "", $collection->title);
                            $title_short = str_replace(array('Выпуск'), " ", $title_short);
                            return $title_short;
                        }
                    })
                    ->searchable()
                    ->limit(40),
                SelectColumn::make('responsible')
                    ->label('Ответственный')
                    ->options([
                        'Ксения' => 'Ксюша',
                        'Николай' => 'Коля',
                        'Кристина' => 'Крис',
                    ]),
//                Tables\Columns\ToggleColumn::make('flg_finished')
//                    ->label('Готово?'),
                Tables\Columns\TextColumn::make('deadline_inner')
                    ->label('Срок')
                    ->sortable()
                    ->searchable()
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('flg_finished')
                    ->options([
                        '1' => 'Уже готово',
                        '0' => 'Нужно делать',
                    ])
                    ->default('0')
                    ->attribute('flg_finished'),
                SelectFilter::make('type')->relationship('InnerTaskType', 'name')
            ])
            ->defaultSort('deadline_inner', 'asc')
            ->actions([
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
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
