<?php

namespace App\Filament\Pages;

use App\Models\Cdek\CdekShipment;
use App\Services\Cdek\CdekOrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Throwable;

class CdekShipmentDetail extends Page
{
    protected static ?string $slug = 'cdek-orders/{shipment}';
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.cdek-shipment-detail';
    protected static ?string $title = 'Отправление СДЭК';

    public int $shipmentId;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole('admin|super_admin|secondary_admin') ?? false;
    }

    public function mount(int $shipment): void
    {
        $this->shipmentId = $shipment;
        CdekShipment::query()->findOrFail($shipment);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cdekDetails')
                ->label('Подробности СДЭК')
                ->icon('heroicon-o-magnifying-glass')
                ->modalHeading('Подробности заказа СДЭК')
                ->modalWidth('5xl')
                ->form([
                    Textarea::make('response')
                        ->label('Ответ API СДЭК')
                        ->rows(24)
                        ->readOnly()
                        ->dehydrated(false),
                ])
                ->fillForm(fn (): array => ['response' => $this->loadCdekDetails()])
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Закрыть'),
        ];
    }

    protected function getViewData(): array
    {
        return ['shipment' => CdekShipment::query()->findOrFail($this->shipmentId)];
    }

    private function loadCdekDetails(): string
    {
        $shipment = CdekShipment::query()->findOrFail($this->shipmentId);
        if (blank($shipment->cdek_uuid)) {
            return 'У отправления нет UUID СДЭК.';
        }

        try {
            $response = app(CdekOrderService::class)->getOrder($shipment->cdek_uuid);
            $createRequest = collect($response['requests'] ?? [])->reverse()->first(fn (array $request) => ($request['type'] ?? null) === 'CREATE');
            $createState = $createRequest['state'] ?? null;
            $cdekNumber = Arr::get($response, 'entity.cdek_number');
            $shipment->update([
                'status' => $createState ?: (Arr::get($response, 'entity.uuid') ? 'SUCCESSFUL' : $shipment->status),
                'cdek_number' => $cdekNumber ?: $shipment->cdek_number,
                'response_payload' => $response,
                'error_message' => $createState === 'INVALID' ? json_encode($createRequest['errors'] ?? [], JSON_UNESCAPED_UNICODE) : null,
            ]);
            $shipment->syncPrintOrderTrackNumber($cdekNumber, $createState);

            return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: 'Пустой ответ СДЭК.';
        } catch (Throwable $exception) {
            report($exception);
            Notification::make()->title('Не удалось обновить подробности СДЭК')->body($exception->getMessage())->danger()->send();

            return 'Не удалось получить подробности: ' . $exception->getMessage();
        }
    }
}
