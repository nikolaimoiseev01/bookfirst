<div class="flex lg:flex-col">
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
            <input id="prints" type="number">
        </div>
        <div class="flex gap-4 flex-wrap">

            <div class="flex gap-4 items-center">
                <label for="needCheck">Мне нужна проверка</label>
                <x-ui.question-mark>
                    Услуги проверки пунктуации и орфографии
                </x-ui.question-mark>
                <x-ui.input.checkbox wire:model="needCheck" id="needCheck" label=""/>
            </div>

            <div class="flex gap-4 items-center">
                <label for="hasPromo">Мне нужна проверка</label>
                <x-ui.input.checkbox wire:model="hasPromo" id="hasPromo" label=""/>
            </div>
        </div>
    </div>
    <div class="flex flex-col items-center gap-8 w-1/2 lg:w-full mt-8 pl-4 mb-4 lg:pl-0 lg:my-4">
        <div class="flex gap-2 justify-evenly w-full items-center flex-wrap">
            <x-price-element price="3000" label="Участие"/>
            <span class="text-2xl text-dark-200">+</span>
            <x-price-element price="3000" label="Печать (42 экз.)"/>
            <span class="text-2xl text-dark-200">+</span>
            <x-price-element price="3000" label="Проверка"/>
        </div>
        <x-price-element price="3000" label="Итого" direction="row" color="green"/>
    </div>
</div>
