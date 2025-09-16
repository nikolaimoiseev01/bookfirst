<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class OwnBookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()->schema([
                    Section::make()->schema([
                        Select::make('own_book_status_id')
                            ->label('Общий статус')
                            ->relationship(name: 'ownBookStatus', titleAttribute: 'name', modifyQueryUsing: fn($query) => $query->orderBy('id')),
                        Select::make('own_book_cover_status_id')
                            ->label('Статус ВБ')
                            ->relationship(name: 'ownBookCoverStatus', titleAttribute: 'name', modifyQueryUsing: fn($query) => $query->orderBy('id')),
                        Select::make('own_book_inside_status_id')
                            ->label('Статус обложки')
                            ->relationship(name: 'ownBookInsideStatus', titleAttribute: 'name', modifyQueryUsing: fn($query) => $query->orderBy('id')),
                        Textarea::make('comment')->hiddenLabel()->placeholder('Комметарий'),
                    ])->columnSpanFull()->columns(4),
                    Tabs::make('Tabs')->tabs([
                        Tabs\Tab::make('Общее')->schema([
                            TextInput::make('pages')
                                ->required()
                                ->numeric(),
                            DatePicker::make('deadline_inside'),
                            DatePicker::make('deadline_cover'),
                            TextInput::make('internal_promo_type')
                                ->numeric(),
                            Textarea::make('annotation')
                                ->columnSpanFull(),
                        ])->columns(3),
                        Tabs\Tab::make('Внутренний блок')->schema([
                            Section::make('Информация от автора')->schema([
                                TextEntry::make('comment_author_inside')
                                    ->label('Пожелания')
                                    ->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('from_author_inside')
                                    ->label('Присланные файлы')
                                    ->multiple()
                                    ->downloadable()
                                    ->collection('from_author_inside'),
                                TextInput::make('inside_type')
                                    ->required(),
                            ])->columnSpanFull()->collapsed(),
                            SpatieMediaLibraryFileUpload::make('inside_file')
                                ->label('Внутренний блок')
                                ->downloadable()
                                ->collection('inside_file'),
                        ]),
                        Tabs\Tab::make('Обложка')->schema([
                            Section::make('Информация от автора')->schema([
                                TextEntry::make('comment_author_cover')
                                    ->label('Пожелания')
                                    ->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('from_author_cover')
                                    ->label('Присланные файлы')
                                    ->multiple()
                                    ->panelLayout('grid')
                                    ->downloadable()
                                    ->collection('from_author_cover'),
                            ])->columnSpanFull()->collapsed(),
                            Section::make()->schema([
                                SpatieMediaLibraryFileUpload::make('cover_front')
                                    ->label('Передняя сторона обложки')
                                    ->collection('cover_front'),
                                SpatieMediaLibraryFileUpload::make('cover_back')
                                    ->label('Задняя сторона обложки')
                                    ->collection('cover_back'),
                                SpatieMediaLibraryFileUpload::make('cover_spine')
                                    ->label('Корешок')
                                    ->collection('cover_spine'),
                            ])->columns(3),
                        ]),
                        Tabs\Tab::make('Исправления')->schema([
                            Repeater::make('previewComments')
                                ->relationship('previewComments')
                                ->schema([
                                    TextEntry::make('text')->hiddenLabel(),
                                    TextEntry::make('comment_type'),
                                    Checkbox::make('flg_done')->label('Выполнено')
                                ])
                                ->deletable(false)
                                ->hiddenLabel()
                                ->addable(false)
                                ->grid(2)
                                ->columns(2)
                        ]),
                        Tabs\Tab::make('Финансы')->schema([
                            TextInput::make('price_text_design')
                                ->numeric(),
                            TextInput::make('price_text_check')
                                ->numeric(),
                            TextInput::make('price_cover')
                                ->numeric(),
                            TextInput::make('price_print')
                                ->numeric(),
                            TextInput::make('price_promo')
                                ->numeric(),
                            TextInput::make('price_total')
                                ->numeric(),
                            DateTimePicker::make('paid_at_without_print'),
                            DateTimePicker::make('paid_at_print_only'),
                        ])->columns(3),
                        Tabs\Tab::make('Ссылки')->schema([
                            Repeater::make('selling_links')->schema([
                                TextInput::make('platform'),
                                TextInput::make('link')
                            ])->columns(2)->hiddenLabel()
                        ]),
                        Tab::make('Чат')->schema([
                            Livewire::make('components.account.chat', ['chat' => $schema->getRecord()->chat])->statePath('chat')
                        ])
                    ])->columnSpanFull()
                ])->columnSpanFull()

            ]);
    }
}
