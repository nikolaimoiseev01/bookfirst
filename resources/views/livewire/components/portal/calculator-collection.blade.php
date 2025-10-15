<div x-data="{needCheck: @entangle('needCheck')}" class="flex lg:flex-col">
    <div class="flex flex-col gap-4 w-1/2 lg:w-full mt-8 pr-4 mb-4 border-r lg:border-none border-dark-100 lg:pr-0 lg:my-4">
        <div class="flex gap-4 flex-wrap">
            <p>Выберите количество страниц</p>
            <x-ui.dropdown
                wire:model.live="pages"
                :options="$options"
            />
        </div>
        <div class="flex gap-4 flex-wrap">
            <p>Мне также необходимо печатных экземпляров</p>
            <input wire:model.live="booksCnt" id="needPrint" type="number">
        </div>
        <div class="flex gap-4 flex-wrap">

            <div class="flex gap-4 items-center">
                <label for="needCheck">Мне нужна проверка</label>
                <x-ui.question-mark>
                    Услуги проверки пунктуации и орфографии
                </x-ui.question-mark>
                <x-ui.input.checkbox wire:model.live="needCheck" id="needCheck" label=""/>
            </div>

            <div class="flex gap-4 items-center">
                <label for="hasPromo">У меня есть скидка в 20%</label>
                <x-ui.input.checkbox wire:model.live="hasPromo" id="hasPromo" label=""/>
            </div>
        </div>
    </div>
    <div class="flex flex-col items-center w-1/2 lg:w-full mt-4 mb-2 pl-4 gap-2 lg:pl-0 lg:my-4">
        <div class="flex gap-8 justify-center w-full items-center flex-wrap">
            <x-price-element price="{{$prices['pricePart']}}" label="Участие"/>
            <span class="text-2xl text-dark-200">+</span>
            <x-price-element price="{{$prices['pricePrint']}}" label="Печать ({{$booksCnt}} экз.)"/>
            <div class="flex items-center gap-8" x-show="needCheck" x-transition>
                <span class="text-2xl text-dark-200">+</span>
                <x-price-element price="{{$prices['priceCheck']}}" label="Проверка"/>
            </div>
        </div>
        <x-price-element price="{{$prices['priceTotal'] + $prices['pricePrint']}}" label="Итого" color="green"/>
    </div>
</div>
