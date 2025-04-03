<div x-data class="create_participation_wrap">

    <form
        wire:submit.prevent="confirm_step_1(Object.fromEntries(new FormData($event.target)))"
        enctype="multipart/form-data">
        <div class="create-participation-form container">


            @csrf
            <div class="participation-inputs">

                <div style="margin-bottom: 0;" class="participation-inputs-row">
                    <div class="input-group">
                        <p>Имя*</p>
                        <input class="@if(in_array('name', $error_fields) && !$name) danger @endif"
                               wire:model.self="name"
                               type="text">
                    </div>
                    <div class="input-group">
                        <p>Фамилия*</p>
                        <input class="@if(in_array('surname', $error_fields) && !$surname) danger @endif"
                               wire:model="surname" type="text">
                    </div>

                    <div class="input-group">
                        <p>Псевдоним</p>
                        <input wire:model="nickname" type="text">
                    </div>
                </div>
                <p class="alert">
                    <i>Псевдоним заменяет имя в сборнике! Индивидуальные условия обсуждаются отдельно.</i>
                </p>

                <div style="flex-flow: column;" class="participation-inputs-row">
                    <p>Произведения для участия*</p>
                    <div class="add_work_wrap @if(in_array('works', $error_fields) && !$works) danger @endif">
                        @livewire('components.work-choose', ['works_already_in'=>$works_already_in])
                    </div>
                </div>

                <div class="participation-inputs-row">
                    <div class="checkbox-group">
                        <label for="print_need"><p>Необходимы печатные экземпляры</p></label>
                        <x-question-mark>
                            Электронный вариант доступен каждому участнику
                        </x-question-mark>
                        <input wire:model="print_need" id="print_need" type="checkbox">
                    </div>

                    <div class="checkbox-group">
                        <label for="need_check"><p>Нужна проверка</p></label>
                        <x-question-mark>
                            Услуга проверки пунктуации и орфографии
                        </x-question-mark>
                        <input wire:model="need_check" id="need_check" type="checkbox">
                    </div>
                </div>


                <div x-cloak
                     x-show="$wire.print_need"
                     x-transition.opacity.duration.500ms
                     class="print_info_block">
                    <div class="print_info_wrap">
                        <div class="participation-inputs-row">
                            <p>Количество экземпляров:</p>
                            <x-input.range-slider model="prints"/>
                        </div>
                        <div class="participation-inputs-row">
                            <div class="input-group">
                                <p>ФИО получателя*</p>
                                <input
                                    class="@if(in_array('send_to_name', $error_fields) && !$send_to_name) danger @endif"
                                    wire:model="send_to_name" type="text">
                            </div>
                            <div style="margin-bottom: 0;" class="input-group">
                                <p>Телефон*</p>
                                <input
                                    placeholder="8 (123) 456 78 99"
                                    class="@if(in_array('send_to_tel', $error_fields) && !$send_to_tel) danger @endif mobile_input"
                                    wire:model="send_to_tel" type="text">
                            </div>
                        </div>

                        <div wire:ignore class="participation-inputs-row">
{{--                            <x-choose-order-address :address="$address_default_string"></x-choose-order-address>--}}
                            <x-choose-cdek-address/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="participation-outputs">
                <h2 class="title">Стоимость, ₽</h2>
                <div class="participation-price">
                    <div class="number">
                        @if(($promocode ?? null) && ($price_part > 00))
                            <p class="old_price">{{$price_part / (100 - $promocode['discount']) * 100}}</p>
                        @endif
                        <h1 id="participation_price">{{$price_part}}</h1>
                    </div>
                    <div class="desc">
                        <p>За участие ({{intval($pages)}} стр.)</p>
                        <span class="question-mark tooltip"
                              title="Cтраницы считаются на основе единого оформления. Каждая страница сверх 28-ми оплачивается в размере 300 руб./стр.">
                                   <svg id="question-circle"
                                        viewBox="0 0 40.12 40.12">
                                        <path
                                            d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                        <path d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                        <path
                                            d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                    </svg>
                            </span>

                    </div>
                </div>


                <div
                    x-cloak
                    x-show="$wire.print_need"
                    x-transition.opacity.duration.500ms
                    id="print-price" class="participation-price">
                    <div style="display: flex;">
                        <h1>{{$price_print}}</h1>
                    </div>
                    <div class="desc"><p>За печать ({{$prints}} экз.)</p></div>
                </div>

                <div
                    x-cloak
                    x-show="$wire.need_check"
                    x-transition.opacity.duration.500ms
                    id="check-price" class="participation-price">
                    <div style="display: flex;">
                        <h1>{{$price_check ?? 0}}</h1>
                    </div>
                    <div class="desc">
                        <p>Проверка</p>
                    </div>
                </div>


                <div class="total_price_wrap participation-price">

                    <div class="number">
                        <h1 id="total_price">{{$price_total}} руб.</h1>
                    </div>
                    <p class="price-desc">Итого</p>
                </div>

                @if($promocode === null)
                    <a x-cloak
                       x-show="!$wire.show_promo_input"
                       @click="$wire.set('show_promo_input', true)"
                       class="link">У меня есть промокод</a>
                @endif

                @if($promocode ?? null)
                    <p style="text-align: center">
                        <i>Cкидка в {{$promocode['discount']}}% учтена.</i>
                    </p>
                @endif

                <div x-show="$wire.show_promo_input" class="promo_input_wrap">
                    <input wire:model="promocode_input"
                           placeholder="Промокод..."
                           type="text">
                    <span wire:click.prevent="check_promo" title="Проверить"
                          class="tooltip material-symbols-outlined done">done</span>
                    <span @click="$wire.set('show_promo_input', false)" title="Закрыть"
                          class="tooltip material-symbols-outlined close">close</span>


                </div>

            </div>


        </div>

        <div class="buttons_wrap">
            <div>
                <button type="submit" class="button show_preloader_on_click">
                    @if($app_type === 'create')
                        Отправить заявку
                    @elseif($app_type === 'edit')
                        Сохранить
                    @endif
                </button>
                <p style="font-size: 20px; color: #bdbdbd"><i>* - обязательны для заполнения</i></p>
            </div>
            <a href="{{route('help_collection')}}" target="_blank" style="font-size: 20px;" class="link"><i>Нужна
                    помощь</i></a>
        </div>

    </form>
</div>

@push('page-js')

    <script>
        {{Session(['back_after_add' => \Livewire\str(Request::url())])}}

        $("#print_need").change(function () {
            $(".print_info_block").slideToggle(500);
            $("#print-price").slideToggle(500);
            $("#delivery-price").slideToggle(500);
        });

        $("#need_check").change(function () {
            $("#check-price").slideToggle(500);
        });

    </script>


    <script>

        document.addEventListener('livewire:load', function () {
            var timeOnPage = 0;
            setInterval(function () {
                timeOnPage += 1; // увеличиваем время на странице каждую секунду
                if (timeOnPage === 30) { // если пользователь находится на странице больше минуты (60 секунд)
                    window.livewire.emit('new_almost_complete_action')
                }
            }, 1000); // вызываем каждую секунду

        })
    </script>

@endpush

