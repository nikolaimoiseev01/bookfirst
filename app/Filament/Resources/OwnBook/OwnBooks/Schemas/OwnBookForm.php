<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Schemas;

use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Enums\PrintOrderStatusEnums;
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
use Filament\Schemas\Components\Fieldset;
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
                Grid::make()->schema([
                    ImageEntry::make('cover')
                        ->imageWidth('auto')
                        ->imageHeight('196px')
                        ->extraAttributes(['class' => 'rounded overflow-hidden'])
                        ->hiddenLabel()
                        ->getStateUsing(function (Model $record) {
                            $cover = $record->getFirstMediaUrl('cover_front') ?: config('app.url') . '/fixed/cover_wip.png';
                            return $cover;
                        })->columnSpan(1),
                    Section::make()->schema([
                        Select::make('status_general')
                            ->label('Общий статус')
                            ->options(
                                collect(OwnBookStatusEnums::cases())
                                    ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                    ->toArray()
                            )->columnSpan(3),
                        Select::make('status_inside')
                            ->label('Статус ВБ')
                            ->options(
                                collect(OwnBookInsideStatusEnums::cases())
                                    ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                    ->toArray()
                            )->columnSpan(3),
                        Select::make('status_cover')
                            ->label('Статус обложки')
                            ->options(
                                collect(OwnBookCoverStatusEnums::cases())
                                    ->mapWithKeys(fn($case) => [$case->value => $case->value])
                                    ->toArray()
                            )->columnSpan(3),
                        TextInput::make('pages')
                            ->label('Страниц')
                            ->required()
                            ->columnSpan(1)
                            ->numeric(),
                        TextInput::make('internal_promo_type')
                            ->disabled()
                            ->label('Продвижение')
                            ->columnSpan(1)
                            ->numeric(),
                        Textarea::make('comment')->hiddenLabel()->placeholder('Комментарий')->columnSpan(6),
                        Textarea::make('annotation')
                            ->hiddenLabel()->placeholder('Аннотация')
                            ->columnSpan(5),
                    ])->columns(11)->columnSpan(8),
                ])->columnSpanFull()->columns(9),
                Tabs::make('Tabs')->tabs([
                    Tabs\Tab::make('Внутренний блок')->schema([
                        DatePicker::make('deadline_inside'),
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
                        DatePicker::make('deadline_cover'),
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
                        TextInput::make('price_inside')
                            ->numeric(),
                        TextInput::make('price_cover')
                            ->numeric(),
                        TextInput::make('price_promo')
                            ->numeric(),
                        TextInput::make('price_total')
                            ->numeric(),
                        Checkbox::make('need_text_design')
                            ->hintIconTooltip('Test')
                            ->label('Нужен дизайн текста'),
                        Checkbox::make('need_text_check')->label('Нужна проверка текста'),
                        Checkbox::make('cover_ready')->label('Обложка готова от автора'),
                        DateTimePicker::make('paid_at_without_print'),
                        DateTimePicker::make('paid_at_print_only'),
                    ])->columns(3),
                    Tabs\Tab::make('Ссылки')->schema([
                        Repeater::make('selling_links')->schema([
                            TextInput::make('platform'),
                            TextInput::make('link')
                        ])->columns(2)->hiddenLabel()
                    ]),
                    Tabs\Tab::make('Печать')->schema([
                        Grid::make()->schema([
                            TextEntry::make('initialPrintOrder.status')
                                ->label('Статус')
                                ->badge()
                                ->color(fn($state): string => match ($state) {
                                    PrintOrderStatusEnums::CREATED => 'primary',
                                    PrintOrderStatusEnums::PAID, PrintOrderStatusEnums::PRINTING => 'warning',
                                    PrintOrderStatusEnums::SEND_NEED => 'danger',
                                    PrintOrderStatusEnums::SENT => 'success',
                                }),
                            TextEntry::make('initialPrintOrder.price_print')
                                ->label('Цена печати')
                                ->numeric(),
                            DatePicker::make('deadline_print'),
                            TextEntry::make('initialPrintOrder.price_send')
                                ->label('Цена отправки')
                                ->numeric(),
                            TextEntry::make('initialPrintOrder.books_cnt')
                                ->label('Экземпляров')
                                ->numeric(),
                            TextEntry::make('initialPrintOrder.cover_type')
                                ->label('Тип обложки'),
                            TextEntry::make('initialPrintOrder.inside_color')
                                ->label('Цветность ВБ'),
                            TextEntry::make('initialPrintOrder.address_json')
                                ->state(fn($record) => $record->initialPrintOrder?->address_json['string'] ?? '—'
                                )
                                ->label('Адрес'),
                            TextEntry::make('initialPrintOrder.receiver_name')
                                ->label('ФИО')
                                ->numeric(),
                            TextEntry::make('initialPrintOrder.receiver_telephone')
                                ->label('Телефон'),
                            Fieldset::make('initialPrintOrder')
                                ->label('Настройки заказа печати')
                                ->relationship('initialPrintOrder')
                                ->schema([
                                    TextInput::make('track_number'),
                                    Select::make('printing_company_id')
                                        ->relationship(name: 'printingCompany', titleAttribute: 'name'),
                                    Select::make('logistic_company_id')
                                        ->relationship(name: 'logisticCompany', titleAttribute: 'name'),
//                                    Select::make('inside_color')
//                                        ->options([
//                                            'Цветной' => 'Цветной',
//                                            'Черно-белый' => 'Черно-белый'
//                                        ]),
                                ])->columns(3)->columnSpanFull()
                        ])->columnSpanFull()->columns(5)
                    ])->columnSpanFull(),
                    Tab::make('Чат')->schema([
                        Livewire::make('components.account.chat', ['chat' => $schema->getRecord()->chat])->extraAttributes(['class' => 'h-[500px]'])
                    ])
                ])->columnSpanFull(),

            ]);
    }
}
