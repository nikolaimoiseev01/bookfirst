<button class="text-xl font-light hover:text-green-500 text-dark-400 text-center rounded min-w-36"
        wire:click="loadMore">
    <span wire:loading.remove>Загрузить еще</span>
    <x-ui.spinner wire:loading class="w-6 h-6"/>
</button>
