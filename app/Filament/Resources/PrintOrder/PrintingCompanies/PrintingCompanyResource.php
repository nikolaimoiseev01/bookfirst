<?php

namespace App\Filament\Resources\PrintOrder\PrintingCompanies;

use App\Filament\Resources\PrintOrder\PrintingCompanies\Pages\ManagePrintingCompanies;
use App\Models\Cdek\CdekCity;
use App\Models\PrintOrder\PrintingCompany;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PrintingCompanyResource extends Resource
{
    protected static ?string $model = PrintingCompany::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAmericas;
    protected static ?string $label = 'Компании печати';
    protected static ?string $navigationLabel = 'Компании печати';
    protected static ?string $pluralLabel = 'Компании печати';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                Section::make('Данные для забора СДЭК')
                    ->description('Заполните один раз для этой типографии. При создании группового забора эти данные подставятся в отправление и заявку курьеру.')
                    ->schema([
                        Select::make('pickup_city_code')
                            ->label('Город забора')
                            ->options(fn () => CdekCity::query()
                                ->where('country_code', 'RU')
                                ->orderBy('city')
                                ->pluck('city', 'code')
                                ->all())
                            ->searchable()
                            ->default(fn () => CdekCity::query()->where('country_code', 'RU')->where('city', 'Самара')->value('code'))
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('pickup_delivery_point', null))
                            ->required(),
                        TextInput::make('pickup_delivery_point')
                            ->label('Код склада СДЭК')
                            ->helperText('Например, SAR7. Код будет передан в заказ напрямую, без обращения к справочнику cdek_offices.')
                            ->maxLength(20)
                            ->required(),
                        Textarea::make('pickup_address')
                            ->label('Адрес забора')
                            ->rows(2)
                            ->required(),
                        TextInput::make('pickup_contact_name')
                            ->label('Контактное лицо')
                            ->required(),
                        TextInput::make('pickup_contact_phone')
                            ->label('Телефон контактного лица')
                            ->tel()
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePrintingCompanies::route('/'),
        ];
    }
}
