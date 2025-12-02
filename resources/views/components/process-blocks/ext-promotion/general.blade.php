<x-process-blocks.template status="green" title="Моя заявка">
    <div class="flex flex-col p-4 gap-4">
        <div class="flex justify-between gap-4 md:flex-col">
            <div class="flex flex-col w-1/2 md:w-full">
                <p><b>Логин: </b><x-ui.link-simple :isLivewire="false" class="inline-flex" target="_blank" href="https://{{$extPromotion['site']}}.ru/avtor/{{$extPromotion['login']}}">{{$extPromotion['login']}}</x-ui.link-simple></p>
                <p><b>Сайт: </b>{{$extPromotion['site']}}</p>
                <p><b>Дней продвижения: </b>
                    {{$extPromotion['days']}}
                </p>
            </div>
            <div class="flex flex-col w-1/2 md:w-full">
                <p><b>Создана: </b>{{formatDate($extPromotion['created_at'], 'j F')}}</p>
                @if($extPromotion['paid_at'])
                    <p><b>Оплачена: </b>{{formatDate($extPromotion['paid_at'], 'j F')}}</p>
                @else
                    <p><b>Еще не оплачена</b></p>
                @endif
            </div>
        </div>
    </div>
</x-process-blocks.template>
