<x-filament-panels::page>
    <x-filament::section>
        <dl class="grid gap-4 sm:grid-cols-2">
            <div><dt class="text-sm text-gray-500">Тип отправления</dt><dd class="font-medium">{{ $shipment->shipment_type === 'collections' ? 'Сборники' : 'Книга' }}</dd></div>
            <div><dt class="text-sm text-gray-500">Номер СДЭК</dt><dd class="font-medium">{{ $shipment->cdek_number ?: 'Ожидается' }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-sm text-gray-500">Содержимое</dt><dd class="font-medium">{{ $shipment->book_title ?: implode(', ', $shipment->book_titles ?? []) }}</dd></div>
            <div><dt class="text-sm text-gray-500">Получатель</dt><dd class="font-medium">{{ $shipment->recipient_name }} · {{ $shipment->recipient_phone }}</dd></div>
            <div><dt class="text-sm text-gray-500">Пункт выдачи</dt><dd class="font-medium">{{ $shipment->delivery_point ?: '—' }}</dd></div>
            <div><dt class="text-sm text-gray-500">Статус</dt><dd class="font-medium">{{ $shipment->status }}</dd></div>
            <div><dt class="text-sm text-gray-500">UUID СДЭК</dt><dd class="font-medium">{{ $shipment->cdek_uuid ?: '—' }}</dd></div>
        </dl>
        @if ($shipment->error_message)
            <div class="mt-4 rounded-lg bg-danger-50 p-3 text-danger-700">{{ $shipment->error_message }}</div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
