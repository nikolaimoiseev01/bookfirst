<form wire:submit="checkAndConfirm()"
      x-data="{
            hasPromo: $wire.entangle('hasPromo'),
            rulesAgreed: $wire.entangle('rulesAgreed')
        }"
    class="max-w-4xl"
>
    @section('title')
        Новая заявка на продвижение
    @endsection
    <div class="flex container p-8 mb-8 md:flex-col"
    >

        <div class="flex flex-col flex-1 pr-8">
            <div class="flex gap-4 mb-4 flex-wrap">
                <p>Выберите сайт для продвижения</p>
                <x-ui.dropdown
                    wire:model.live="site"
                    :options="$options"
                />
            </div>
            <div class="flex gap-4 mb-4">
                <p>Ознакомлен и согласен с <x-ui.link-simple class="inline-flex">правилами продвижения</x-ui.link-simple></p>
                <x-ui.input.checkbox wire:model="rulesAgreed"/>
            </div>
            <div x-show="rulesAgreed"  x-cloak x-collapse.duration.400ms>
                <div  class="flex gap-4 mb-4 md:flex-col">
                    <x-ui.input.text name="login" class="" label="Логин от сайта {{$site}}*" wire:model="login"/>
                    <x-ui.input.text name="password" class="" label="Пароль  от сайта {{$site}}*" wire:model="password"/>
                </div>
            </div>

            <div class="flex gap-4 flex-wrap items-center">
                <p>Количество дней продвижения</p>
                <x-ui.input.range model="days"/>
            </div>
        </div>

        <div
            class="min-w-[20%] max-w-[25%] flex border-l md:border-l-0 md:max-w-full md:justify-center md:pt-8 border-dark-100 pl-8">
            <div class="flex flex-col items-center justify-center my-auto">
                <x-price-element
                    price="{{$prices['priceTotal']}}"
                    label="Итого"
                    oldPrice="{{$promocode ? $prices['priceTotal'] / (100 - $promocode['discount']) * 100 : null}}"
                    direction="column"
                    class="" color="green"/>
                @if($promocode)
                    <p class="text-dark-200 text-lg italic text-center">
                        Промокод
                        <b>{{$promocode['name']}}</b>
                        применен!
                        <br>Теперь в цене учитывается <b>скидка
                            в {{$promocode['discount']}}
                            %</b></p>
                @else
                    <div x-show="hasPromo"
                         class="flex flex-col">
                        <x-ui.input.text name="Имя"
                                         class="!text-lg !py-0"
                                         placeholder="Промокод"
                                         wire:model="promocodeInput"/>
                        <div
                            class="flex items-center justify-center gap-2">
                            <x-ui.tooltip-wrap
                                wire:click="checkPromo()"
                                text="Проверить прокод">
                                <x-bi-check
                                    class="w-8 h-auto fill-green-400 cursor-pointer"/>
                            </x-ui.tooltip-wrap>
                            <x-ui.tooltip-wrap
                                @click="hasPromo=false"
                                text="Назад">
                                <x-bi-x
                                    class="w-8 h-auto fill-red-300 cursor-pointer"/>
                            </x-ui.tooltip-wrap>
                        </div>
                    </div>
                    <p x-show="!hasPromo"
                       @click="hasPromo = true"
                       class="cursor-pointer text-green-500 text-xl italic">
                        У
                        меня есть промокод</p>
                @endif
            </div>
        </div>
    </div>

    <div class="flex">
        <x-ui.button>Отправить заявку</x-ui.button>
    </div>
</form>
