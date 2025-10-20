<x-participation-blocks.template status="{{$blockColor}}" title="Предварительная проверка">
    <div class="flex flex-col p-4 gap-4">
        @if ($collection['collection_status_id'] == 1)
            <p class="text-dark-300 font-normal">На данный момент сборник находится в статусе приема
                заявок. {{$collection['date_preview_start']}} до
                23:59
                МСК в этом блоке откроется возможность скачать внутренний блок сборника, чтобы проверить верстку и
                внести
                комментарии</p>

        @elseif ($collection['collection_status_id'] == 2)
            <p>Сблок сверстан, проверяйте!)</p>
            <x-ui.link color="yellow" class="w-full">
                <x-bi-file-arrow-down class="w-6 h-6"/>
                <span>Скачать макет</span>
            </x-ui.link>

            <livewire:components.account.preview-comments model-type="Participation" :model-id="$participation['id']"
                                                          comment-type="inside"/>

        @elseif ($collection['collection_status_id'] == 3)
            <p>На данный момент мы вносим указанные изменения. Срок: до {{$collection['date_preview_end']}}. Как только они будут учтены, вы
                получите оповещение об этом на почте и внутри нашей системы. Далее материалы можно будет еще раз
                проверить,
                а затем запросить дополнительные изменения или утвердить.</p>
        @endif
    </div>
</x-participation-blocks.template>
