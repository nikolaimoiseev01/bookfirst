<form wire:submit="search()" {{ $attributes->merge(['class' => 'w-fit']) }}>
    <div
        class="relative w-72"
        x-data="{ text: @entangle('searchText') }"
    >
        <input
            type="text"
            x-model="text"
            placeholder="Поиск..."
            class="w-full pr-16"
        />

        {{-- Крестик очистки --}}
        <button
            type="button"
            x-show="text.length > 0"
            @click="text = ''"
            wire:click="clearSearch"
            class="absolute inset-y-0 right-12 flex items-center text-gray-400 hover:text-gray-600 hover:scale-110 transition"
            aria-label="Очистить поиск"
        >
            <x-heroicon-o-x-mark class="w-5 h-5"/>
        </button>

        {{-- Лупа / спиннер --}}
        <button type="submit"
                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:scale-110 transition z-10">
            <x-heroicon-o-magnifying-glass wire:loading.remove class="w-6 text-green-500"/>
            <x-ui.spinner class="w-5 h-5" wire:loading/>
        </button>
    </div>
</form>
