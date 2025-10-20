<x-participation-blocks.template status="{{$blockColor}}" title="Оплата участия">
    <div class="flex flex-col py-8 px-4 gap-4">
        <div class="flex gap-8 items-center justify-center">
            <x-price-element color="{{$blockColor}}" price="{{$participation['price_part']}}" label="Участие"/>
            @if ($participation->printOrder ?? null)
                <x-price-element color="{{$blockColor}}" :plus="true" price="{{$participation->printOrder['price_print']}}"
                                 label="Печать ({{$participation->printOrder['booksCnt']}} экз.)"/>
            @else
                <x-price-element color="{{$blockColor}}" :plus="true" price="0"
                                 label="Печать (0 экз.)"/>
            @endif
            @if ($participation['price_check'] ?? null)
                <x-price-element color="{{$blockColor}}" :plus="true" price="{{$participation['price_check']}}" label="Проверка текста"/>
            @else
            @endif

            <x-price-element price="{{$participation['price_part']}}" label="Итого" :bigElement="true" color="{{$blockColor}}"/>
        </div>
        @if ($participation['participation_status_id'] == 1)
            <span class="text-dark-300 italic text-2xl text-center">Сейчас ваша заявка проверяется. Как только проверка будет завершена, вы получите оповещение по почте, а в этом блоке появится возможность оплаты.</span>
        @elseif ($participation['participation_status_id'] == 2)
            <x-ui.button color="yellow" class="w-full">Оплатить {{$participation['price_total']}} руб.</x-ui.button>
        @endif
    </div>
</x-participation-blocks.template>
