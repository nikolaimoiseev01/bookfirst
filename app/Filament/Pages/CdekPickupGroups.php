<?php

namespace App\Filament\Pages;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use App\Models\Cdek\CdekCity;
use App\Models\Cdek\CdekPickupGroup;
use App\Models\Cdek\CdekShipment;
use App\Models\PrintOrder\PrintingCompany;
use App\Services\Cdek\CdekOrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Throwable;

class CdekPickupGroups extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.cdek-pickup-groups';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Отправки СДЭК';
    protected static ?string $title = 'Отправки СДЭК';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 6;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole('admin|super_admin|secondary_admin') ?? false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createPickupGroup')
                ->label('Создать общую накладную и вызвать курьера')
                ->icon('heroicon-o-truck')
                ->color('primary')
                ->modalWidth('4xl')
                ->form([
                    Select::make('shipment_ids')
                        ->label('Отправления, которые заберёт курьер')
                        ->multiple()
                        ->searchable()
                        ->options(fn () => $this->pickupOptions())
                        ->rules(['array', 'min:2'])
                        ->required(),
                    Select::make('printing_company_id')
                        ->label('Типография, откуда забрать')
                        ->helperText('Адрес и контакт будут подставлены из настроек компании печати.')
                        ->options(fn () => $this->pickupCompanyOptions())
                        ->searchable()
                        ->live()
                        ->required(),
                    Placeholder::make('pickup_company_preview')
                        ->label('Как будут заполнены данные')
                        ->content(fn (Get $get) => $this->pickupCompanyPreview($get('printing_company_id')))
                        ->columnSpanFull(),
                    DatePicker::make('intake_date')
                        ->label('Дата приезда курьера')
                        ->default(now()->addDay()->toDateString())
                        ->minDate(now()->toDateString())
                        ->required(),
                    TextInput::make('intake_time_from')
                        ->label('Курьер с')
                        ->type('time')
                        ->default('09:00')
                        ->required(),
                    TextInput::make('intake_time_to')
                        ->label('Курьер до')
                        ->type('time')
                        ->default('18:00')
                        ->required(),
                ])
                ->action(fn (array $data) => $this->createPickupGroup($data)),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CdekPickupGroup::query())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('printing_company_name')
                    ->label('Типография')
                    ->searchable()
                    ->limit(24)
                    ->tooltip(fn (CdekPickupGroup $record) => $record->printing_company_name)
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('shipment_links')
                    ->label('Треки отправлений')
                    ->getStateUsing(fn (CdekPickupGroup $record) => $this->shipmentLinks($record))
                    ->html()
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('pickup_address')
                    ->label('Адрес забора')
                    ->limit(32)
                    ->tooltip(fn (CdekPickupGroup $record) => $record->pickup_address)
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pickup_contact_name')
                    ->label('Контакт')
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pickup_contact_phone')
                    ->label('Телефон контакта')
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('delivery_point')
                    ->label('Склад')
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('total_weight')
                    ->label('Общий вес')
                    ->getStateUsing(fn (CdekPickupGroup $record) => number_format($record->total_weight / 1000, 3, ',', ' ') . ' кг')
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('intake_date')
                    ->label('Курьер')
                    ->formatStateUsing(fn (CdekPickupGroup $record) => $record->intake_date->format('d.m.Y') . ' ' . $record->intake_time_from . '–' . $record->intake_time_to)
                    ->sortable()
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('cdek_number')
                    ->label('Номер накладной')
                    ->placeholder('Ожидается')
                    ->searchable()
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('cdek_order_uuid')
                    ->label('UUID накладной')
                    ->limit(12)
                    ->tooltip(fn (CdekPickupGroup $record) => $record->cdek_order_uuid)
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('intake_status')
                    ->label('Заявка курьеру')
                    ->placeholder('Не создана')
                    ->badge()
                    ->color(fn ($state) => match (strtoupper((string) $state)) {
                        'ACCEPTED', 'SUCCESSFUL' => 'success',
                        'INVALID', 'FAILED' => 'danger',
                        default => 'warning',
                    })
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('intake_uuid')
                    ->label('UUID заявки курьеру')
                    ->limit(12)
                    ->tooltip(fn (CdekPickupGroup $record) => $record->intake_uuid)
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Статус группы')
                    ->badge()
                    ->color(fn ($state) => match ((string) $state) {
                        'accepted' => 'success',
                        'intake_failed', 'failed' => 'danger',
                        default => 'warning',
                    })
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('error_message')
                    ->label('Ошибка')
                    ->limit(36)
                    ->tooltip(fn (CdekPickupGroup $record) => $record->error_message)
                    ->color('danger')
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(fn () => CdekPickupGroup::query()->distinct()->orderBy('status')->pluck('status', 'status')->all()),
            ])
            ->recordActions([
                Action::make('pickupOrderDetails')
                    ->label('Подробности накладной')
                    ->icon('heroicon-o-magnifying-glass')
                    ->visible(fn (CdekPickupGroup $record) => filled($record->cdek_order_uuid))
                    ->modalHeading('Подробности общей накладной СДЭК')
                    ->modalWidth('5xl')
                    ->schema([
                        Textarea::make('response')
                            ->label('Ответ API СДЭК')
                            ->rows(24)
                            ->readOnly()
                            ->dehydrated(false),
                    ])
                    ->fillForm(fn (CdekPickupGroup $record): array => [
                        'response' => $this->loadPickupOrderDetails($record),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Закрыть'),
                Action::make('retryIntake')
                    ->label('Повторить вызов курьера')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (CdekPickupGroup $record) => $record->status === 'intake_failed'
                        && filled($record->cdek_order_uuid)
                        && strtoupper((string) $record->order_status) !== 'INVALID')
                    ->action(fn (CdekPickupGroup $record) => $this->retryPickupIntake($record->id)),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginationPageOptions([25, 50, 100])
            ->striped()
            ->extraAttributes(['class' => 'text-xs']);
    }

    private function shipmentLinks(CdekPickupGroup $group): HtmlString
    {
        $shipments = CdekShipment::query()
            ->whereIn('id', $group->shipment_ids ?? [])
            ->orderBy('id')
            ->get();

        if ($shipments->isEmpty()) {
            return new HtmlString('—');
        }

        $links = $shipments->map(function (CdekShipment $shipment): string {
            $label = $shipment->cdek_number ?: $shipment->cdek_uuid ?: 'Отправление #' . $shipment->id;

            $url = $shipment->print_order_id
                ? PrintOrderResource::getUrl('view', ['record' => $shipment->print_order_id])
                : CdekShipmentDetail::getUrl(['shipment' => $shipment->id]);

            return '<a class="text-primary-600 hover:underline" href="' . e($url) . '">' . e($label) . '</a>';
        });

        return new HtmlString($links->implode('<br>'));
    }

    private function pickupOptions(): array
    {
        $alreadyPickedUp = CdekPickupGroup::query()
            ->where(function ($query): void {
                $query->where('status', 'accepted')
                    ->orWhere(function ($query): void {
                        $query->where('status', 'intake_failed')
                            ->whereRaw("LOWER(COALESCE(order_status, '')) <> ?", ['invalid']);
                    });
            })
            ->get()
            ->flatMap(fn (CdekPickupGroup $group) => $group->shipment_ids ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->all();

        return CdekShipment::query()
            ->whereNotNull('cdek_uuid')
            ->whereNotNull('request_payload')
            ->whereRaw('LOWER(status) IN (?, ?, ?)', ['accepted', 'successful', 'waiting'])
            ->when($alreadyPickedUp, fn ($query) => $query->whereNotIn('id', $alreadyPickedUp))
            ->latest()
            ->get()
            ->mapWithKeys(fn (CdekShipment $shipment) => [
                $shipment->id => trim(($shipment->cdek_number ?: $shipment->cdek_uuid) . ' — ' . ($shipment->book_title ?: 'Отправление #' . $shipment->id)),
            ])
            ->all();
    }

    private function pickupCompanyOptions(): array
    {
        return PrintingCompany::query()
            ->whereNotNull('pickup_city_code')
            ->whereNotNull('pickup_delivery_point')
            ->whereNotNull('pickup_address')
            ->whereNotNull('pickup_contact_name')
            ->whereNotNull('pickup_contact_phone')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (PrintingCompany $company) => [
                $company->id => implode(' — ', array_filter([
                    $company->name,
                    $company->pickup_address,
                    $company->pickup_contact_name,
                    $company->pickup_contact_phone,
                ])),
            ])
            ->all();
    }

    private function pickupCompanyPreview(?string $companyId): string
    {
        $company = $companyId ? PrintingCompany::query()->find($companyId) : null;
        if (!$company) {
            return 'Выберите типографию, чтобы увидеть данные, которые автоматически попадут в накладную и заявку курьеру.';
        }

        $city = CdekCity::query()->where('code', $company->pickup_city_code)->value('city');

        return "В СДЭК будут переданы: отправитель — {$company->name}; город забора — " . ($city ?: $company->pickup_city_code)
            . "; адрес — {$company->pickup_address}; контакт — {$company->pickup_contact_name}, {$company->pickup_contact_phone}"
            . '; код склада СДЭК — ' . ($company->pickup_delivery_point ?: 'не указан') . '.';
    }

    private function createPickupGroup(array $data): void
    {
        $shipmentIds = collect($data['shipment_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
        if ($shipmentIds->count() < 2) {
            Notification::make()->title('Для общей накладной выберите не менее двух отправлений')->danger()->send();
            return;
        }

        $intakeDate = Carbon::parse($data['intake_date'])->startOfDay();
        if ($intakeDate->lt(today()) || $intakeDate->gt(today()->addDays(31))) {
            Notification::make()->title('Дата забора должна быть в пределах ближайших 31 дня')->danger()->send();
            return;
        }

        $timeFrom = (string) $data['intake_time_from'];
        $timeTo = (string) $data['intake_time_to'];
        if ($timeFrom < '09:00' || $timeTo > '22:00' || $timeFrom >= $timeTo || strtotime($timeTo) - strtotime($timeFrom) < 3 * 60 * 60) {
            Notification::make()->title('Укажите окно курьера не короче 3 часов, в пределах 09:00–22:00')->danger()->send();
            return;
        }

        $shipments = CdekShipment::query()->with(['ownBook', 'printOrder'])->whereIn('id', $shipmentIds)->get()->keyBy('id');
        if ($shipments->count() !== $shipmentIds->count()) {
            Notification::make()->title('Одно или несколько отправлений не найдены')->danger()->send();
            return;
        }
        if ($shipments->contains(fn (CdekShipment $shipment) => blank($shipment->cdek_uuid) || in_array(strtolower((string) $shipment->status), ['invalid', 'failed']))) {
            Notification::make()->title('Выберите только зарегистрированные отправления СДЭК')->danger()->send();
            return;
        }

        $printingCompany = PrintingCompany::query()->find($data['printing_company_id'] ?? null);
        if (!$printingCompany || blank($printingCompany->pickup_city_code) || blank($printingCompany->pickup_delivery_point)
            || blank($printingCompany->pickup_address) || blank($printingCompany->pickup_contact_name) || blank($printingCompany->pickup_contact_phone)) {
            Notification::make()->title('Заполните данные забора в настройках выбранной типографии')->danger()->send();
            return;
        }

        $cityCode = (int) $printingCompany->pickup_city_code;
        $group = CdekPickupGroup::create([
            'created_by' => auth()->id(),
            'printing_company_id' => $printingCompany->id,
            'printing_company_name' => $printingCompany->name,
            'shipment_ids' => $shipmentIds->all(),
            'pickup_city_code' => $cityCode,
            'pickup_address' => $printingCompany->pickup_address,
            'delivery_point' => $printingCompany->pickup_delivery_point,
            'pickup_contact_name' => $printingCompany->pickup_contact_name,
            'pickup_contact_phone' => $printingCompany->pickup_contact_phone,
            'intake_date' => $intakeDate->toDateString(),
            'intake_time_from' => $timeFrom,
            'intake_time_to' => $timeTo,
            'total_weight' => (int) $shipments->sum('weight'),
            'status' => 'processing',
        ]);

        $packages = $shipments->values()->map(function (CdekShipment $shipment, int $index): array {
            $bookComment = $this->bookComment($shipment);
            $ownCdekId = 'CdekShipment: ' . $shipment->id;

            return [
                'number' => (string) ($index + 1),
                'comment' => $bookComment,
                'weight' => (int) $shipment->weight,
                'length' => (int) $shipment->length,
                'width' => (int) $shipment->width,
                'height' => (int) $shipment->height,
                'items' => [[
                    'name' => mb_substr($shipment->ownBook?->title ?: ('Отправление СДЭК ' . ($shipment->cdek_number ?: $shipment->cdek_uuid)), 0, 255),
                    'ware_key' => $ownCdekId,
                    'payment' => ['value' => 0],
                    'cost' => 0,
                    'weight' => (int) $shipment->weight,
                    'amount' => 1,
                ]],
            ];
        })->all();

        $bookLines = $shipments->values()->map(fn (CdekShipment $shipment) => $this->bookSummaryLine($shipment))->all();
        $pickupCityName = CdekCity::query()->where('code', $cityCode)->value('city') ?: 'город ' . $cityCode;
        $senderPhone = $printingCompany->pickup_contact_phone;
        $orderPayload = [
            'type' => 1,
            'number' => 'BF-PICKUP-' . $group->id,
            'tariff_code' => (int) config('services.cdek.pickup_tariff_code', 138),
            'comment' => $this->limitedBooksComment('Книги для забора: ', $bookLines),
            'sender' => [
                'company' => $printingCompany->name,
                'name' => $printingCompany->pickup_contact_name,
                'phones' => [['number' => $senderPhone]],
            ],
            'recipient' => [
                'company' => 'СДЭК',
                'name' => 'СДЭК',
                'phones' => [['number' => config('services.cdek.sender_phone') ?: $senderPhone]],
            ],
            'from_location' => ['code' => $cityCode, 'address' => $printingCompany->pickup_address],
            'delivery_point' => $printingCompany->pickup_delivery_point,
            'packages' => $packages,
        ];
        $group->update(['request_payload' => $orderPayload]);

        try {
            $service = app(CdekOrderService::class);
            $orderResponse = $service->create($orderPayload);
            $orderState = Arr::get($orderResponse, 'requests.0.state');
            $orderUuid = Arr::get($orderResponse, 'entity.uuid');
            $cdekNumber = Arr::get($orderResponse, 'entity.cdek_number');
            $group->update([
                'order_status' => $orderState,
                'cdek_order_uuid' => $orderUuid,
                'cdek_number' => $cdekNumber,
                'response_payload' => $orderResponse,
            ]);

            if ($orderState === 'INVALID' || blank($orderUuid)) {
                throw new \RuntimeException('СДЭК не принял общую накладную: ' . json_encode(Arr::get($orderResponse, 'requests.0.errors', []), JSON_UNESCAPED_UNICODE));
            }

            $intakePayload = [
                'order_uuid' => $orderUuid,
                'intake_date' => $intakeDate->toDateString(),
                'intake_time_from' => $timeFrom,
                'intake_time_to' => $timeTo,
                'comment' => $this->limitedBooksComment("Забор из {$pickupCityName}: ", $bookLines),
                'need_call' => true,
            ];
            $group->update(['intake_payload' => $intakePayload]);
            $intakeResponse = $service->createIntake($intakePayload);
            $this->saveIntakeResponse($group, $intakeResponse);

            if ($group->fresh()->status === 'intake_failed') {
                Notification::make()->title('Накладная создана, СДЭК отклонил вызов курьера')->body($group->fresh()->error_message)->warning()->send();
                return;
            }

            Notification::make()->title('Общая накладная и вызов курьера созданы')->body('Номер СДЭК: ' . ($cdekNumber ?: 'ожидает присвоения'))->success()->send();
        } catch (Throwable $exception) {
            report($exception);
            $orderWasCreated = filled($group->cdek_order_uuid);
            $errorMessage = $this->appendOrderDiagnostics($group, $exception);
            $group->update([
                'status' => $orderWasCreated ? 'intake_failed' : 'failed',
                'error_message' => $errorMessage,
            ]);
            Notification::make()
                ->title($orderWasCreated ? 'Накладная создана, не удалось вызвать курьера' : 'Не удалось создать общую накладную')
                ->body($errorMessage . ($orderWasCreated ? ' Повторите вызов курьера в этой таблице; накладная повторно не создаётся.' : ''))
                ->danger()
                ->send();
        }
    }

    private function bookComment(CdekShipment $shipment): string
    {
        if ($shipment->shipment_type === 'collections') {
            $titles = $shipment->book_titles ?? [];

            return mb_substr('Сборники: ' . implode(', ', $titles) . '; количество: 1 шт. каждого', 0, 255);
        }

        $book = $shipment->ownBook;
        $author = $book?->author ?: 'Автор не указан';
        $title = $book?->title ?: ($shipment->book_title ?: 'Название не указано');
        $quantity = max(1, (int) ($shipment->printOrder?->books_cnt ?? 1));

        return mb_substr("Автор: {$author}; Название: {$title}; Количество: {$quantity} шт.", 0, 255);
    }

    private function bookSummaryLine(CdekShipment $shipment): string
    {
        if ($shipment->shipment_type === 'collections') {
            $titles = $shipment->book_titles ?? [];

            return 'Сборники: ' . implode(', ', $titles) . ' — 1 шт. каждого';
        }

        $book = $shipment->ownBook;
        $author = $book?->author ?: 'Автор не указан';
        $title = $book?->title ?: ($shipment->book_title ?: 'Название не указано');
        $quantity = max(1, (int) ($shipment->printOrder?->books_cnt ?? 1));

        return "{$author} — «{$title}» — {$quantity} шт.";
    }

    private function limitedBooksComment(string $prefix, array $bookLines): string
    {
        $comment = $prefix;
        $included = 0;
        $total = count($bookLines);

        foreach ($bookLines as $index => $line) {
            $candidate = $comment . ($included > 0 ? '; ' : '') . $line;
            $remaining = $total - $index - 1;
            $reservedSuffix = $remaining > 0 ? '; ещё ' . $remaining . ' мест — см. описания мест' : '';
            if (mb_strlen($candidate . $reservedSuffix) > 255) {
                break;
            }
            $comment = $candidate;
            $included++;
        }

        $remaining = $total - $included;
        if ($remaining > 0) {
            $suffix = $included > 0 ? '; ещё ' . $remaining . ' мест — см. описания мест' : 'См. описания грузомест';
            $comment = mb_substr($comment, 0, 255 - mb_strlen($suffix)) . $suffix;
        }

        return $comment;
    }

    private function saveIntakeResponse(CdekPickupGroup $group, array $intakeResponse): void
    {
        $intakeState = Arr::get($intakeResponse, 'requests.0.state', 'ACCEPTED');
        $group->update([
            'status' => $intakeState === 'INVALID' ? 'intake_failed' : 'accepted',
            'intake_status' => $intakeState,
            'intake_uuid' => Arr::get($intakeResponse, 'entity.uuid'),
            'intake_response_payload' => $intakeResponse,
            'error_message' => $intakeState === 'INVALID'
                ? json_encode(Arr::get($intakeResponse, 'requests.0.errors', []), JSON_UNESCAPED_UNICODE)
                : null,
        ]);
    }

    private function appendOrderDiagnostics(CdekPickupGroup $group, Throwable $exception): string
    {
        $message = $exception->getMessage();
        if (blank($group->cdek_order_uuid)) {
            return $message;
        }

        try {
            $orderDetails = app(CdekOrderService::class)->getOrder($group->cdek_order_uuid);
            $createRequest = collect($orderDetails['requests'] ?? [])
                ->reverse()
                ->first(fn (array $request) => ($request['type'] ?? null) === 'CREATE');
            $createState = $createRequest['state'] ?? null;
            $statuses = collect(Arr::get($orderDetails, 'entity.statuses', []))
                ->map(fn (array $status) => array_intersect_key($status, array_flip(['code', 'name', 'date_time'])))
                ->values()
                ->all();
            $diagnostics = [
                'create_state' => $createState,
                'errors' => $createRequest['errors'] ?? [],
                'statuses' => $statuses,
            ];

            $group->update([
                'order_status' => $createState ?: $group->order_status,
                'response_payload' => array_merge($group->response_payload ?? [], ['order_details_after_intake_error' => $orderDetails]),
            ]);

            return $message . ' Подробности общей накладной: '
                . json_encode($diagnostics, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (Throwable $detailsException) {
            report($detailsException);

            return $message . ' Не удалось запросить подробности общей накладной: ' . $detailsException->getMessage();
        }
    }

    private function loadPickupOrderDetails(CdekPickupGroup $group): string
    {
        try {
            $response = app(CdekOrderService::class)->getOrder($group->cdek_order_uuid);
            $createRequest = collect($response['requests'] ?? [])
                ->reverse()
                ->first(fn (array $request) => ($request['type'] ?? null) === 'CREATE');
            $group->update([
                'order_status' => $createRequest['state'] ?? $group->order_status,
                'response_payload' => array_merge($group->response_payload ?? [], ['order_details' => $response]),
            ]);

            return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                ?: 'Пустой ответ СДЭК.';
        } catch (Throwable $exception) {
            report($exception);
            Notification::make()->title('Не удалось получить подробности накладной')->body($exception->getMessage())->danger()->send();

            return 'Не удалось получить подробности: ' . $exception->getMessage();
        }
    }

    private function retryPickupIntake(CdekPickupGroup $group): void
    {
        if (blank($group->cdek_order_uuid)) {
            Notification::make()->title('У общей накладной нет UUID СДЭК — повторный вызов невозможен')->danger()->send();
            return;
        }

        try {
            $orderDetails = app(CdekOrderService::class)->getOrder($group->cdek_order_uuid);
            $createRequest = collect($orderDetails['requests'] ?? [])
                ->reverse()
                ->first(fn (array $request) => ($request['type'] ?? null) === 'CREATE');
            if (($createRequest['state'] ?? null) === 'INVALID') {
                $details = $createRequest['errors'] ?? [];
                $message = 'Общая накладная недействительна, повторный вызов курьера невозможен: '
                    . json_encode($details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $group->update([
                    'order_status' => 'INVALID',
                    'response_payload' => array_merge($group->response_payload ?? [], ['order_details' => $orderDetails]),
                    'error_message' => $message,
                ]);
                Notification::make()
                    ->title('Накладная СДЭК недействительна')
                    ->body($message . ' Создайте новую общую накладную после исправления тарифа.')
                    ->warning()
                    ->send();
                return;
            }
        } catch (Throwable $exception) {
            report($exception);
            // If status lookup is temporarily unavailable, keep the existing retry path.
        }

        $shipments = CdekShipment::query()->with(['ownBook', 'printOrder'])->whereIn('id', $group->shipment_ids ?? [])->get();
        $bookLines = $shipments->map(fn (CdekShipment $shipment) => $this->bookSummaryLine($shipment))->all();
        $cityName = CdekCity::query()->where('code', $group->pickup_city_code)->value('city') ?: 'город ' . $group->pickup_city_code;
        $intakePayload = array_merge($group->intake_payload ?? [], [
            'order_uuid' => $group->cdek_order_uuid,
            'intake_date' => $group->intake_date->toDateString(),
            'intake_time_from' => $group->intake_time_from,
            'intake_time_to' => $group->intake_time_to,
            'comment' => $this->limitedBooksComment("Забор из {$cityName}: ", $bookLines),
            'need_call' => true,
        ]);
        $group->update(['intake_payload' => $intakePayload, 'status' => 'processing']);

        try {
            $intakeResponse = app(CdekOrderService::class)->createIntake($intakePayload);
            $this->saveIntakeResponse($group, $intakeResponse);
            $group->refresh();
            Notification::make()
                ->title($group->status === 'intake_failed' ? 'СДЭК отклонил повторный вызов курьера' : 'Курьер вызван')
                ->body($group->error_message ?: 'Накладная не создавалась повторно.')
                ->color($group->status === 'intake_failed' ? 'warning' : 'success')
                ->send();
        } catch (Throwable $exception) {
            report($exception);
            $errorMessage = $this->appendOrderDiagnostics($group, $exception);
            $group->update(['status' => 'intake_failed', 'error_message' => $errorMessage]);
            Notification::make()->title('Не удалось повторно вызвать курьера')->body($errorMessage)->danger()->send();
        }
    }
}
