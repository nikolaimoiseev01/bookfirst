<x-filament-panels::page>
    <form wire:submit="process">
        {{ $this->form }}

        <div class="flex justify-end gap-2 mt-6">
            @foreach($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>
</x-filament-panels::page>
