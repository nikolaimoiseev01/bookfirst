<div class="mb-16 max-w-5xl">
    @section('title')
        Мое участие в сборнике {{$participation->collection['title']}}
    @endsection
    <div class="mb-4">
        <livewire:components.account.collection.survey-participation :participation="$participation"/>
    </div>
    <x-ui.link-simple class="mb-4">Инструкция по этой странице</x-ui.link-simple>
    <div class="mb-8 px-4 py-2 flex justify-between items-center gap-4 border rounded-2xl border-green-500">
        <div class="flex flex-col gap-1">
            <p>Мой статус участия: <span class="font-normal">{{$participation->participationStatus['name']}}</span></p>
            <p>Статус издания сборника: <span
                    class="font-normal">{{$participation->collection->collectionStatus['name']}}</span></p>
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
    <div class="flex flex-col pl-4">
        <x-participation-blocks.general :part="$participation"/>
        <x-participation-blocks.payment :part="$participation"/>
        <x-participation-blocks.preview :part="$participation"/>
        <x-participation-blocks.voting :part="$participation"/>
        <x-participation-blocks.tracking :part="$participation"/>
    </div>

</div>
