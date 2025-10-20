<x-participation-blocks.template status="{{$blockColor}}" title="Отслеживание">
    <div class="p-4">
        @if ($participation->printOrder ?? null)
            @if($collection['collection_status_id'] == 1)
                <p class="text-dark-300 font-normal">Сборник еще на этапе приеме заявок. {{$collection['date_print_start']}} сборник пойдет в печать,
                    а {{$collection['date_print_end']}} должен быть отправлен авторам</p>
            @elseif($collection['collection_status_id'] == 2)
            @endif
        @else
            <p class="text-green-500 font-normal">У вас нет заказа печатных экземплярв</p>
        @endif
    </div>
</x-participation-blocks.template>
