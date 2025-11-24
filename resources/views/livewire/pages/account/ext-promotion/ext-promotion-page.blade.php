<div class="mb-16 max-w-5xl">
    @section('title')
        Продвижение на сайте {{$extPromotion['site']}}
    @endsection
    <div class="mb-4">
        <livewire:components.account.ext-promotion.survey-ext-promotion :ext-promotion="$extPromotion"/>
    </div>
    <x-ui.link-simple href="{{route('portal.help.ext_promotion')}}" class="mb-4">Инструкция по этой странице</x-ui.link-simple>
    <div class="mb-8 px-4 py-2 flex flex-col justify-between items-center gap-2 border rounded-2xl border-green-500">
            <p>Мой статус продвижения: <span class="font-normal">{{$extPromotion['status']}}</span></p>
        <div class="flex gap-4 text-lg">
            <div class="flex gap-2 items-center"><span class="w-4 h-4 rounded-full bg-dark-200"></span>Пункт недоступен
            </div>
            <div class="flex gap-2 items-center"><span class="w-4 h-4 rounded-full bg-brown-300"></span>Необходимо
                действие
            </div>
            <div class="flex gap-2 items-center"><span class="w-4 h-4 rounded-full bg-green-500"></span>Успешно
                выполнено
            </div>
        </div>
    </div>
    <div class="flex flex-col mb-8" x-data="{showChat: false}">
        <x-ui.button @click="showChat=!showChat" x-text="!showChat ? 'Чат с личным менеджером' : 'Свернуть чат'"/>
        <div x-show="showChat"
             x-cloak
             x-collapse.duration.400ms>
            <div class="mt-4 container">
                <livewire:components.account.chat :chat="$extPromotion->chat"/>
            </div>
        </div>
    </div>
    <div class="flex flex-col pl-4">
        @if($extPromotion['status']->order() < 999)
            <x-process-blocks.ext-promotion.general :extPromotion="$extPromotion"/>
            <x-process-blocks.ext-promotion.payment :extPromotion="$extPromotion"/>
            <x-process-blocks.ext-promotion.statistic :extPromotion="$extPromotion"/>
        @else
            <div class="flex flex-col py-8 px-4 gap-4">
                <span
                    class="text-dark-300 italic text-2xl text-center">Заявка на продвижение в статусе "Неактуальна".</span>
            </div>
        @endif
    </div>

</div>
