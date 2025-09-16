<div
    x-data="{ insideReady: $wire.entangle('insideReady'), needPrint: $wire.entangle('needPrint')
    ,coverReady: $wire.entangle('coverReady'), insideColor: $wire.entangle('insideColor')
    ,needTextCheck: $wire.entangle('needTextCheck'), needTextDesign: $wire.entangle('needTextDesign')}"
    class="flex lg:flex-col">
    <div
        class="flex flex-col gap-6 w-1/2 lg:w-full mt-8 pr-4 lg:pr-0 mb-4 border-r lg:border-none border-dark-100 lg:my-4">
        <div class="flex gap-4 flex-wrap items-center">
            <p>Страниц в моей книге</p>
            <input id="prints" type="number">
            <x-ui.question-mark>
                Минимальное количество страниц: 20
            </x-ui.question-mark>
        </div>
        <div class="flex flex-col">
            <div class="flex gap-4 flex-wrap items-center">
                <p>Макет полностью готов?</p>
                <x-ui.question-mark>
                    Макет можно считать готовым, если файл полностью подготовлен к общепринятым правилам издания. Никакая редактура не потребуется.
                </x-ui.question-mark>
                <x-ui.input.toggle boolean="true" model="insideReady" :options="[true => 'Да', false => 'Нет']"/>
            </div>
            <div x-show="!insideReady"
                 x-cloak
                 x-collapse.duration.800ms>
                <div class="flex gap-4 pt-4 flex-wrap">
                    <div class="flex gap-2 items-center">
                        <label for="needTextDesign">Дизайн текста</label>
                        <x-ui.input.checkbox wire:model="needTextDesign" id="needTextDesign" label=""/>
                        <x-ui.question-mark>
                            Подбор шрифтов, цветов, общего оформления и подготовка к печати
                        </x-ui.question-mark>
                    </div>
                    <div class="flex gap-2 items-center">
                        <label for="needTextCheck">Проверка правописания</label>
                        <x-ui.input.checkbox wire:model="needTextCheck" id="needTextCheck" label=""/>
                        <x-ui.question-mark>
                            Услуги проверки пунктуации и орфографии
                        </x-ui.question-mark>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex gap-4 flex-wrap items-center">
            <p>Обложка полностью готова?</p>
            <x-ui.question-mark>
                Обложка считается готовой, если подходит под все параметры профессиональной печати
            </x-ui.question-mark>
            <x-ui.input.toggle model="coverReady" boolean="true" :options="[true => 'Да', false => 'Нет']"/>
        </div>

        <div class="flex flex-col">
            <div class="flex gap-2 items-center">
                <label for="needPrint">Мне также необходимы печатные экземпляры</label>
                <x-ui.input.checkbox wire:model="needPrint" id="needPrint" label=""/>
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
                        <x-ui.input.toggle model="coverType" :options="['Мягкая' => 'мягкая', 'Твердая' => 'твердая']"/>
                    </div>
                    <div class="flex gap-4 flex-wrap items-center">
                        <p>Цветность блока</p>
                        <x-ui.input.toggle model="insideColor"
                                           :options="['Черно-белый' => 'черно-белый', 'Цветной' => 'цветной']"/>
                        <div class="flex gap-4" x-show="insideColor == 'Цветной'" x-transition>
                            <p>, цветных страниц: </p>
                            <input type="number" wire:model="colorPages">
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="flex flex-col w-1/2 lg:w-full mt-8 pl-4 lg:pl-0 mb-4 items-center">
        <div class="flex flex-col mb-6 text-center">
            <x-price-element price="4600" label="Работа с макетом"/>
            <span x-show="needTextDesign && !insideReady" x-collapse.duration.800ms class="text-dark-200 italic text-xl font-light">Включая дизайн текста: 1300</span>
            <span x-show="needTextCheck && !insideReady" x-collapse.duration.800ms class="text-dark-200 italic text-xl font-light">Включай проверку правописания: 3000</span>
        </div>

        <div x-show="needPrint" x-collapse.duration.800ms>
            <x-price-element plus="true" class="pb-6" price="12469" label="Печать (50 экз.)"/>
        </div>
        <div x-show="!coverReady" x-collapse.duration.800ms>
            <x-price-element plus="true" class="pb-6" price="1500" label="Создание обложки"/>
        </div>
        <x-price-element plus="true" class="mb-6" price="500" label="Продвижение"/>
        <x-price-element price="3000" label="Итого" direction="row" color="green"/>
    </div>
</div>
