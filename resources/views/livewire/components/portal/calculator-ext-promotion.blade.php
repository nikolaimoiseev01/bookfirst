<div class="flex lg:flex-col">
    <div class="flex flex-col gap-4 w-1/2 lg:w-full mt-8 pr-4 mb-4 border-r lg:border-none border-dark-100 lg:pr-0 lg:my-4">
        <div class="flex gap-4 flex-wrap">
            <p>Выберите сайт для продвижения</p>
            <x-ui.dropdown
                wire:model.live="site"
                :options="$options"
            />
        </div>
        <div class="flex gap-4 flex-wrap items-center">
            <p>Количество дней продвижения</p>
            <x-ui.input.range model="days"/>
        </div>
        <div class="flex gap-4 items-center">
            <label for="hasPromo">У меня есть скидка в 20%</label>
            <x-ui.input.checkbox wire:model.live="hasPromo" id="hasPromo" label=""/>
        </div>
    </div>
    <div class="flex flex-col items-center justify-center gap-4 w-1/2 lg:w-full mt-8 pl-4 mb-4 lg:pl-0 lg:my-4">
            <x-price-element direction="row" :bigElement="true" color="green" price="{{$prices['priceTotal']}}" label="Итого"/>
            <p class="text-gray-300">За такое кол-во дней есть скидка: {{$prices['extDiscount']}}%</p>
    </div>
</div>
