<div class="mb-16 max-w-5xl">
    @section('title')
        Страница издания книги "{{$ownBook['title']}}"
    @endsection
    <div class="mb-4">
        <livewire:components.account.own-book.survey-own-book-application :own-book="$ownBook"/>
    </div>
    <div class="mb-8 px-4 py-2 flex justify-between items-center gap-4 border rounded-2xl border-green-500">
        <div class="flex flex-col gap-1">
            <p>Общий статус: <span class="font-normal">{{$ownBook['status_general']}}</span></p>
            <p>Статус обложки: <span class="font-normal">{{$ownBook['status_cover']}}</span></p>
            <p>Статус ВБ: <span class="font-normal">{{$ownBook['status_inside']}}</span></p>
        </div>
        <div class="flex flex-col">
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
                <livewire:components.account.chat :chat="$ownBook->chat"/>
            </div>
        </div>
    </div>
    <div class="flex flex-col pl-4">
        @if($ownBook['status_general']->order() < 9)
            <x-process-blocks.own-book.general :own-book="$ownBook"/>
            <x-process-blocks.own-book.payment :own-book="$ownBook"/>
            <x-process-blocks.own-book.preview :own-book="$ownBook"/>
            <x-process-blocks.own-book.tracking :own-book="$ownBook"/>
        @else
            <div class="flex flex-col py-8 px-4 gap-4">
                <span
                    class="text-dark-300 italic text-2xl text-center">Заявка на участие в статусе "Неактуальна".</span>
            </div>
        @endif
    </div>
</div>
