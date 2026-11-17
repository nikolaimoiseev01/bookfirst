<?php

namespace App\Filament\Pages;

use App\Models\Cdek\CdekCity;
use App\Models\Cdek\CdekOffice;
use App\Models\Cdek\CdekShipment;
use App\Models\OwnBook\OwnBook;
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

class CdekShipments extends Page implements HasForms
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.cdek-shipments';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Отправки СДЭК';
    protected static ?string $title = 'Создание отправлений СДЭК';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole('admin|super_admin|secondary_admin') ?? false;
    }

    public function mount(): void
    {
        $this->form->fill([
            'shipments' => [[
                'own_book_id' => null,
                'origin_city_code' => 44,
                'weight' => null,
                'length' => null,
                'width' => null,
                'height' => null,
            ]],
        ]);
    }

    private function bookOptions(): array
    {
        return OwnBook::query()->with('initialPrintOrder')->orderBy('title')->get()
            ->mapWithKeys(fn (OwnBook $book) => [
                $book->id => $book->initialPrintOrder
                    ? "{$book->title} — {$book->author} (заказ #{$book->initialPrintOrder->id})"
                    : "{$book->title} — {$book->author} (нет заказа печати)",
            ])->all();
    }

    public function form(Schema $form): Schema
    {
        return $form->statePath('data')->schema([
            Section::make('Книги и параметры отправлений')
                ->description('Одна строка — одна книга и одно отдельное отправление СДЭК. Данные получателя берутся из связанного начального заказа печати.')
                ->schema([
                    Repeater::make('shipments')
                        ->label('Отправления')
                        ->schema([
                            Select::make('own_book_id')
                                ->label('Книга')
                                ->options(fn () => $this->bookOptions())
                                ->searchable()
                                ->required()
                                ->live(),
                            Select::make('origin_city_code')
                                ->label('Город отправления')
                                ->options([
                                    44 => 'Москва',
                                    428 => 'Саратов',
                                ])
                                ->default(44)
                                ->required(),
                            Placeholder::make('recipient_preview')
                                ->label('Данные из заказа печати')
                                ->content(fn (Get $get) => $this->recipientPreview($get('own_book_id'))),
                            TextInput::make('weight')
                                ->label('Вес, кг')
                                ->helperText('Перед отправкой вес будет переведён в граммы, как требует API СДЭК.')
                                ->numeric()
                                ->minValue(0.001)
                                ->step(0.001)
                                ->required(),
                            Placeholder::make('recipient_delivery_cost')
                                ->label('Стоимость для получателя')
                                ->content('Рассчитывается по тарифу СДЭК автоматически при создании отправления.'),
                            TextInput::make('length')->label('Длина, см')->numeric()->minValue(1)->required(),
                            TextInput::make('width')->label('Ширина, см')->numeric()->minValue(1)->required(),
                            TextInput::make('height')->label('Высота, см')->numeric()->minValue(1)->required(),
                        ])
                        ->columns(3)
                        ->minItems(1)
                        ->addActionLabel('Добавить книгу')
                        ->reorderable(false)
                        ->collapsible(false),
                ]),
        ]);
    }

    private function recipientPreview(?string $bookId): HtmlString|string
    {
        if (!$bookId) {
            return 'Сначала выберите книгу.';
        }

        $book = OwnBook::query()->with('initialPrintOrder.logisticCompany')->find($bookId);
        $order = $book?->initialPrintOrder;
        if (!$order) {
            return new HtmlString('<span class="text-danger-600 font-medium">У книги нет связанного initialPrintOrder; отправление создать нельзя.</span>');
        }

        $isCdekAddress = $order->address_type === 'СДЭК';
        $addressData = $order->address_json ?? [];
        $message = '<div class="space-y-1">'
            . '<div><strong>Получатель:</strong> ' . e($order->receiver_name ?: 'не указан') . '</div>'
            . '<div><strong>Телефон:</strong> ' . e($order->receiver_telephone ?: 'не указан') . '</div>'
            . '<div><strong>Адрес:</strong> ' . e($addressData['string'] ?? 'не указан') . '</div>'
            . '<div><strong>Тип адреса:</strong> ' . e($order->address_type ?: 'не указан') . '</div>';

        if (!$isCdekAddress) {
            $message .= '<div class="mt-2 rounded-lg bg-warning-50 p-2 font-medium text-warning-700">Для этой книги выбран другой тип доставки. Проверьте адрес: отправление всё равно будет сформировано с предупреждением.</div>';
        } elseif (empty(data_get($addressData, 'parsed_data.code'))) {
            $message .= '<div class="mt-2 rounded-lg bg-warning-50 p-2 font-medium text-warning-700">В заказе указан СДЭК, но код пункта выдачи не найден.</div>';
        }

        return new HtmlString($message . '</div>');
    }

    public function createShipment(): void
    {
        $data = $this->form->getState();
        $rows = $data['shipments'] ?? [];
        $bookIds = array_column($rows, 'own_book_id');

        if (count($bookIds) !== count(array_unique($bookIds))) {
            Notification::make()->title('Каждую книгу можно добавить только один раз')->danger()->send();
            return;
        }

        $books = OwnBook::query()->with('initialPrintOrder.logisticCompany')->whereIn('id', $bookIds)->get()->keyBy('id');
        if ($books->count() !== count($bookIds)) {
            Notification::make()->title('Одна или несколько книг не найдены')->danger()->send();
            return;
        }

        $created = 0;
        $failed = 0;

        foreach ($rows as $row) {
            /** @var OwnBook $book */
            $book = $books->get($row['own_book_id']);
            $printOrder = $book->initialPrintOrder;
            if (!$printOrder) {
                $failed++;
                continue;
            }

            $weightInGrams = (int) round(((float) $row['weight']) * 1000);
            if ($weightInGrams < 1) {
                $failed++;
                continue;
            }

            $address = $printOrder->address_json ?? [];
            $parsed = $address['parsed_data'] ?? [];
            $isCdek = $printOrder->address_type === 'СДЭК';
            $deliveryPoint = $isCdek ? ($parsed['code'] ?? null) : null;
            $cityCode = $parsed['city_code'] ?? null;
            if ($deliveryPoint && blank($cityCode)) {
                $officeData = CdekOffice::query()->where('code', $deliveryPoint)->first()?->full_data;
                $cityCode = data_get($officeData, 'location.city_code');
            }
            if (blank($cityCode) && !empty($parsed['city'])) {
                $cityCode = CdekCity::query()->where('city', $parsed['city'])->where('country_code', 'RU')->value('code');
            }

            $shipment = CdekShipment::create([
                'created_by' => auth()->id(),
                'shipment_type' => 'own_book',
                'own_book_id' => $book->id,
                'origin_city_code' => (int) $row['origin_city_code'],
                'print_order_id' => $printOrder->id,
                'book_title' => "{$book->title} — {$book->author}",
                'own_book_ids' => [$book->id],
                'book_titles' => ["{$book->title} — {$book->author}"],
                'recipient_name' => $printOrder->receiver_name ?: 'Не указан',
                'recipient_phone' => $printOrder->receiver_telephone ?: 'Не указан',
                'recipient_address' => $address['string'] ?? '',
                'address_type' => $printOrder->address_type,
                'delivery_point' => $deliveryPoint,
                'weight' => $weightInGrams,
                'length' => (int) $row['length'],
                'width' => (int) $row['width'],
                'height' => (int) $row['height'],
                'tariff_code' => (int) config('services.cdek.tariff_code', 136),
                'status' => 'processing',
            ]);

            try {
                foreach ([
                    'services.cdek.sender_company' => 'CDEK_SENDER_COMPANY',
                    'services.cdek.sender_name' => 'CDEK_SENDER_NAME',
                    'services.cdek.sender_phone' => 'CDEK_SENDER_PHONE',
                ] as $configKey => $envName) {
                    if (blank(config($configKey))) {
                        throw new RuntimeException("Не заполнена переменная {$envName} в .env.");
                    }
                }

                if (blank($printOrder->receiver_name) || blank($printOrder->receiver_telephone) || blank($address['string'] ?? null)) {
                    throw new \RuntimeException('В initialPrintOrder не заполнены ФИО, телефон или адрес получателя.');
                }

                $toLocation = $deliveryPoint
                    ? ['delivery_point' => $deliveryPoint]
                    : ['to_location' => ['code' => $cityCode, 'address' => $address['string']]];

                if (blank($cityCode)) {
                    throw new \RuntimeException('Не удалось определить код города СДЭК из адреса initialPrintOrder.');
                }

                $ownCdekId = 'OwnBook: ' . $book->id . '; PrintOrder: ' . $printOrder->id;
                $bookQuantity = max(1, (int) $printOrder->books_cnt);
                $bookComment = mb_substr('Автор: ' . $book->author . '; Название: ' . $book->title . "; Количество: {$bookQuantity} шт.", 0, 255);
                $tariffCode = (int) config('services.cdek.tariff_code', 136);
                $calculationPayload = array_merge([
                    'type' => 1,
                    'tariff_code' => $tariffCode,
                    'from_location' => ['code' => (int) $row['origin_city_code']],
                    'to_location' => ['code' => (int) $cityCode],
                    'packages' => [[
                        'weight' => $weightInGrams,
                        'length' => (int) $row['length'],
                        'width' => (int) $row['width'],
                        'height' => (int) $row['height'],
                    ]],
                ], $deliveryPoint ? ['delivery_point' => $deliveryPoint] : []);
                $calculation = app(CdekOrderService::class)->calculateTariff($calculationPayload);
                $recipientDeliveryCost = (int) round((float) Arr::get($calculation, 'total_sum'));

                $payload = array_merge([
                    'type' => 1,
                    'number' => $ownCdekId,
                    'comment' => $bookComment,
                    'tariff_code' => $tariffCode,
                    'delivery_recipient_cost' => ['value' => $recipientDeliveryCost],
                    'sender' => [
                        'company' => config('services.cdek.sender_company'),
                        'name' => config('services.cdek.sender_name'),
                        'phones' => [['number' => config('services.cdek.sender_phone')]],
                    ],
                    'recipient' => [
                        'name' => $printOrder->receiver_name,
                        'phones' => [['number' => $printOrder->receiver_telephone]],
                    ],
                    'from_location' => ['code' => (int) $row['origin_city_code']],
                    'packages' => [[
                        'number' => '1',
                        'comment' => $bookComment,
                        'weight' => $weightInGrams,
                        'length' => (int) $row['length'],
                        'width' => (int) $row['width'],
                        'height' => (int) $row['height'],
                        'items' => [[
                            'name' => mb_substr($book->title, 0, 255),
                            'ware_key' => $ownCdekId,
                            'payment' => ['value' => 0],
                            'cost' => 0,
                            'weight' => $weightInGrams,
                            'amount' => 1,
                        ]],
                    ]],
                ], $toLocation);

                $shipment->update(['request_payload' => $payload]);
                $response = app(CdekOrderService::class)->create($payload);
                $requestState = Arr::get($response, 'requests.0.state');
                $cdekNumber = Arr::get($response, 'entity.cdek_number');
                $shipment->update([
                    'status' => $requestState ?: 'accepted',
                    'cdek_uuid' => Arr::get($response, 'entity.uuid'),
                    'cdek_number' => $cdekNumber,
                    'request_payload' => $payload,
                    'response_payload' => $response,
                ]);
                $shipment->syncPrintOrderTrackNumber($cdekNumber, $requestState);
                $created++;
            } catch (Throwable $exception) {
                report($exception);
                $shipment->update([
                    'status' => 'failed',
                    'error_message' => $exception->getMessage(),
                ]);
                $failed++;
            }
        }

        Notification::make()
            ->title("Обработано: {$created} отправлений создано, {$failed} с ошибкой")
            ->color($failed > 0 ? 'warning' : 'success')
            ->send();

        $this->form->fill(['shipments' => [[
            'own_book_id' => null,
            'origin_city_code' => 44,
            'weight' => null,
            'length' => null,
            'width' => null,
            'height' => null,
        ]]]);
    }

    protected function getFormActions(): array
    {
        return [Action::make('createShipment')->label('Создать отправления')->submit('createShipment')->color('primary')];
    }

}
