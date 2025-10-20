<x-participation-blocks.template status="{{$blockColor}}" title="Голосование">
    <div class="flex flex-col p-4 gap-4">
        @if ($collection['collection_status_id'] == 1)
            <p class="text-dark-300 font-normal">На данный момент сборник находится в статусе приема
                заявок. {{$collection['date_preview_start']}} до
                23:59
                МСК в этом блоке откроется возможность проголосовать за лучшего автора</p>

        @elseif ($collection['collection_status_id'] == 2)

            <livewire:components.account.collection.votes :collection="$collection"
                                                          :participation-id="$participation['id']"/>

        @elseif ($collection['collection_status_id'] == 3)
            <p>На данный момент мы вносим указанные изменения. Срок: до {{$collection['date_preview_end']}}. Как только
                они будут учтены, вы
                получите оповещение об этом на почте и внутри нашей системы. Далее материалы можно будет еще раз
                проверить,
                а затем запросить дополнительные изменения или утвердить.</p>
        @endif
    </div>
</x-participation-blocks.template>
