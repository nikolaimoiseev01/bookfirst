<x-filament-panels::page>
    @include('filament.pages.components.cdek-tabs', ['active' => 'shipments'])
    {{ $this->table }}
</x-filament-panels::page>
