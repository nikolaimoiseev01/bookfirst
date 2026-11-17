<?php

namespace App\Filament\Pages;

use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use App\Models\Cdek\CdekShipment;
use App\Services\Cdek\CdekOrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Throwable;

class CdekShipmentHistory extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.cdek-shipment-history';
    protected static ?string $navigationLabel = 'Отправки СДЭК';
    protected static ?string $title = 'Отправки СДЭК';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 5;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole('admin|super_admin|secondary_admin') ?? false;
    }

    public function refreshStatuses(): void
    {
        $service = app(CdekOrderService::class);
        $updated = 0;

        CdekShipment::query()
            ->whereRaw('LOWER(status) IN (?, ?)', ['accepted', 'waiting'])
            ->whereNotNull('cdek_uuid')
            ->each(function (CdekShipment $shipment) use ($service, &$updated): void {
                try {
                    $response = $service->getOrder($shipment->cdek_uuid);
                    $state = Arr::get($response, 'requests.0.state');
                    $hasOrder = (bool) Arr::get($response, 'entity.uuid');
                    $shipment->update([
                        'status' => $state ?: ($hasOrder ? 'successful' : $shipment->status),
                        'cdek_number' => Arr::get($response, 'entity.cdek_number', $shipment->cdek_number),
                        'response_payload' => $response,
                        'error_message' => $state === 'INVALID'
                            ? json_encode(Arr::get($response, 'requests.0.errors', []), JSON_UNESCAPED_UNICODE)
                            : null,
                    ]);
                    $shipment->syncPrintOrderTrackNumber(Arr::get($response, 'entity.cdek_number'), $state);
                    $updated++;
                } catch (Throwable $exception) {
                    report($exception);
                }
            });

        Notification::make()->title("Обновлено отправлений: {$updated}")->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createShipment')
                ->label('Создать отправление')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    Select::make('shipment_type')
                        ->label('Что отправляем?')
                        ->options([
                            'own_book' => 'Собственную книгу',
                            'collections' => 'Сборники',
                        ])
                        ->required(),
                ])
                ->action(fn (array $data) => redirect()->to(
                    $data['shipment_type'] === 'collections'
                        ? CdekCollectionShipment::getUrl()
                        : CdekShipments::getUrl()
                )),
            Action::make('refreshStatuses')
                ->label('Обновить статусы')
                ->action('refreshStatuses')
                ->icon('heroicon-o-arrow-path'),
        ];
    }

    protected function getViewData(): array
    {
        return [];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CdekShipment::query()->with('ownBook'))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('book_title')
                    ->label('Книга / сборники')
                    ->getStateUsing(fn (CdekShipment $record) => $record->book_title ?: implode(', ', $record->book_titles ?? []))
                    ->limit(24)
                    ->tooltip(fn (CdekShipment $record) => $record->book_title ?: implode(', ', $record->book_titles ?? []))
                    ->url(fn (CdekShipment $record) => $record->own_book_id
                        ? OwnBookResource::getUrl('edit', ['record' => $record->own_book_id])
                        : null)
                    ->color('primary')
                    ->wrap(false)
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('shipment_type')
                    ->label('Тип')
                    ->formatStateUsing(fn ($state) => $state === 'collections' ? 'Сборники' : 'Обычное отправление')
                    ->badge()
                    ->color(fn ($state) => $state === 'collections' ? 'info' : 'gray')
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('print_order_id')
                    ->label('Заказ печати')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('origin_city_code')
                    ->label('Город отправления')
                    ->formatStateUsing(fn ($state) => [44 => 'Москва', 428 => 'Саратов'][$state] ?? $state)
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('recipient_name')
                    ->label('Получатель')
                    ->limit(20)
                    ->tooltip(fn (CdekShipment $record) => $record->recipient_name)
                    ->searchable()
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('recipient_phone')
                    ->label('Телефон')
                    ->searchable()
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address_type')
                    ->label('Тип адреса')
                    ->badge()
                    ->color(fn ($state) => $state === 'СДЭК' ? 'success' : 'warning')
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('recipient_address')
                    ->label('Адрес')
                    ->limit(28)
                    ->tooltip(fn (CdekShipment $record) => $record->recipient_address)
                    ->searchable()
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('recipient_cost')
                    ->label('Доставка с получателя')
                    ->getStateUsing(fn (CdekShipment $record) => data_get($record->request_payload, 'delivery_recipient_cost.value'))
                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, ',', ' ') . ' ₽' : '—')
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('weight')
                    ->label('Вес')
                    ->getStateUsing(fn (CdekShipment $record) => number_format($record->weight / 1000, 3, ',', ' ') . ' кг')
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dimensions')
                    ->label('Габариты, см')
                    ->getStateUsing(fn (CdekShipment $record) => "{$record->length} × {$record->width} × {$record->height}")
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cdek_number')
                    ->label('Номер СДЭК')
                    ->getStateUsing(fn (CdekShipment $record) => $record->cdek_number ?: 'Не присвоен')
                    ->searchable()
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('cdek_uuid')
                    ->label('UUID СДЭК')
                    ->limit(12)
                    ->tooltip(fn (CdekShipment $record) => $record->cdek_uuid)
                    ->searchable()
                    ->wrap(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn ($state) => match (strtolower((string) $state)) {
                        'accepted', 'successful' => 'success',
                        'waiting', 'processing' => 'warning',
                        'invalid', 'failed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->wrap(false)
                    ->toggleable(),
                TextColumn::make('error_message')
                    ->label('Ошибка')
                    ->limit(32)
                    ->tooltip(fn (CdekShipment $record) => $record->error_message)
                    ->wrap(false)
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(fn () => CdekShipment::query()->whereNotNull('status')->distinct()->orderBy('status')->pluck('status', 'status')->all()),
            ])
            ->extraAttributes(['class' => 'text-xs'])
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->paginationPageOptions([25, 50, 100]);
    }
}
