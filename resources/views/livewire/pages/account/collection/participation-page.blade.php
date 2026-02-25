<div class="mb-16 max-w-5xl">
    @section('title')
        Мое участие в сборнике {{$participation->collection['title']}}
    @endsection
    <div class="mb-4">
        <livewire:components.account.collection.survey-participation :participation="$participation"/>
    </div>
    <x-ui.link-simple href="{{route('portal.help.collection')}}" class="mb-4">Инструкция по этой странице</x-ui.link-simple>
    <div class="mb-8 px-4 py-2 flex justify-between items-center gap-4 border rounded-2xl border-green-500 lg:flex-col">
        <div class="flex flex-col gap-1">
            <p>Мой статус участия: <span class="font-normal">{{$participation['status']}}</span></p>
            <p>Статус издания сборника: <span
                    class="font-normal">{{$participation->collection['status']}}</span></p>
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
                <livewire:components.account.chat :chat="$participation->chat"/>
            </div>
        </div>
    </div>
        @if($participation->collection['status'] == \App\Enums\CollectionStatusEnums::DONE)
            <div class="mb-8 border rounded-2xl border-green-500 flex-wrap md:flex-col px-4 py-2">
                <p class="font-medium">Мы поздравляем вас с окончанием процесса публикации в сборнике современных авторов!
                    Мы стараемся каждый день, чтобы развивать литературное дело в РФ и СНГ.
                    Будем признательны, если вы оставите отзыв о процессе издания в
                    <x-ui.link-simple :isLivewire="false" class="inline-block font-medium"
                                      target="_blank" href="https://vk.com/topic-122176261_35858257">
                        нашей группе Вконтакте
                    </x-ui.link-simple>
                </p>
            </div>
        @endif
    <div class="flex flex-col pl-4 md:pl-0 md:gap-8">
        @if($participation['status']->order() < 9)
            <x-process-blocks.participation.general :part="$participation"/>
            <x-process-blocks.participation.payment :part="$participation"/>
            <x-process-blocks.participation.preview :part="$participation"/>
            <x-process-blocks.participation.voting :part="$participation"/>
            <x-process-blocks.participation.tracking :part="$participation"/>
        @else
            <div class="flex flex-col py-8 px-4 gap-4">
                <span
                    class="text-dark-300 italic text-2xl text-center">Заявка на участие в статусе "Неактуальна".</span>
            </div>
        @endif
    </div>

</div>
