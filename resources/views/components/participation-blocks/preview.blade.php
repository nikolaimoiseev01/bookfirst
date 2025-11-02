<x-participation-blocks.template status="{{$blockColor}}" title="Предварительная проверка">
    <div class="flex flex-col p-4 gap-4">
        @if ($collection['status'] == \App\Enums\CollectionStatusEnums::APPS_IN_PROGRESS)
            <p class="text-dark-300 font-normal">На данный момент сборник находится в статусе приема
                заявок. {{$collection['date_preview_start']}} до
                23:59
                МСК в этом блоке откроется возможность скачать внутренний блок сборника, чтобы проверить верстку и
                внести
                комментарии</p>
        @endif

        @if ($collection['status'] == \App\Enums\CollectionStatusEnums::PREVIEW && $participation['status'] == \App\Enums\ParticipationStatusEnums::APPROVED)
            <p>На данный момент сборник находится на этапе предварительной проверки. Это означает, что все
                регистрационные
                номера присвоены, и блок сверстан. Сейчас необходимо скачать файл, найти свой блок и
                указать комментарии, что бы вы хотели исправить в своем блоке.
                Пожалуйста, укажите страницу исправления, а также описание того, что нужно исправить.</p>
            <x-ui.link href="{{$collection->getFirstMediaUrl('inside_file')}}" :navigate="false"
                       download="{{$collection['title']}}.pdf" color="yellow" class="w-full">
                <x-bi-file-arrow-down class="w-6 h-6"/>
                <span>Скачать макет</span>
            </x-ui.link>

            <livewire:components.account.preview-comments model-type="Participation" :model-id="$participation['id']"
                                                          comment-type="inside"/>
        @endif

        @if ($collection['status'] == \App\Enums\CollectionStatusEnums::DONE && $participation['status'] == \App\Enums\ParticipationStatusEnums::APPROVED)
            <p>На данный момент сборник был проверен авторами и можно скачать финальный макет внутреннего блока.</p>
            <x-ui.link href="{{$collection->getFirstMediaUrl('inside_file')}}" :navigate="false"
                       download="{{$collection['title']}}.pdf" color="green" class="w-full">
                <x-bi-file-arrow-down class="w-6 h-6"/>
                <span>Скачать макет</span>
            </x-ui.link>
        @endif

        @if ($collection['status'] == \App\Enums\CollectionStatusEnums::PRINT_PREPARE)
            <p class="">На данный момент мы вносим указанные изменения. Срок: до {{formatDate($collection['date_preview_end'], 'j F')}}. Как только
                они будут учтены, вы
                получите оповещение об этом на почте и внутри нашей системы. Далее материалы направятся в печать.</p>
        @endif

        @if (($collection['status'] == \App\Enums\CollectionStatusEnums::PREVIEW || $collection['status'] == \App\Enums\CollectionStatusEnums::PRINT_PREPARE) && $participation['status'] != \App\Enums\ParticipationStatusEnums::APPROVED)
            <p class="text-dark-300 font-normal">На данный момент сборник находится на этапе предварительной проверки. Вы не произвели оплату, поэтому не видите макет. На этом этапе еще можно произвести оплату в блоке выше, чтобы продолжить участвовать в сборнике.</p>
        @endif
    </div>
</x-participation-blocks.template>
