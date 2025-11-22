<x-process-blocks.template status="{{$blockColor}}" title="Отслеживание">
    <div class="p-4">
        @if ($ownBook->firstPrintOrder() ?? null)
            <p class="">Статус издания: <b>{{$ownBook['status_general']}}</b></p>
            <x-ui.link-simple :isLivewire="false" href="#Моя заявка">Подробности заказа</x-ui.link-simple>
            @if($ownBook['status_general']->order() < \App\Enums\OwnBookStatusEnums::PRINT_PAYMENT_REQUIRED->order())
                <p class="text-dark-300">Есть заказ печатных экземпляров.
                    Как только работа с макетами будет завершена, в этом блоке появится возможность оплатить заказ.</p>
            @endif
            @if($ownBook['status_general'] == \App\Enums\OwnBookStatusEnums::PRINT_PAYMENT_REQUIRED)
                <p class="mb-4">Макеты успешно утверждены! Мы готовы приступить к печати. Для этого необходимо оплатить заказ</p>
                <x-ui.button wire:click="createPayment({{$ownBook->firstPrintOrder()['price_print']}}, 'printOnly')" class="w-full" color="yellow">Оплатить {{$ownBook->firstPrintOrder()['price_print']}} руб.</x-ui.button>
            @endif
            @if($ownBook['status_general'] == \App\Enums\OwnBookStatusEnums::PRINT_WAITING)
                <p class="mb-4">
                    Мы успешно приняли оплату и подготавливаем все макеты и упаковку. В течение 3-х дней начнется печать экземпляров.
                </p>
            @endif
            @if($ownBook['status_general'] == \App\Enums\OwnBookStatusEnums::PRINTING)
                <p class="text-dark-300 mb-4">Прямо сейчас идет печать заказа.</p>
            @endif
            @if($ownBook['status_general'] == \App\Enums\OwnBookStatusEnums::DONE)
                <p class="text-dark-300 mb-4">Печать завершена!</p>
                <x-ui.link>Отследить</x-ui.link>
            @endif
        @else
            <p class="text-dark-300 font-normal">У вас нет заказа печатных экземплярв</p>
        @endif
    </div>
</x-process-blocks.template>
