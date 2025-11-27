<x-process-blocks.template status="{{$blockColor}}" title="Предварительная проверка">
    <div class="flex flex-col p-4 gap-4">
        @if ($ownBook['status_general'] == \App\Enums\OwnBookStatusEnums::REVIEW)
            <p class="text-dark-300 font-normal">Работа с макетами еще не началась</p>
        @endif
        @if ($ownBook['status_general'] == \App\Enums\OwnBookStatusEnums::PAYMENT_REQUIRED)
            <p class="text-dark-300 font-normal">Работа с макетами начнется сразу после оплаты</p>
        @endif
        @if ($ownBook['status_general'] == \App\Enums\OwnBookStatusEnums::WORK_IN_PROGRESS)
            <div class="flex flex-col gap-4" x-data="{previewType: 'inside'}">
                <div class="flex gap-4 flex-wrap md:flex-col">
                    <x-ui.input.toggle model="previewType" :isLivewire="false"
                                       :options="['inside' => 'Внутренний блок', 'cover' => 'Обложка']"/>
                    <p><b>Статус: </b><span
                            x-text="previewType == 'inside' ? @js($statusInside) : @js($statusCover)"></span>
                    </p>
                </div>
                <div x-show="previewType == 'inside'" class="flex flex-col">
                    @if($statusInside == \App\Enums\OwnBookInsideStatusEnums::DEVELOPMENT)
                        <p>На данный момент внутренний блок находится в разработке. Предварительный
                            макет появится здесь
                            до {{formatDate($ownBook['deadline_inside'], 'j F')}}.</p>
                    @elseif($statusInside == \App\Enums\OwnBookInsideStatusEnums::PREVIEW)
                        <div class="flex flex-col gap-4">
                            <p>На данный момент внутренний блок находится на этапе предварительной
                                проверки. Это означает,
                                что все регистрационные номера присвоены, и блок сверстан. Сейчас
                                необходимо скачать файл и проверить его.<br>
                                Если исправления не требуются, пожалуйста, утвердите макет нажатием
                                на кнопку "Утвердить макет"
                                Если требуются, пожалуйста, укажите описание исправления в форме
                                ниже.
                                Когда все исправления указаны, необходимо отправить макет на
                                дальнейшее редактирование.
                                <b>Только тогда мы начнем работу над исправлениями.</b> Для этого
                                нажмите "Отправить на исправление".</p>
                            <x-ui.link color="yellow" class="flex-1" download
                                       href="{{$ownBook->getFirstMediaUrl('inside_file')}}">
                                Скачать внутренний блок
                            </x-ui.link>
                            <livewire:components.account.preview-comments model-type="OwnBook"
                                                                          :model-id="$ownBook['id']"
                                                                          comment-type="inside"/>
                        </div>
                    @elseif($statusInside == \App\Enums\OwnBookInsideStatusEnums::CORRECTIONS)
                        <div class="flex flex-col gap-4">
                            <p>На данный момент мы вносим указанные изменения. Срок:
                                до {{formatDate($ownBook['deadline_inside'], 'j F')}}. Как только
                                они будут учтены, вы получите оповещение об этом на почте и внутри
                                нашей системы.</p>
                            <x-ui.link color="yellow" class="flex-1" download
                                       href="{{$ownBook->getFirstMediaUrl('inside_file')}}">
                                Скачать внутренний блок
                            </x-ui.link>
                            <livewire:components.account.preview-comments :disabled="true"
                                                                          model-type="OwnBook"
                                                                          :model-id="$ownBook['id']"
                                                                          comment-type="inside"/>
                        </div>
                    @elseif($statusInside == \App\Enums\OwnBookInsideStatusEnums::READY_FOR_PUBLICATION)
                        <p>Внутренний блок утвержден. Как только будут утверждены и ВБ и обложка,
                            можно будет приступить к печати.</p>
                    @endif
                </div>
                <div x-show="previewType == 'cover'" class="flex flex-col">
                    @if($statusCover == \App\Enums\OwnBookCoverStatusEnums::DEVELOPMENT)
                        <p>На данный момент обложка находится в разработке. Предварительные макеты
                            появятся здесь
                            до {{formatDate($ownBook['deadline_cover'], 'j F')}}.</p>
                    @elseif($statusCover == \App\Enums\OwnBookCoverStatusEnums::PREVIEW)
                        <div class="flex flex-col gap-4">
                            <p>На данный момент обложка находится на этапе предварительной проверки.<br>
                                Если исправления не требуются, пожалуйста, утвердите макет нажатием
                                на кнопку "Утвердить макет"
                                Если требуются, пожалуйста, укажите описание исправления в форме
                                ниже.
                                Когда все исправления указаны, необходимо отправить макет на
                                дальнейшее редактирование.
                                <b>Только тогда мы начнем работу над исправлениями.</b> Для этого
                                нажмите "Отправить на исправление".</p>
                            <div class="flex gap-4 flex-wrap md:flex-col">
                                <x-ui.link color="yellow" class="flex-1" download
                                           href="{{$ownBook->getFirstMediaUrl('cover_front')}}">
                                    Скачать переднюю сторону обложки
                                </x-ui.link>
                                @if($ownBook->getFirstMediaUrl('cover_full'))
                                    <x-ui.link color="yellow" class="flex-1" download
                                               href="{{$ownBook->getFirstMediaUrl('cover_full')}}">
                                        Скачать разворот обложки
                                    </x-ui.link>
                                @endif
                            </div>
                            <livewire:components.account.preview-comments model-type="OwnBook"
                                                                          :model-id="$ownBook['id']"
                                                                          comment-type="cover"/>
                        </div>
                    @elseif($statusCover == \App\Enums\OwnBookCoverStatusEnums::CORRECTIONS)
                        <p class="mb-4">На данный момент мы вносим указанные изменения. Срок:
                            до {{formatDate($ownBook['deadline_cover'], 'j F')}}. Как только
                            они будут учтены, вы
                            получите оповещение об этом на почте и внутри нашей системы.</p>
                        <livewire:components.account.preview-comments :disabled="true"
                                                                      model-type="OwnBook"
                                                                      :model-id="$ownBook['id']"
                                                                      comment-type="cover"/>
                    @elseif($statusCover == \App\Enums\OwnBookCoverStatusEnums::READY_FOR_PUBLICATION)
                        <p>Обложка утверждена. Как только будут утверждены и ВБ и обложка, можно
                            будет приступить к печати.</p>
                    @endif
                </div>
            </div>

        @endif
        @if ($ownBook['status_general']->order() > 3)
            <p class="text-dark-300 font-normal">Работа с макетами завершена</p>
            <div class="flex flex-wrap gap-4">
                <x-ui.link class="flex-1" download
                           href="{{$ownBook->getFirstMediaUrl('cover_front')}}">
                    Скачать переднюю сторону обложки
                </x-ui.link>
                <x-ui.link class="flex-1" download
                           href="{{$ownBook->getFirstMediaUrl('cover_full')}}">
                    Скачать разворот обложки
                </x-ui.link>
                <x-ui.link class="flex-1" download
                           href="{{$ownBook->getFirstMediaUrl('inside_file')}}">
                    Скачать внутренний блок
                </x-ui.link>
            </div>
        @endif
    </div>
</x-process-blocks.template>
