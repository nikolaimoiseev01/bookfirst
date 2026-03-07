<x-process-blocks.template status="{{$blockColor}}" title="Оплата печати">
    <div class="flex flex-col py-8 px-4 gap-4">
        <div class="flex gap-8 items-center justify-center">
            <x-price-element price="{{$printOrder['price_print']}}"
                             label="Итого" :bigElement="true" color="{{$blockColor}}"/>
        </div>
        @if ($printOrder['status'] == \App\Enums\PrintOrderStatusEnums::CREATED)
            <span class="text-dark-300 italic text-2xl text-center">Сейчас ваша заявка проверяется. Как только проверка будет завершена, вы получите оповещение по почте, а в этом блоке появится возможность оплаты.</span>
        @elseif ($printOrder['status'] == \App\Enums\PrintOrderStatusEnums::PAYMENT_REQUIRED)
            <div class="flex gap-4">
                <x-ui.button wire:click="createPayment({{$printOrder['price_print']}})" color="yellow" class="w-full">
                    Оплатить
                    {{$printOrder['price_print']}} руб.
                </x-ui.button>
            </div>
        @endif
    </div>
</x-process-blocks.template>
