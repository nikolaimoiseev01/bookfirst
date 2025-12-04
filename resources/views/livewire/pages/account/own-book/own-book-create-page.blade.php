<form x-data="{
            insideType: $wire.entangle('insideType'),
            coverReady: $wire.entangle('coverReady'),
            needTextDesign: $wire.entangle('needTextDesign'),
            needTextCheck: $wire.entangle('needTextCheck'),
            needPrint: $wire.entangle('needPrint'),
            insideColor: $wire.entangle('insideColor'),
            coverType: $wire.entangle('coverType'),
            needPromo: $wire.entangle('needPromo')
        }"
      wire:submit="checkAndConfirm()" class="mb-16 max-w-6xl">
    @section('title')
        Создание заявки на издание собственной книги
    @endsection

    <div class="flex container mb-8 lg:flex-col">
        <div class="flex flex-col flex-1 border-r border-dark-300 lg:border-r-0 lg:border-b">
            <div class="flex flex-col p-4 border-b border-dark-300" x-data="{show:false}">
                <div @click="show = !show" class="flex justify-between cursor-pointer">
                    <h3 class="text-3xl font-medium">Общая информация</h3>
                    <x-bi-chevron-down x-bind:class="show ? 'rotate-180' : ''"
                                       class="w-8 h-auto cursor-pointer transition"/>
                </div>

                <div class="flex gap-4 md:flex-wrap" x-show="show" x-cloak
                     x-collapse.duration.400ms>
                    <x-ui.input.text name="author" class="" label="Автор*" wire:model="author"/>
                    <x-ui.input.text name="title" class="" label="Название книги*"
                                     wire:model="title"/>
                </div>
            </div>

            <div class="flex flex-col p-4 border-b border-dark-300" x-data="{show:false}">
                <div class="flex justify-between cursor-pointer flex-wrap gap-2">
                    <h3 @click="show = !show" class="text-3xl font-medium">Внутренний блок</h3>
                    <div class="flex gap-2">
                        <div x-show="show" @click="show = true" x-transition>
                            <x-ui.input.toggle model="insideType"
                                               :options="['Файлом' => 'Файлом', 'Из системы' => 'Из системы']"/>
                        </div>

                        <x-bi-chevron-down @click="show = !show"
                                           x-bind:class="show ? 'rotate-180' : ''"
                                           class="w-8 h-auto cursor-pointer transition"/>
                    </div>
                </div>

                <div x-show="show" x-cloak x-collapse.duration.400ms>
                    <div class="flex flex-col gap-2 mb-4 pt-4" x-show="insideType == 'Файлом'">
                        <div class="flex flex-col gap-4">
                            <x-ui.input.text-area required
                                                  description="Загрузите файлы внутреннего блока (иконка скрепки в углу это поля ввода) и укажите любой комментарий (при необходимости) по оформлению. Затем введите количество страниц в загружаемых файлах."
                                                  text-model="commentAuthorInside"
                                                  files-model="insideFiles"
                                                  :attachable="true"
                                                  :sendable="false" :multiple="true"/>
                            <div class="flex gap-2 items-center flex-wrap">
                                <p class="text-nowrap">Количество загруженных страниц:</p>
                                <x-ui.question-mark class="md:hidden">Несмотря на то, что количество
                                    может имзениться при редактировании,
                                    стоимость считается от количества загруженных страниц
                                </x-ui.question-mark>
                                <input id="pages" min:30 wire:model.live="pages" type="number">
                            </div>
                        </div>

                    </div>

                    <div class="flex flex-col gap-2 mb-4 pt-4" x-show="insideType == 'Из системы'">
                        <x-ui.work-choose :userWorks="$userWorks"/>
                        <x-ui.input.text-area required
                                              description="Укажите любой комментарий (при необходимости) по оформлению."
                                              text-model="commentAuthorInside"
                                              files-model="insideFiles"
                                              :attachable="false"
                                              :sendable="false" :multiple="true"/>
                    </div>

                    <div class="flex gap-2 flex-wrap">
                        <div class="flex gap-2 items-center">
                            <label for="needTextDesign">Нужен дизайн текста</label>
                            <x-ui.question-mark>Подбор шрифтов, цветов, общего оформления и
                                подготовка к печати
                            </x-ui.question-mark>
                            <x-ui.input.checkbox wire:model.live="needTextDesign"
                                                 id="needTextDesign" label=""/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <label for="needTextCheck">Нужна проверка правописания</label>
                            <x-ui.question-mark>Услуга проверки пунктуации и орфографии
                            </x-ui.question-mark>
                            <x-ui.input.checkbox wire:model.live="needTextCheck" id="needTextCheck"
                                                 label=""/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col p-4 border-b border-dark-300" x-data="{show:false}">
                <div @click="show = !show" class="flex justify-between cursor-pointer">
                    <h3 class="text-3xl font-medium">Обложка</h3>
                    <x-bi-chevron-down x-bind:class="show ? 'rotate-180' : ''"
                                       class="w-8 h-auto cursor-pointer transition"/>
                </div>

                <div x-show="show" x-cloak x-collapse.duration.400ms class="">
                    <div class="flex flex-col gap-4 pt-4">
                        <x-ui.input.text-area required
                                              description="Здесь можно указать комментарии к будущей обложке, а так же прикрепить любые файлы. Если обложка полностью готова, она должна быть подготовлена к печати."
                                              text-model="commentAuthorCover"
                                              files-model="coverFiles"
                                              :attachable="true"
                                              :sendable="false" :multiple="true"/>
                        <div class="flex gap-2 flex-wrap">
                            <p>Обложка полностью готова</p>
                            <x-ui.input.toggle :boolean="true" model="coverReady"
                                               :options="[false => 'Нужна помощь', true => 'Готовая']"/>
                        </div>
                    </div>

                </div>

            </div>

            <div class="flex flex-col p-4 border-b border-dark-300" x-data="{show: true}">
                <div class="flex justify-between cursor-pointer">
                    <div class="flex items-center">
                        <x-ui.input.checkbox wire:model.live="needPrint" id="needPrint" label=""/>
                        <label class="text-3xl font-medium" for="needPrint">Мне также необходимы
                            печатные экземпляры</label>
                    </div>
                    <x-bi-chevron-down x-show="needPrint" @click="show = !show"
                                       x-bind:class="show ? 'rotate-180' : ''"
                                       class="w-8 h-auto cursor-pointer transition"/>
                </div>

                <div x-show="needPrint && show"
                     x-cloak
                     x-collapse.duration.400ms>
                    <div class="flex flex-col gap-4 pt-4">

                        <div class="flex gap-4 flex-wrap items-center">
                            <p>Количество экземпляров</p>
                            <x-ui.input.range model="booksCnt"/>
                        </div>
                        <div class="flex gap-4 flex-wrap items-center">
                            <p>Стиль обложки</p>
                            <x-ui.input.toggle model="coverType"
                                               :options="['Мягкая' => 'мягкая', 'Твердая' => 'твердая']"/>
                        </div>
                        <div class="flex gap-4 flex-wrap items-center">
                            <p>Цветность блока</p>
                            <x-ui.input.toggle model="insideColor"
                                               :options="['Черно-белый' => 'черно-белый', 'Цветной' => 'цветной']"/>
                            <div class="flex gap-4" x-show="insideColor == 'Цветной'" x-transition>
                                <p>, цветных страниц: </p>
                                <input type="number" wire:model.live="pagesColor">
                            </div>
                        </div>
                        <div class="flex gap-4 md:flex-wrap">
                            <x-ui.input.text name="Имя" label="Фио получателя*"
                                             wire:model="receiverName"/>
                            <x-ui.input.text name="surname" label="Телефон получателя*"
                                             wire:model="receiverTelephone"/>
                        </div>
                        <livewire:components.account.address-choose/>
                    </div>
                </div>
            </div>

            <div class="flex flex-col p-4">
                <div class="flex items-center">
                    <x-ui.input.checkbox wire:model="needPromo" id="needPromo" label=""/>
                    <label class="text-3xl font-medium" for="needPromo">Мне необходимо продвижение
                        книги</label>
                </div>
                <div x-show="needPromo"
                     x-cloak
                     x-collapse.duration.400ms>
                    <div class="flex flex-col gap-4 pt-4 pl-8">
                        <div class="flex gap-2 items-center">
                            <input type="radio" wire:model.live="internalPromoType"
                                   name="internalPromoType"
                                   id="internal_promo_type_1" value="1">
                            <label for="internal_promo_type_1">Вариант 1</label>
                            <x-ui.question-mark>Книга окажется в слайдере на главной странице портала</x-ui.question-mark>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="radio" wire:model.live="internalPromoType"
                                   name="internalPromoType"
                                   id="internal_promo_type_2" value="2">
                            <label for="internal_promo_type_2">Вариант 2</label>
                            <x-ui.question-mark>Книга окажется на самом видном месте в большом индивидуальном блоке на главной странице сайта. Так же в нашей группе ВК будет опубликован отдельный пост о вашем издании.</x-ui.question-mark>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="flex flex-col w-[30%] max-w-[30%] items-center p-4 my-auto lg:w-full lg:max-w-full lg:justify-center">
            @if($pages > 0)
                <div class="flex flex-col mb-6 text-center">
                    <x-price-element price="{{$prices['priceInside']}}"
                                     label="Работа с макетом ({{$pages}} стр.)"/>
                    <span class="text-dark-200 italic text-xl font-light">Подготовка к публикации: 500</span>
                    <span class="text-dark-200 italic text-xl font-light">ISBN: 300</span>
                    <span x-show="needTextDesign" x-collapse.duration.400ms
                          class="text-dark-200 italic text-xl font-light">Включая дизайн текста: {{$prices['priceTextDesign']}}</span>
                    <span x-show="needTextCheck" x-collapse.duration.400ms
                          class="text-dark-200 italic text-xl font-light">Включай проверку правописания: {{$prices['priceTextCheck']}}</span>
                </div>

                <div x-show="needPrint" class="flex items-center gap-4" x-collapse.duration.400ms>
                    <x-price-element plus="true" class="pb-6" price="{{$prices['pricePrint']}}"
                                     label="Печать ({{$booksCnt}} экз.)"/>
                    @if($booksCnt <= 4)
                        <x-ui.question-mark>
                            Стоимость 1,2,3,4 экземпляров будет одинаковая, так как мы печатаем
                            книгу изначально на А3.
                        </x-ui.question-mark>
                    @endif
                </div>
                <div x-show="!coverReady" x-collapse.duration.400ms>
                    <x-price-element plus="true" class="pb-6" price="{{$prices['priceCover']}}"
                                     label="Создание обложки"/>
                </div>
                <div x-show="needPromo" x-collapse.duration.400ms>
                    <x-price-element plus="true" class="mb-6" price="{{$prices['pricePromo']}}"
                                     label="Продвижение"/>
                </div>

                <x-price-element price="{{$prices['priceTotal'] + $prices['pricePrint']}}"
                                 label="Итого" direction="row" color="green"/>
            @else
                <p class="font-black text-2xl text-center my-auto text-dark-200">
                    Загрузите работы и введите количествостраниц для начала расчета стоимости
                </p>
            @endif
        </div>
    </div>

        <div class="flex justify-between gap-4 flex-wrap">
            <x-ui.button>Отправить заявку</x-ui.button>
            <x-ui.link-simple class="italic text-xl" href="{{route('account.chat_create',['title' => 'Вопрос по заявке на издание книги'])}}">Получить помощь по заявке</x-ui.link-simple>
        </div>

</form>
