<div class="w-full p-4 ">
    @filepondScripts
    <h2>{{$chat['title']}}</h2>
    <div class="container bg-white dark:bg-dark_bg dark:border dark:border-gray-300">
        <div class="flex flex-col gap-4 p-8 pb-4">
            @if(count($chat['messages']) > 0 )
                @foreach($chat['messages'] as $message)
                    <div class="flex flex-col">
                        <span
                            class="text-dark-600 dark:text-white">{{$message->user['name'] . ' ' . $message->user['surname']}}</span>
                        <div class="rounded-xl bg-green-500 px-4 py-2 w-fit max-w-fit">
                            <p class="text-lg text-white">{{$message['text']}}</p>
                        </div>
                        <span
                            class="text-dark-600 text-sm dark:text-white">{{$message['created_at']->translatedFormat('j F H:i')}}</span>
                    </div>
                @endforeach
            @else
            <span class="text-gray-100! text-4xl font-bold">
                Это чат с Вашим личным менеджером по конкретно этому изданию. В нем пока нет сообщений.
                Здесь Вы можете задать любые вопросы, а также прикреплять файлы при необходимости.
            </span>
        </div>
        @endif
        <x-ui.chat-file-upload multiple="true" wire:model="file" />
        <x-ui.input-text-area model="text"></x-ui.input-text-area>
    </div>
</div>
