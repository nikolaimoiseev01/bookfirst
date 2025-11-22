<div
    x-data="{
        needPrint: $wire.entangle('needPrint'),
        coverReady: $wire.entangle('coverReady'),
        insideColor: $wire.entangle('insideColor'),
        coverType: $wire.entangle('coverType'),
        needTextCheck: $wire.entangle('needTextCheck'),
        needTextDesign: $wire.entangle('needTextDesign'),
        needPromo: $wire.entangle('needPromo')
        }
    "
    class="flex lg:flex-col">
    <div
        class="flex flex-col gap-6 w-1/2 lg:w-full mt-8 pr-4 lg:pr-0 mb-4 border-r lg:border-none border-dark-100 lg:my-4">
        <div class="flex gap-4 flex-wrap items-center">
            <p>Страниц в моей книге</p>
            <input id="pages" wire:model.live="pages" type="number">
            <x-ui.question-mark>
                Минимальное количество страниц: 30
            </x-ui.question-mark>
        </div>
        <div class="flex flex-col">
            <div class="flex gap-4 flex-wrap items-center">
                <p>Работа с внутренним блоком</p>
                <x-ui.question-mark>
                    Макет можно считать готовым, если файл полностью подготовлен к общепринятым
                    правилам издания.
                    Никакая редактура не потребуется.
                </x-ui.question-mark>
            </div>
            <div class="flex gap-4 pt-4 flex-wrap">
                <div class="flex gap-2 items-center">
                    <label for="needTextDesign">Дизайн текста</label>
                    <x-ui.input.checkbox wire:model.live="needTextDesign" id="needTextDesign"
                                         label=""/>
                    <x-ui.question-mark>
                        Подбор шрифтов, цветов, общего оформления и подготовка к печати
                    </x-ui.question-mark>
                </div>
                <div class="flex gap-2 items-center">
                    <label for="needTextCheck">Проверка правописания</label>
                    <x-ui.input.checkbox wire:model.live="needTextCheck" id="needTextCheck"
                                         label=""/>
                    <x-ui.question-mark>
                        Услуги проверки пунктуации и орфографии
                    </x-ui.question-mark>
                </div>
            </div>
        </div>
        <div class="flex gap-4 flex-wrap items-center">
            <p>Обложка полностью готова?</p>
            <x-ui.question-mark>
                Обложка считается готовой, если подходит под все параметры профессиональной печати
            </x-ui.question-mark>
            <x-ui.input.toggle model="coverReady" boolean="true"
                               :options="[true => 'Да', false => 'Нет']"/>
        </div>

        <div class="flex flex-col">
            <div class="flex gap-2 items-center">
                <label for="needPrint">Мне также необходимы печатные экземпляры</label>
                <x-ui.input.checkbox wire:model.live="needPrint" id="needPrint" label=""/>
            </div>

            <div x-show="needPrint"
                 x-cloak
                 x-collapse.duration.800ms>
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
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="flex gap-2 items-center">
                <label for="needPromo">Мне необходимо продвижение книги</label>
                <x-ui.input.checkbox wire:model.live="needPromo" id="needPromo" label=""/>
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
                    </div>
                    <div class="flex gap-2 items-center">
                        <input type="radio" wire:model.live="internalPromoType"
                               name="internalPromoType"
                               id="internal_promo_type_2" value="2">
                        <label for="internal_promo_type_2">Вариант 2</label>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="flex flex-col w-1/2 lg:w-full mt-8 pl-4 lg:pl-0 mb-4 items-center">
        <div class="flex flex-col mb-6 text-center">
            <x-price-element price="{{$prices['priceInside']}}"
                             label="Работа с макетом ({{$pages}} стр.)"/>
            <span class="text-dark-200 italic text-xl font-light">Включая базовую редактуру и присвоение ISBN</span>
            <span x-show="needTextDesign" x-collapse.duration.800ms
                  class="text-dark-200 italic text-xl font-light">Включая дизайн текста: {{$prices['priceTextDesign']}}</span>
            <span x-show="needTextCheck" x-collapse.duration.800ms
                  class="text-dark-200 italic text-xl font-light">Включай проверку правописания: {{$prices['priceTextCheck']}}</span>
        </div>

        <div x-show="needPrint" x-collapse.duration.800ms>
            <x-price-element plus="true" class="pb-6" price="{{$prices['pricePrint']}}"
                             label="Печать ({{$booksCnt}} экз.{{$pagesColor > 0 ? ', ' . $pagesColor . ' цв. стр.' : ''}})"/>
        </div>
        <div x-show="!coverReady" x-collapse.duration.800ms>
            <x-price-element plus="true" class="pb-6" price="{{$prices['priceCover']}}"
                             label="Создание обложки"/>
        </div>
        <div  x-show="needPromo" x-collapse.duration.800ms>
            <x-price-element plus="true" class="pb-6" price="{{$prices['pricePromo']}}"
                             label="Продвижение"/>
        </div>

        <x-price-element price="{{$prices['priceTotal'] + $prices['pricePrint']}}" label="Итого" direction="row" color="green"/>
    </div>
</div>
