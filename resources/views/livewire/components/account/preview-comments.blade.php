<div class="flex flex-col gap-8">
    <p class="text-3xl font-semibold">Исправления:</p>
    <div class="flex flex-col gap-4">
        @foreach($comments as $comment)
            <div class="flex flex-col w-fit">
                <p class="px-3 py-1 {{$comment['flg_done'] ? 'bg-green-500' : 'bg-dark-300'}} text-white rounded-xl w-full">
                    @if ($comment['page'])
                        Стр.: {{$comment['page']}}.
                    @endif
                    {{$comment['text']}}
                </p>
                <p class="text-dark-350 italic text-lg">Статус: {{$comment['flg_done'] ? 'учтено' : 'в работе'}}</p>
            </div>

        @endforeach
    </div>
    <div class="flex flex-col">
        <div class="flex flex-col gap-2">
            <x-ui.input.text color="yellow" type="number" wire:model="page" class="!w-max" placeholder="Страница"/>
            <x-ui.input.text-area color="brown-400"/>
        </div>
    </div>
</div>
