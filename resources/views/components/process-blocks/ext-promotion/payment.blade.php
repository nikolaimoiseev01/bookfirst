<x-process-blocks.template status="{{$blockColor}}" title="Оплата участия">
    <div class="flex flex-col py-8 px-4 gap-4">
        <div class="flex gap-8 items-center justify-center">
            <x-price-element price="{{$extPromotion['price_total']}}"
                             label="Итого" :bigElement="true" color="{{$blockColor}}"/>
        </div>
        @if ($extPromotion['status'] == \App\Enums\ExtPromotionStatusEnums::REVIEW)
            <span class="text-dark-300 italic text-2xl text-center">Сейчас ваша заявка проверяется. Как только проверка будет завершена, вы получите оповещение по почте, а в этом блоке появится возможность оплаты.</span>
        @elseif ($extPromotion['status'] == \App\Enums\ExtPromotionStatusEnums::PAYMENT_REQUIRED)
            <div class="flex gap-4">
                <x-ui.button wire:click="createPayment({{$extPromotion['price_total']}})" color="yellow" class="w-full">
                    Оплатить
                    {{$extPromotion['price_total']}} руб.
                </x-ui.button>
            </div>

        @endif
    </div>
</x-process-blocks.template>
