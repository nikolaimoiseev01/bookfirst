<x-process-blocks.template status="{{$blockColor}}" title="Оплата издания">
    <div class="flex flex-col py-8 px-4 gap-4">
        <div class="flex gap-8 items-center justify-center flex-wrap">
            <x-price-element color="{{$blockColor}}" price="{{$ownBook['price_inside']}}" label="Работа с блоком"/>
            @if ($ownBook->initialPrintOrder ?? null)
                <x-price-element color="bright" :plus="true"
                                 price="{{$ownBook->initialPrintOrder['price_print']}}"
                                 label="Печать ({{$ownBook->initialPrintOrder['books_cnt']}} экз.)*"/>
            @else
                <x-price-element color="bright" :plus="true" price="0"
                                 label="Печать (0 экз.)"/>
            @endif
            @if ($ownBook['price_cover'] > 0)
                <x-price-element color="{{$blockColor}}" :plus="true" price="{{$ownBook['price_cover']}}"
                                 label="Работа с обложкой"/>
            @endif
            @if ($ownBook['price_promo'] > 0)
                <x-price-element color="{{$blockColor}}"
                                 :plus="true"
                                 price="{{$ownBook['price_promo']}}"
                                 label="Продвижение"/>
            @endif

            <x-price-element price="{{$ownBook['price_total'] + ($printOrder ? $printOrder['price_print'] : 0)}}"
                             label="Итого" :bigElement="true" color="{{$blockColor}}"/>
        </div>
        <span class="text-dark-300 italic font-light text-2xl text-center">*На данном этапе оплата производится за все услуги, кроме печати, так как цена печати может измениться после утверждения макетов.</span>
        @if ($ownBook['status_general'] == \App\Enums\OwnBookStatusEnums::REVIEW)
            <span class="text-dark-300 italic text-2xl text-center">Сейчас ваша заявка проверяется. Как только проверка будет завершена, вы получите оповещение по почте, а в этом блоке появится возможность оплаты.</span>
        @elseif ($ownBook['status_general'] == \App\Enums\OwnBookStatusEnums::PAYMENT_REQUIRED)
            <x-ui.button wire:click="createPayment({{$ownBook['price_total']}}, 'firstPayment')" color="yellow" class="w-full">
                Оплатить
                {{$ownBook['price_total']}} руб.
            </x-ui.button>
        @endif
    </div>
</x-process-blocks.template>
