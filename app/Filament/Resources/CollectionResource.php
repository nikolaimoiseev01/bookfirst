<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionResource\Pages;
use App\Filament\Resources\CollectionResource\RelationManagers;
use App\Models\Collection;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpOffice\PhpWord\Style\Tab;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Наши сборники';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основная информация')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Group::make()->schema([
                                Forms\Components\TextInput::make('title')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('col_desc')
                                    ->maxLength(65535),
                            ]),
                            Forms\Components\Group::make()->schema([
                                Forms\Components\Group::make()->schema([
                                    Forms\Components\DatePicker::make('col_date1'),
                                    Forms\Components\DatePicker::make('col_date2'),
                                    Forms\Components\DatePicker::make('col_date3'),
                                    Forms\Components\DatePicker::make('col_date4'),
                                ])->columns(2),
                                Forms\Components\BelongsToSelect::make('col_status_id')->relationship('col_status', 'col_status'),

                            ])
                        ]),
                        Grid::make(3)->schema([
                            Forms\Components\FileUpload::make('pre_var'),
                            Forms\Components\TextInput::make('cover_2d')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('cover_3d')
                                ->maxLength(255),
                        ]),


                        Forms\Components\TextInput::make('amazon_link')
                            ->maxLength(65535)

                    ])
                    ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_3d')->width(100)->height('auto')->label(''),
                Tables\Columns\TextColumn::make('title')->label('Название'),
                Tables\Columns\TextColumn::make('participation_sum_pages')->sum('participation', 'pages')->label('Страниц'),
                TextColumn::make('participation_count')->counts('participation')->label('Участников'),
                Tables\Columns\TextColumn::make('participation_sum_total_price')->sum('participation', 'total_price')->label('Выручка'),
                BadgeColumn::make('col_status.col_status')->label('Статус')
                    ->colors([
                        'primary' => 'Идет прием заявок',
                        'danger' => 'Предварительная проверка',
                        'secondary' => 'Идет печать',
                        'success' => 'Сборник издан'
                    ])
                    ->icons([
                        'heroicon-o-document' => 'draft',
                        'heroicon-o-refresh' => 'reviewing',
                        'heroicon-o-truck' => 'published',
                    ])

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('col_status_id')
                    ->multiple()
                    ->relationship('col_status', 'col_status')
                    ->default(array('1', '2', '3'))
                    ->label('Статус')
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ParticipationRelationManager::class,
            RelationManagers\PrintorderRelationManager::class,
            RelationManagers\PreviewCommentRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollections::route('/'),
            'view' => Pages\ViewCollection::route('/{record}'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }
}
