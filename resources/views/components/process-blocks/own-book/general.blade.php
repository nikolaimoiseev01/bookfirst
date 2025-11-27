<x-process-blocks.template status="green" title="Моя заявка">
    <div class="flex flex-col p-4 gap-4">
        <div class="flex justify-between gap-4 md:flex-col">
            <div class="flex flex-col w-1/2 md:w-full">
                <p><b>Книга: </b>{{$ownBook['author']}}: '{{$ownBook['title']}}'</p>
                <p><b>Страниц: </b>{{$ownBook['pages']}}</p>
                <p><b>Внутренний блок: </b>{{$ownBook['need_text_design'] ? 'необходим дизайн;' : ''}} {{$ownBook['need_text_check'] ? 'необходима проверка;' : ''}}</p>
                <p><b>Обложка: </b>{{$ownBook['cover_ready'] ? 'готова от автора' : 'необходимо создание'}}</p>
                <p><b>Продвижение: </b>{{$ownBook['price_promo'] ? 'вариант ' . $ownBook['internal_promo_type'] : 'не требуется'}}</p>
            </div>
            <div class="flex flex-col w-1/2 md:w-full">
                @if($ownBook->initialPrintOrder ?? null)
                    <p class="font-semibold text-2xl">Печатные экземпляры</p>
                    <p><b>Параметры печати: </b>{{$ownBook->initialPrintOrder['books_cnt']}} экз., Обложка: {{$ownBook->initialPrintOrder['cover_type']}}, Внутренний блок: {{$ownBook->initialPrintOrder['inside_color']}}</p>
                    <p><b>Фио получателя: </b>{{$ownBook->initialPrintOrder['receiver_name']}}</p>
                    <p><b>Телефон получателя: </b>{{$ownBook->initialPrintOrder['receiver_telephone']}}</p>
                    <p><b>Адрес получателя: </b>{{$ownBook->initialPrintOrder['address_json']['string']}}</p>
                @else
                    <p class="font-semibold text-2xl">Печатные экземпляры не требуются</p>
                @endif
            </div>
        </div>
    </div>
</x-process-blocks.template>
