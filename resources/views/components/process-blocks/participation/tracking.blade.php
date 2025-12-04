<x-process-blocks.template status="{{$blockColor}}" title="Отслеживание">
    <div class="p-4">
        <p class="text-dark-300">Статус сборника: <b>{{$collection['status']}}</b></p>
        @if ($participation->printOrder ?? null)
            @if($collection['status']->order() < \App\Enums\CollectionStatusEnums::PRINTING->order())
                <p class="text-dark-300">
                    Предварительная дата отправки сборника в печать:
                    <b>{{formatDate($collection['date_print_start'], 'j F')}}</b>.<br>
                    Предварительная дата пересылки сборника авторам:
                    <b>{{formatDate($collection['date_print_end'], 'j F')}}</b>
                </p>
            @endif
            @if($collection['status'] == \App\Enums\CollectionStatusEnums::PRINTING)
                <p class="text-dark-300">
                    Предварительная дата пересылки сборника авторам:
                    <b>{{formatDate($collection['date_print_end'], 'j F')}}</b>
                </p>
            @endif
            @if($collection['status'] == \App\Enums\CollectionStatusEnums::DONE)
                <p class="text-dark-300 mb-4">
                    Сборник успешно был разослан всем авторам!
                </p>
                <x-ui.link target="_blank" :navigate="false" href="{{$participation->printOrder->trackingLink()}}">Отследить свою посылку</x-ui.link>
            @endif
        @else
            <p class="text-green-500 font-normal">У вас нет заказа печатных экземплярв</p>
        @endif
    </div>
</x-process-blocks.template>
