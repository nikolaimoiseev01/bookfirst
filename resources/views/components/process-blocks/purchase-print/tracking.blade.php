<x-process-blocks.template status="{{$blockColor}}" title="Отслеживание">
    <div class="p-4">
        @if($printOrder['status'] !== \App\Enums\PrintOrderStatusEnums::SENT && $printOrder['status'] !== \App\Enums\PrintOrderStatusEnums::PAID)
            <p class="text-dark-300">Текущий статус заказа: {{$printOrder['status']}}<br>Как только он будет отправлен, здесь появится ссылка для его
                отслеживания</p>
        @endif
        @if($printOrder['status'] == \App\Enums\PrintOrderStatusEnums::PAID)
            <p class="text-dark-300">Мы успешно приняли оплату и подготавливаем все макеты и
                упаковку. В течение 3-х дней начнется печать экземпляров.</p>
        @endif
        @if($printOrder['status'] == \App\Enums\PrintOrderStatusEnums::SENT)
            <p class="text-dark-300 mb-4">Печать завершена!</p>
            <x-ui.link :navigate="false" target="_blank"
                       href="{{$printOrder->trackingLink()}}">Отследить
            </x-ui.link>
        @endif
    </div>
</x-process-blocks.template>
