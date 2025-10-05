<x-participation-blocks.template status="yellow" title="Оплата участия">
    <div class="flex flex-col py-8 px-4 gap-4">
        <div class="flex gap-8 items-center justify-center">
            <x-price-element price="{{$participation['price_part']}}" label="Участие"/>
            @if ($participation->printOrder ?? null)
                <x-price-element :plus="true" price="{{$participation->printOrder['price_print']}}"
                                 label="Печать ({{$participation->printOrder['booksCnt']}} экз.)"/>
            @else
                <x-price-element price="0"
                                 label="Печать (0 экз.)"/>
            @endif
            @if ($participation['price_check'] ?? null)
                <x-price-element :plus="true" price="{{$participation['price_check']}}" label="Проверка текста"/>
            @else
            @endif
            <x-price-element price="{{$participation['price_part']}}" label="Итого" color="yellow"/>
        </div>
        <div class="flex items-center justify-center"><x-ui.link color="yellow" class="w-full">Оплатить {{$participation['price_total']}}</x-ui.link></div>
    </div>
</x-participation-blocks.template>
