<div class="mb-16 max-w-5xl">
    @section('title')
        Страница издания книги "{{$ownBook['title']}}"
    @endsection
    <div class="flex flex-col" x-data="{showChat: false}">
        <x-ui.button @click="showChat=!showChat" x-text="!showChat ? 'Чат с личным менеджером' : 'Свернуть чат'"/>
        <div x-show="showChat"
             x-cloak
             x-collapse.duration.400ms>
            <div class="pt-4">
                <livewire:components.account.chat :chat="$ownBook->chat"/>
            </div>

        </div>
    </div>
</div>
