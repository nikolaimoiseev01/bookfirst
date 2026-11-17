<?php

namespace App\Filament\Pages;

use App\Models\Cdek\CdekCity;
use App\Models\Cdek\CdekShipment;
use App\Models\Collection\Collection;
use App\Models\PrintOrder\PrintingCompany;
use App\Services\Cdek\CdekOrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use RuntimeException;
use Throwable;

class CdekCollectionShipment extends Page implements HasForms
{
    use InteractsWithSchemas;

    private const RECIPIENT_NAME = 'Моисеев Николай';
    private const RECIPIENT_PHONE = '+79095713756';
    private const DELIVERY_POINT = 'MSK2152';
    private const RECIPIENT_ADDRESS = 'Москва, пункт выдачи СДЭК MSK2152';
    private const DESTINATION_CITY_CODE = 44;

    protected string $view = 'filament.pages.cdek-collection-shipment';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Отправить сборники';
    protected static ?string $title = 'Заказ СДЭК для сборников';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 5;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole('admin|super_admin|secondary_admin') ?? false;
    }

    private function printingCompanyOptions(): array
    {
        return PrintingCompany::query()
            ->whereNotNull('pickup_city_code')
            ->whereNotNull('pickup_address')
            ->whereNotNull('pickup_contact_name')
            ->whereNotNull('pickup_contact_phone')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (PrintingCompany $company) => [
                $company->id => $company->name . ' — ' . $company->pickup_address,
            ])
            ->all();
    }

    private function collectionOptions(): array
    {
        return Collection::query()
            ->orderBy('title')
            ->get(['id', 'title', 'title_short'])
            ->mapWithKeys(fn (Collection $collection) => [
                $collection->id => $collection->title_short ?: $collection->title,
            ])
            ->all();
    }

    private function senderPreview(?string $companyId): HtmlString|string
    {
        $company = $companyId ? PrintingCompany::query()->find($companyId) : null;
        if (!$company) {
            return 'Выберите типографию-отправителя. Данные забора будут взяты из её настроек.';
        }

        $city = CdekCity::query()->where('code', $company->pickup_city_code)->value('city') ?: $company->pickup_city_code;

        return new HtmlString(
            '<div class="space-y-1">'
            . '<div><strong>Отправитель СДЭК:</strong> ' . e(config('services.cdek.sender_company')) . '</div>'
            . '<div><strong>Контакт:</strong> ' . e(config('services.cdek.sender_name')) . ', ' . e(config('services.cdek.sender_phone')) . '</div>'
            . '<div><strong>Город отправления из типографии:</strong> ' . e($city) . '</div>'
            . '</div>'
        );
    }

    public function mount(): void
    {
        $this->form->fill([
            'printing_company_id' => null,
            'collection_ids' => [],
            'weight_kg' => null,
            'length' => null,
            'width' => null,
            'height' => null,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form->statePath('data')->schema([
            Section::make('Отправление')
                ->description('Создаётся один заказ СДЭК и одно грузоместо с выбранными сборниками. Получатель и пункт выдачи заданы фиксированно.')
                ->schema([
                    Select::make('printing_company_id')
                        ->label('Типография / город отправления')
                        ->helperText('Город отправления берётся из настроек типографии. Данные отправителя СДЭК — из общих настроек, как в заказах собственных книг.')
                        ->options(fn () => $this->printingCompanyOptions())
                        ->searchable()
                        ->live()
                        ->required(),
                    Placeholder::make('sender_preview')
                        ->label('Отправитель и город')
                        ->content(fn (Get $get) => $this->senderPreview($get('printing_company_id')))
                        ->columnSpanFull(),
                    Select::make('collection_ids')
                        ->label('Сборники в заказе')
                        ->multiple()
                        ->searchable()
                        ->options(fn () => $this->collectionOptions())
                        ->rules(['array', 'min:1'])
                        ->required()
                        ->columnSpanFull(),
                    Placeholder::make('recipient_preview')
                        ->label('Получатель')
                        ->content(self::RECIPIENT_NAME . ' · ' . self::RECIPIENT_PHONE . ' · ' . self::RECIPIENT_ADDRESS)
                        ->columnSpanFull(),
                    TextInput::make('weight_kg')
                        ->label('Общий вес, кг')
                        ->helperText('Укажите вес всего отправления. Для товаров в заказе он будет распределён между выбранными сборниками.')
                        ->numeric()
                        ->minValue(0.001)
                        ->step(0.001)
                        ->required(),
                    TextInput::make('length')
                        ->label('Длина, см')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make('width')
                        ->label('Ширина, см')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make('height')
                        ->label('Высота, см')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public function createShipment(): void
    {
        $data = $this->form->getState();
        $company = PrintingCompany::query()->find($data['printing_company_id'] ?? null);
        if (!$company || blank($company->pickup_city_code) || blank($company->pickup_address)
            || blank($company->pickup_contact_name) || blank($company->pickup_contact_phone)) {
            Notification::make()->title('Заполните данные отправителя в настройках типографии')->danger()->send();
            return;
        }

        $collectionIds = collect($data['collection_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
        $collections = Collection::query()->whereIn('id', $collectionIds)->orderBy('title')->get()->keyBy('id');
        if ($collectionIds->isEmpty() || $collections->count() !== $collectionIds->count()) {
            Notification::make()->title('Выберите хотя бы один существующий сборник')->danger()->send();
            return;
        }

        $weightInGrams = (int) round(((float) $data['weight_kg']) * 1000);
        if ($weightInGrams < $collections->count()) {
            Notification::make()->title('Общий вес должен быть не меньше 1 грамма на каждый выбранный сборник')->danger()->send();
            return;
        }

        $titles = $collections->map(fn (Collection $collection) => $collection->title_short ?: $collection->title)->values();
        $bookTitle = 'Сборники: ' . $titles->implode(', ');
        $shipment = CdekShipment::create([
            'created_by' => auth()->id(),
            'shipment_type' => 'collections',
            'collection_ids' => $collectionIds->all(),
            'own_book_ids' => [],
            'book_titles' => $titles->all(),
            'book_title' => $bookTitle,
            'origin_city_code' => (int) $company->pickup_city_code,
            'recipient_name' => self::RECIPIENT_NAME,
            'recipient_phone' => self::RECIPIENT_PHONE,
            'recipient_address' => self::RECIPIENT_ADDRESS,
            'address_type' => 'СДЭК',
            'delivery_point' => self::DELIVERY_POINT,
            'weight' => $weightInGrams,
            'length' => (int) $data['length'],
            'width' => (int) $data['width'],
            'height' => (int) $data['height'],
            'tariff_code' => (int) config('services.cdek.tariff_code', 136),
            'status' => 'processing',
        ]);

        $names = $collections->map(fn (Collection $collection) => $collection->title_short ?: $collection->title)->values()->all();
        $comment = mb_substr('Сборники к отправке: ' . implode('; ', $names) . '; количество: 1 шт. каждого', 0, 255);
        $baseItemWeight = intdiv($weightInGrams, $collections->count());
        $weightRemainder = $weightInGrams % $collections->count();
        $items = $collections->values()->map(function (Collection $collection, int $index) use ($baseItemWeight, $weightRemainder): array {
            return [
                'name' => mb_substr($collection->title_short ?: $collection->title, 0, 255),
                'ware_key' => $collection->title_short,
                'payment' => ['value' => 0],
                'cost' => 0,
                'weight' => $baseItemWeight + ($index < $weightRemainder ? 1 : 0),
                'amount' => 1,
            ];
        })->all();
        $orderNumber = 'Сборники Сумма: ' . $titles->all();
        $payload = [
            'type' => 1,
            'number' => $orderNumber,
            'tariff_code' => $shipment->tariff_code,
            'comment' => $comment,
            'sender' => [
                'company' => config('services.cdek.sender_company'),
                'name' => config('services.cdek.sender_name'),
                'phones' => [['number' => config('services.cdek.sender_phone')]],
            ],
            'recipient' => [
                'name' => self::RECIPIENT_NAME,
                'phones' => [['number' => self::RECIPIENT_PHONE]],
            ],
            'from_location' => ['code' => (int) $company->pickup_city_code],
            'delivery_point' => self::DELIVERY_POINT,
            'packages' => [[
                'number' => '1',
                'comment' => $comment,
                'weight' => $weightInGrams,
                'length' => (int) $data['length'],
                'width' => (int) $data['width'],
                'height' => (int) $data['height'],
                'items' => $items,
            ]],
        ];
        $shipment->update(['request_payload' => $payload]);

        try {
            $response = app(CdekOrderService::class)->create($payload);
            $requestState = Arr::get($response, 'requests.0.state');
            $cdekNumber = Arr::get($response, 'entity.cdek_number');
            $errors = Arr::get($response, 'requests.0.errors', []);
            $shipment->update([
                'status' => $requestState ?: 'accepted',
                'cdek_uuid' => Arr::get($response, 'entity.uuid'),
                'cdek_number' => $cdekNumber,
                'response_payload' => $response,
                'error_message' => $requestState === 'INVALID' ? json_encode($errors, JSON_UNESCAPED_UNICODE) : null,
            ]);

            if ($requestState === 'INVALID' || blank($shipment->cdek_uuid)) {
                throw new RuntimeException('СДЭК отклонил заказ для сборников: ' . json_encode($errors, JSON_UNESCAPED_UNICODE));
            }

            Notification::make()
                ->title('Заказ для сборников создан')
                ->body('Номер СДЭК: ' . ($cdekNumber ?: 'ожидает присвоения'))
                ->success()
                ->send();
        } catch (Throwable $exception) {
            report($exception);
            $shipment->update([
                'status' => $shipment->cdek_uuid ? 'INVALID' : 'failed',
                'error_message' => $exception->getMessage(),
            ]);
            Notification::make()->title('Не удалось создать заказ для сборников')->body($exception->getMessage())->danger()->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('createShipment')
                ->label('Создать заказ СДЭК')
                ->submit('createShipment')
                ->color('primary'),
        ];
    }
}
