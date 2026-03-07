<x-process-blocks.template status="green" title="Моя заявка">
    <div class="flex flex-col p-4 gap-4">
        <div class="flex justify-between gap-4 md:flex-col">
            <div class="flex flex-col w-1/2 md:w-full">
                <p><b>Издание: </b><x-ui.link-simple class="inline-block" :isLivewire="false" target="_blank" href="{{$printOrder->model->portalPage()}}">{{$printOrder->model['title']}}</x-ui.link-simple> </p>
                <p><b>Дата создания: </b>{{formatDate($printOrder['created_at'], 'j F')}}</p>
                @if($printOrder['paid_at'])
                    <p><b>Оплачена: </b>{{formatDate($printOrder['paid_at'], 'j F')}}</p>
                @else
                    <p><b>Еще не оплачена</b></p>
                @endif
            </div>
            <div class="flex flex-col w-1/2 md:w-full">
                <p><b>Количество: </b>{{$printOrder['books_cnt']}} экз.</p>
                <p><b>Фио получателя: </b>{{$printOrder['receiver_name']}}</p>
                <p><b>Телефон получателя: </b>{{$printOrder['receiver_telephone']}}</p>
                <p><b>Адрес получателя: </b>{{$printOrder['address_json']['string']}}</p>
            </div>
        </div>
    </div>
</x-process-blocks.template>
