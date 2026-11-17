<?php

namespace App\Filament\Pages;

use App\Models\Cdek\CdekCity;
use App\Models\Cdek\CdekShipment;
use App\Models\Collection\Collection;
use App\Models\PrintOrder\PrintingCompany;
use App\Services\Cdek\CdekOrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
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

    private function shipmentTotals(mixed $rows): HtmlString|string
    {
        $totalWeight = 0.0;
        $dimensions = ['length' => 0, 'width' => 0, 'height' => 0];
        $placesCount = 0;

        foreach (is_array($rows) ? $rows : [] as $row) {
            foreach (is_array($row['packages'] ?? null) ? $row['packages'] : [] as $package) {
                $totalWeight += (float) ($package['weight_kg'] ?? 0);
                $dimensions['length'] += (int) ($package['length'] ?? 0);
                $dimensions['width'] += (int) ($package['width'] ?? 0);
                $dimensions['height'] += (int) ($package['height'] ?? 0);
                $placesCount++;
            }
        }

        if ($placesCount === 0) {
            return 'Добавьте места, чтобы увидеть общий вес и сумму габаритов.';
        }

        return new HtmlString(
            '<strong>' . $placesCount . ' мест</strong> · общий вес: '
            . number_format($totalWeight, 3, ',', ' ') . ' кг · сумма габаритов: '
            . $dimensions['length'] . ' × ' . $dimensions['width'] . ' × ' . $dimensions['height'] . ' см'
        );
    }

    public function mount(): void
    {
        $this->form->fill([
            'printing_company_id' => null,
            'collections' => [[
                'collection_id' => null,
                'packages' => [['weight_kg' => null, 'length' => null, 'width' => null, 'height' => null]],
            ]],
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form->statePath('data')->schema([
            Section::make('Отправление')
                ->description('Создаётся один заказ СДЭК. Для каждого сборника укажите одно или несколько мест (коробок); у каждого места свои вес и габариты. Получатель и пункт выдачи заданы фиксированно.')
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
                    Repeater::make('collections')
                        ->label('Сборники')
                        ->schema([
                            Select::make('collection_id')
                                ->label('Сборник')
                                ->options(fn () => $this->collectionOptions())
                                ->searchable()
                                ->required()
                                ->columnSpanFull(),
                            Repeater::make('packages')
                                ->label('Места / коробки этого сборника')
                                ->schema([
                                    TextInput::make('weight_kg')->label('Вес, кг')->numeric()->minValue(0.001)->step(0.001)->live(onBlur: true)->required(),
                                    TextInput::make('length')->label('Длина, см')->numeric()->minValue(1)->maxValue(300)->live(onBlur: true)->required(),
                                    TextInput::make('width')->label('Ширина, см')->numeric()->minValue(1)->maxValue(300)->live(onBlur: true)->required(),
                                    TextInput::make('height')->label('Высота, см')->numeric()->minValue(1)->maxValue(300)->live(onBlur: true)->required(),
                                ])
                                ->columns(4)
                                ->minItems(1)
                                ->defaultItems(1)
                                ->addActionLabel('Добавить коробку')
                                ->reorderable(false)
                                ->columnSpanFull(),
                        ])
                        ->minItems(1)
                        ->defaultItems(1)
                        ->addActionLabel('Добавить сборник')
                        ->reorderable(false)
                        ->columnSpanFull(),
                    Placeholder::make('recipient_preview')
                        ->label('Получатель')
                        ->content(self::RECIPIENT_NAME . ' · ' . self::RECIPIENT_PHONE . ' · ' . self::RECIPIENT_ADDRESS)
                        ->columnSpanFull(),
                    Placeholder::make('shipment_totals')
                        ->label('Итого по местам')
                        ->content(fn (Get $get) => $this->shipmentTotals($get('collections')))
                        ->columnSpanFull(),
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

        $rows = collect($data['collections'] ?? [])->values();
        $collectionIds = $rows->pluck('collection_id')->filter()->map(fn ($id) => (int) $id)->values();
        if ($collectionIds->isEmpty() || $collectionIds->count() !== $collectionIds->unique()->count()) {
            Notification::make()->title('Добавьте сборники без повторов')->danger()->send();
            return;
        }

        $collections = Collection::query()->whereIn('id', $collectionIds)->orderBy('title')->get()->keyBy('id');
        if ($collections->count() !== $collectionIds->count()) {
            Notification::make()->title('Один из выбранных сборников не найден')->danger()->send();
            return;
        }

        $packages = [];
        $totalWeight = 0;
        $dimensionTotals = ['length' => 0, 'width' => 0, 'height' => 0];
        foreach ($rows as $row) {
            $collection = $collections->get((int) ($row['collection_id'] ?? 0));
            $collectionPackages = collect($row['packages'] ?? [])->values();
            if (!$collection || $collectionPackages->isEmpty()) {
                Notification::make()->title('У каждого сборника должно быть хотя бы одно место')->danger()->send();
                return;
            }

            foreach ($collectionPackages as $index => $package) {
                $weight = (int) round(((float) ($package['weight_kg'] ?? 0)) * 1000);
                $length = (int) ($package['length'] ?? 0);
                $width = (int) ($package['width'] ?? 0);
                $height = (int) ($package['height'] ?? 0);
                if ($weight < 1 || min($length, $width, $height) < 1) {
                    Notification::make()->title('Проверьте вес и габариты каждого места')->danger()->send();
                    return;
                }

                $totalWeight += $weight;
                $dimensionTotals['length'] += $length;
                $dimensionTotals['width'] += $width;
                $dimensionTotals['height'] += $height;
                $packages[] = [
                    'collection' => $collection,
                    'place_number' => $index + 1,
                    'weight' => $weight,
                    'length' => $length,
                    'width' => $width,
                    'height' => $height,
                ];
            }
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
            'weight' => $totalWeight,
            'length' => $dimensionTotals['length'],
            'width' => $dimensionTotals['width'],
            'height' => $dimensionTotals['height'],
            'tariff_code' => (int) config('services.cdek.tariff_code', 136),
            'status' => 'processing',
        ]);

        $names = $collections->map(fn (Collection $collection) => $collection->title_short ?: $collection->title)->values()->all();
        $comment = mb_substr('Сборники к отправке: ' . implode('; ', $names) . '; мест: ' . count($packages), 0, 255);
        $cdekPackages = collect($packages)->values()->map(function (array $package, int $index) use ($comment): array {
            /** @var Collection $collection */
            $collection = $package['collection'];
            $title = $collection->title_short ?: $collection->title;
            $packageComment = mb_substr($title . ' — место ' . $package['place_number'], 0, 255);

            return [
                'number' => (string) ($index + 1),
                'comment' => $packageComment ?: $comment,
                'weight' => $package['weight'],
                'length' => $package['length'],
                'width' => $package['width'],
                'height' => $package['height'],
                'items' => [[
                    'name' => mb_substr($title, 0, 255),
                    'ware_key' => 'collection-' . $collection->id,
                    'payment' => ['value' => 0],
                    'cost' => 0,
                    'weight' => $package['weight'],
                    'amount' => 1,
                ]],
            ];
        })->all();
        $orderNumber = 'Сборники Сумма: ' . $names;
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
            'packages' => $cdekPackages,
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
