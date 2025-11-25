<div>
    <x-ui.modal name="subscribeModal">
        @if(!$subscribed)
            <form
                wire:submit="subscribe"
                class="flex flex-col gap-4 p-4"
            >
                <h3 class="text-2xl md:text-xl font-semibold text-dark-400">Подписаться на новости</h3>
                <x-ui.input.text required type="email" id="email_subscription" wire:model="email"
                                 placeholder="Введите email"
                />
                <x-ui.button>Подписаться</x-ui.button>
            </form>
        @else
            <h3 class="text-green-500 text-2xl md:!text-xl text-center font-semibold p-4">
                Вы успешно подписаны на рассылку новостей!</h3>
        @endif
    </x-ui.modal>

    <x-ui.link @click="$dispatch('open-modal', 'subscribeModal')" color="white"
               class="!text-lg !py-0 !px-4">Подписаться на новости
    </x-ui.link>
</div>
