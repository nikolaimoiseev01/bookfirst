<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Schemas;

use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Forms\Components\CustomMediaUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class OwnBookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')->tabs([
                    Tabs\Tab::make('Общее')->schema([
                        Grid::make()->schema([
                            ImageEntry::make('cover')
                                ->imageWidth('100%')
                                ->imageHeight('auto')
                                ->hiddenLabel()
                                ->getStateUsing(function (Model $record) {
                                    $cover = $record->getFirstMediaUrl('cover_front') ?: ENV('APP_URL') . '/fixed/cover_wip.png';
                                    return $cover;
                                })->columnSpan(1),
                            Grid::make()->schema([
                                Select::make('status_general')
                                    ->label('Общий статус')
                                    ->options(
                                        collect(OwnBookStatusEnums::cases())
                                            ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                            ->toArray()
                                    ),
                                Select::make('status_inside')
                                    ->label('Статус ВБ')
                                    ->options(
                                        collect(OwnBookInsideStatusEnums::cases())
                                            ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                            ->toArray()
                                    ),
                                Select::make('status_cover')
                                    ->label('Статус обложки')
                                    ->options(
                                        collect(OwnBookCoverStatusEnums::cases())
                                            ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                            ->toArray()
                                    ),
                                Textarea::make('comment')->hiddenLabel()->placeholder('Комметарий')->columnSpanFull(),
                            ])->columns(3)->columnSpan(6),
                        ])->columnSpanFull()->columns(7),
//                            TextInput::make('pages')
//                                ->required()
//                                ->numeric(),
//                            DatePicker::make('deadline_inside'),
//                            DatePicker::make('deadline_cover'),
//                            TextInput::make('internal_promo_type')
//                                ->numeric(),
//                            Textarea::make('annotation')
//                                ->columnSpanFull(),
                    ])->columns(3),
                    Tabs\Tab::make('Внутренний блок')->schema([
                        Section::make('Информация от автора')->schema([
                            TextEntry::make('comment_author_inside')
                                ->label('Пожелания')
                                ->columnSpanFull(),
                            CustomMediaUpload::make('from_author_inside')
                                ->label('Присланные файлы')
                                ->mediaName(fn(Get $get) => $get('file_name'))
                                ->multiple()
                                ->disabled()
                                ->downloadable()
                                ->collection('from_author_inside'),
                            TextInput::make('inside_type')
                                ->disabled()
                                ->required(),
                        ])->columnSpanFull()->collapsed(),
                        SpatieMediaLibraryFileUpload::make('inside_file')
                            ->label('Внутренний блок')
                            ->downloadable()
                            ->collection('inside_file'),
                        Section::make('Исправления ВБ от автора')->schema([
                            Repeater::make('previewCommentsInside')
                                ->relationship('previewCommentsInside')
                                ->schema([
                                    TextEntry::make('page')->label('Страница'),
                                    TextEntry::make('text')->hiddenLabel(),
                                    Checkbox::make('flg_done')->label('Выполнено')
                                ])
                                ->formatStateUsing(function ($state) {
                                    return collect($state)->where('comment_type', 'inside')->toArray();
                                })
                                ->deletable(false)
                                ->hiddenLabel()
                                ->addable(false)
                                ->grid(2)
                                ->columns(2)
                        ])->collapsed()
                    ]),
                    Tabs\Tab::make('Обложка')->schema([
                        Section::make('Информация от автора')->schema([
                            TextEntry::make('comment_author_cover')
                                ->label('Пожелания')
                                ->columnSpanFull(),
                            CustomMediaUpload::make('from_author_cover')
                                ->label('Присланные файлы')
                                ->multiple()
                                ->disabled()
                                ->downloadable()
                                ->collection('from_author_cover'),
                        ])->columnSpanFull()->collapsed(),
                        Section::make()->schema([
                            SpatieMediaLibraryFileUpload::make('cover_front')
                                ->label('Передняя сторона обложки (!! PNG !!)')
                                ->collection('cover_front'),
                            SpatieMediaLibraryFileUpload::make('cover_full')
                                ->label('Разворот обложки')
                                ->collection('cover_full'),
                        ])->columns(2),
                        Section::make('Исправления обложки от автора')->schema([
                            Repeater::make('previewCommentsCover')
                                ->relationship('previewCommentsCover')
                                ->schema([
                                    TextEntry::make('text')->hiddenLabel(),
                                    Checkbox::make('flg_done')->label('Выполнено')
                                ])
                                ->formatStateUsing(function ($state) {
                                    return collect($state)->where('comment_type', 'cover')->toArray();
                                })
                                ->deletable(false)
                                ->hiddenLabel()
                                ->addable(false)
                                ->grid(2)
                                ->columns(2)
                        ])->collapsed()
                    ]),
                    Tabs\Tab::make('Финансы')->schema([
                        TextInput::make('price_text_design')
                            ->numeric(),
                        TextInput::make('price_text_check')
                            ->numeric(),
                        TextInput::make('price_cover')
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
                        Livewire::make('components.account.chat', ['chat' => $schema->getRecord()->chat])->extraAttributes(['class'=>'h-[500px]'])
                    ])
                ])->columnSpanFull(),

            ]);
    }
}
