<x-participation-blocks.template status="yellow" title="Предварительная проверка">
    <div class="flex flex-col p-4 gap-4">
        <p>На данный момент мы вносим указанные изменения. Срок: до 5 октября 2025. Как только они будут учтены, Вы
            получите оповещение об этом на почте и внутри нашей системы. Далее материалы можно будет еще раз проверить,
            а затем запросить дополнительные изменения или утвердить.</p>
        <x-ui.link color="yellow" class="w-full">
            <x-bi-file-arrow-down class="w-6 h-6"/>
            <span>Скачать макет</span>
        </x-ui.link>
        <p class="text-3xl font-semibold">Исправления:</p>
        <div class="flex flex-col">
            <div class="flex flex-col">
                @foreach($participation->previewComments as $comment)
                    <p class="px-2 bg-green-500 text-white rounded">{{$comment['text']}}</p>
                @endforeach
            </div>
            <x-ui.input.text-area color="brown-400"/>
        </div>

    </div>
</x-participation-blocks.template>
