<x-participation-blocks.template status="{{$blockColor}}" title="Голосование">
    <div class="flex flex-col p-4 gap-4">
        @if ($collection['status'] == \App\Enums\CollectionStatusEnums::APPS_IN_PROGRESS)
            <p class="text-dark-300 font-normal">На данный момент сборник находится в статусе приема
                заявок. {{$collection['date_preview_start']}} до
                23:59
                МСК в этом блоке откроется возможность проголосовать за лучшего автора</p>
        @endif

        @if ($collection['status']->order() > 1 && $participation['status'] == \App\Enums\ParticipationStatusEnums::APPROVED)
            <livewire:components.account.collection.votes :collection="$collection"
                                                          :participation-id="$participation['id']"/>
        @endif

        @if ($collection['status'] == \App\Enums\CollectionStatusEnums::PREVIEW && $participation['status'] != \App\Enums\ParticipationStatusEnums::APPROVED)
            <p class="text-dark-300 font-normal">На данный момент сборник находится на этапе предварительной проверки.
                Вы не произвели оплату, поэтому не видите макет. На этом этапе еще можно произвести оплату в блоке выше,
                чтобы продолжить участвовать в сборнике.</p>
        @endif
    </div>
</x-participation-blocks.template>
