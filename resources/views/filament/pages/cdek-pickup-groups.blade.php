<x-filament-panels::page>
    @include('filament.pages.components.cdek-tabs', ['active' => 'groups'])
    {{ $this->table }}
</x-filament-panels::page>
