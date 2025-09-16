<a class="flex px-2 py-1 gap-2 items-center rounded border-b border-dark-100 hover:bg-dark-100
@if($chosen) bg-dark-100 bg-opacity-50 @endif"
wire:click="changeChat({{$chat['id']}})"
>
    <img src="{{$user_avatar}}" class="w-10 h-10 rounded-full" alt="">
    <span class="flex flex-col">
            <span class="text-lg font-normal">
        {{Str::limit($chat['title'], 20, '...')}}
    </span>
        <span class="text-base italic">
            @if($chat->messages[0] ?? null)
                {{Str::limit($chat->messages[0]['text'], 20, '...')}}
            @else
                Нет сообщений
            @endif
        </span>
    </span>

</a>
