<div>
    @section('title')
        Новая заявка на печать.
        <x-ui.link-simple class="text-4xl font-semibold inline-block"
                          href="{{$model->portalPage()}}" :isLivewire="false"
                          target="_blank">{{$model['title']}}
        </x-ui.link-simple>
    @endsection
    <form x-data="{
            showChosenAddress: @js($showChosenAddress),
            insideType: $wire.entangle('insideType'),
            insideColor: $wire.entangle('insideColor'),
            coverType: $wire.entangle('coverType')
        }"


          wire:submit="checkAndConfirm()" class="mb-16 max-w-6xl">

        <div class="flex container p-8 mb-8 lg:flex-col lg:gap-4">

            <div class="flex flex-col flex-1 pr-8 lg:pr-0">
                <div class="flex flex-col gap-4">
                    <div class="flex gap-4 flex-wrap items-center">
                        <p>Количество экземпляров</p>
                        <x-ui.input.range model="booksCnt"/>
                    </div>
                    @if($type == 'OwnBook')
                        <div class="flex gap-4 flex-wrap items-center">
                            <p>Стиль обложки</p>
                            <x-ui.input.toggle model="coverType"
                                               :options="['Мягкая' => 'мягкая', 'Твердая' => 'твердая']"/>
                        </div>
                    @endif
                    <div class="flex gap-4 md:flex-col">
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
                    <div x-show="!showChosenAddress">
                        <livewire:components.account.address-choose/>
                    </div>
                </div>
            </div>

            <div
                class="min-w-[20%] max-w-[25%] flex border-l border-dark-100 pl-8 lg:w-full lg:max-w-full lg:justify-center lg:text-center lg:border-l-0 lg:border-t lg:py-4 lg:pl-0">
                <div class="flex flex-col items-center my-auto">
                    <div class="flex flex-col items-center py-2">
                        <x-price-element price="{{$pricePrint}}"
                                         label="Печать ({{$booksCnt}} экз.)"/>
                        @if($pages > 0)
                            <p class="text-dark-350 text-base italic">Страниц: {{$pages}}
                                @if($pagesColor > 0)
                                    (цветных: {{$pagesColor}})
                                @endif
                            </p>
                        @endif
                        @if($type == 'Collection')
                            <p class="text-dark-350 text-base italic">Обложка: мягкая</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between gap-4 flex-wrap">
            <x-ui.button>Отправить заявку</x-ui.button>
            <x-ui.link-simple class="italic text-xl"
                              href="{{route('account.chat_create',['title' => 'Вопрос по заявке на печать'])}}">
                Получить помощь по заявке
            </x-ui.link-simple>
        </div>

    </form>

</div>
