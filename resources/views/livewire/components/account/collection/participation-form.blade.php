<form x-data="{
            needPrint: $wire.entangle('needPrint'),
            needCheck: $wire.entangle('needCheck'),
            hasPromo: $wire.entangle('hasPromo'),
            showChosenAddress: @js('showChosenAddress')
        }"
      wire:submit="checkAndConfirm()" class="mb-16 max-w-6xl">

    <div class="flex container p-8 mb-8">

        <div class="flex flex-col flex-1 pr-8">
            <x-ui.input.text name="authorName" class="mb-4" label="Имя в сборнике*"
                             wire:model="authorName"/>
            <div class="flex flex-col gap-2 mb-4">
                <p class="text-xl">Произведения для участия*</p>
                <x-ui.work-choose :userWorks="$userWorks"
                                  :disabled="$collection['status']->order() > 1"/>
            </div>
            <div class="flex gap-4">
                <div class="flex gap-2 items-center">
                    <label for="needPrint">Необходимы печатные экземпляры</label>
                    <x-ui.question-mark>Электронный вариант доступен каждому участнику
                    </x-ui.question-mark>
                    <x-ui.input.checkbox wire:model.live="needPrint" id="needPrint" label=""/>
                </div>

                <div class="flex gap-2 items-center">
                    <label for="needCheck">Нужна проверка</label>
                    <x-ui.question-mark>Услуга проверки пунктуации и орфографии</x-ui.question-mark>
                    <x-ui.input.checkbox wire:model.live="needCheck" id="needCheck" label=""/>
                </div>
            </div>

            <div x-show="needPrint"
                 x-cloak
                 x-collapse.duration.800ms>
                <div class="flex flex-col pt-4 gap-4">
                    <div class="flex gap-4 flex-wrap items-center">
                        <p>Количество экземпляров</p>
                        <x-ui.input.range model="booksCnt"/>
                    </div>
                    <div class="flex gap-4">
                        <x-ui.input.text name="Имя" label="Фио получателя*"
                                         wire:model="receiverName"/>
                        <x-ui.input.text name="surname" label="Телефон получателя*"
                                         wire:model="receiverTelephone"/>
                    </div>
                    @if($showChosenAddress)
                        <div x-show="showChosenAddress" class="flex flex-col gap-2">
                            <p><b>Адрес
                                    получателя: </b>{{$participation->printOrder['address_json']['string']}}
                            </p>
                            <x-ui.link-simple @click="showChosenAddress = false">Изменить адрес
                            </x-ui.link-simple>
                        </div>
                    @endif
                    {{--                    <div x-show="!showChosenAddress">--}}
                    <livewire:components.account.address-choose/>
                    {{--                    </div>--}}
                </div>
            </div>
        </div>

        <div class="min-w-[20%] max-w-[25%] flex border-l border-dark-100 pl-8">
            @if(count($selectedWorks))
                <div class="flex flex-col items-center my-auto">
                    <x-price-element price="{{$prices['pricePart']}}"
                                     oldPrice="{{$promocode ? $prices['pricePart'] / (100 - $promocode['discount']) * 100 : null}}"
                                     label="Участие" class="mb-2"/>
                    <div class="" x-show="needPrint"
                         x-cloak
                         x-collapse.duration.800ms>
                        <div class="flex flex-col items-center py-2">
                            <span class="text-2xl text-dark-200">+</span>
                            <x-price-element price="{{$prices['pricePrint']}}"
                                             label="Печать ({{$booksCnt}} экз.)"/>
                        </div>
                    </div>
                    <div class="" x-show="needCheck"
                         x-cloak
                         x-collapse.duration.800ms>
                        <div class="flex flex-col items-center py-2">
                            <span class="text-2xl text-dark-200">+</span>
                            <x-price-element price="{{$prices['priceCheck']}}" label="Проверка"/>
                        </div>
                    </div>
                    <x-price-element price="{{$prices['priceTotal'] + $prices['pricePrint']}}"
                                     label="Итого"
                                     direction="column"
                                     class="mt-6 mb-4" color="green"/>
                    @if($promocode)
                        <p class="text-dark-200 text-lg italic text-center">Промокод
                            <b>{{$promocode['name']}}</b>
                            применен!
                            <br>Теперь в цене учитывается <b>скидка в {{$promocode['discount']}}
                                %</b></p>
                    @else
                        <div x-show="hasPromo" class="flex flex-col">
                            <x-ui.input.text name="Имя" class="!text-lg !py-0"
                                             placeholder="Промокод"
                                             wire:model="promocodeInput"/>
                            <div class="flex items-center justify-center gap-2">
                                <x-ui.tooltip-wrap wire:click="checkPromo()"
                                                   text="Проверить прокод">
                                    <x-bi-check class="w-8 h-auto fill-green-400 cursor-pointer"/>
                                </x-ui.tooltip-wrap>
                                <x-ui.tooltip-wrap @click="hasPromo=false" text="Назад">
                                    <x-bi-x class="w-8 h-auto fill-red-300 cursor-pointer"/>
                                </x-ui.tooltip-wrap>
                            </div>
                        </div>
                        <p x-show="!hasPromo" @click="hasPromo = true"
                           class="cursor-pointer text-green-500 text-xl italic">
                            У
                            меня есть промокод</p>
                    @endif
                </div>
            @else
                <p class="font-black text-2xl text-center my-auto text-dark-200">Добавьте
                    произведения, чтобы начать
                    считать стоимость</p>
            @endif
        </div>
    </div>

    <div class="flex">
        <x-ui.button>Отправить заявку</x-ui.button>
    </div>

</form>
