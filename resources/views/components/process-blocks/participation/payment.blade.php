<x-process-blocks.template status="{{$blockColor}}" title="Оплата участия">
    <div class="flex flex-col py-8 px-4 gap-4">
        <div class="flex gap-8 items-center justify-center flex-wrap">
            <x-price-element color="{{$blockColor}}" price="{{$participation['price_part']}}" label="Участие"/>
            @if ($participation->printOrder ?? null)
                <x-price-element color="{{$blockColor}}" :plus="true"
                                 price="{{$participation->printOrder['price_print']}}"
                                 label="Печать ({{$participation->printOrder['books_cnt']}} экз.)"/>
            @else
                <x-price-element color="{{$blockColor}}" :plus="true" price="0"
                                 label="Печать (0 экз.)"/>
            @endif
            @if ($participation['price_check'] ?? null)
                <x-price-element color="{{$blockColor}}" :plus="true" price="{{$participation['price_check']}}"
                                 label="Проверка текста"/>
            @else
            @endif

            <x-price-element price="{{$participation['price_total'] + ($printOrder ? $printOrder['price_print'] : 0)}}"
                             label="Итого" :bigElement="true" color="{{$blockColor}}"/>
        </div>
        @if ($collection['status'] <> \App\Enums\CollectionStatusEnums::APPS_IN_PROGRESS
            && $participation['status'] <> \App\Enums\ParticipationStatusEnums ::APPROVED)
        <span class="text-dark-300 italic text-2xl text-center">На данный момент сборник находится на этапе предварительной проверки. Вы не произвели оплату, поэтому не видите макет. Если хотите участвовать в сборнике и оплатить, пожалуйста, напишите нам в чате. Иначе заявка перейдет в статус "неактуальна" при следующем изменении статуса сборника.</span>
        @elseif($participation['status'] == \App\Enums\ParticipationStatusEnums::APPROVE_NEEDED)
        <span class="text-dark-300 italic text-2xl text-center">Сейчас ваша заявка проверяется. Как только проверка будет завершена, вы получите оповещение по почте, а в этом блоке появится возможность оплаты.</span>
        @elseif ($participation['status'] == \App\Enums\ParticipationStatusEnums::PAYMENT_REQUIRED)
            <div class="flex gap-4">
                @if($paidAmount > 0)
                    <p class="text-green-400 text-nowrap italic">Уже оплачено: {{$paidAmount}}</p>
                @endif
                <x-ui.button wire:click="createPayment({{$amountToPay}})" color="yellow" class="w-full">
                    @if($paidAmount > 0)
                        Доплатить
                    @else
                        Оплатить
                    @endif
                    {{$amountToPay}} руб.
                </x-ui.button>
            </div>
        @endif
    </div>
</x-process-blocks.template>
