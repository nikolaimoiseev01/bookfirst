<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Pages;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use App\Models\Cdek\CdekShipment;
use App\Services\Cdek\CdekOrderService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Arr;
use Throwable;
use Filament\Resources\Pages\ViewRecord;

class ViewPrintOrder extends ViewRecord
{
    protected static string $resource = PrintOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cdekDetails')
                ->label('Подробности СДЭК')
                ->icon('heroicon-o-magnifying-glass')
                ->color('gray')
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
                ->modalCancelActionLabel('Закрыть')
                ->visible(fn (): bool => CdekShipment::query()->where('print_order_id', $this->record->getKey())->exists()),
            EditAction::make(),
        ];
    }

    private function loadCdekDetails(): string
    {
        $shipment = CdekShipment::query()
            ->where('print_order_id', $this->record->getKey())
            ->latest()
            ->first();

        if (!$shipment || blank($shipment->cdek_uuid)) {
            return 'У отправления нет UUID СДЭК, запросить подробности пока нельзя.';
        }

        try {
            $response = app(CdekOrderService::class)->getOrder($shipment->cdek_uuid);
            $createRequest = collect($response['requests'] ?? [])
                ->reverse()
                ->first(fn (array $request) => ($request['type'] ?? null) === 'CREATE');
            $createState = $createRequest['state'] ?? null;
            $cdekNumber = Arr::get($response, 'entity.cdek_number');

            $shipment->update([
                'status' => $createState ?: (Arr::get($response, 'entity.uuid') ? 'SUCCESSFUL' : $shipment->status),
                'cdek_number' => $cdekNumber ?: $shipment->cdek_number,
                'response_payload' => $response,
                'error_message' => $createState === 'INVALID'
                    ? json_encode($createRequest['errors'] ?? [], JSON_UNESCAPED_UNICODE)
                    : null,
            ]);
            $shipment->syncPrintOrderTrackNumber($cdekNumber, $createState);

            return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: 'Пустой ответ СДЭК.';
        } catch (Throwable $exception) {
            report($exception);

            return 'Не удалось получить подробности заказа: ' . $exception->getMessage();
        }
    }
}
