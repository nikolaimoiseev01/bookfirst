<div x-data class="ext_promotion_application_wrap create_participation_wrap">

    <form
        wire:submit.prevent="save_application(Object.fromEntries(new FormData($event.target)))"
        enctype="multipart/form-data">
        <div class="create-participation-form container">


            @csrf
            <div class="participation-inputs">

                <div style="margin-bottom: 0;" class="participation-inputs-row">
                    <div class="input-group">
                        <p>Логин от сайта*</p>
                        <input class="@if(in_array('login', $error_fields) && !$login) danger @endif"
                               wire:model.self="login"
                               type="text">
                    </div>
                    <div class="input-group">
                        <p>Пароль от сайта*</p>
                        <input class="@if(in_array('password', $error_fields) && !$password) danger @endif"
                               wire:model="password" type="text">
                    </div>

                    <div class="input-group">
                        <p>Сайт для продвижения</p>
                        <select wire:model="site" id="site" name="site">
                            <option value="stihi">stihi</option>
                            <option value="proza">proza</option>
                            <option value="chitalnya">chitalnya</option>
                            <option value="poembook">poembook</option>
                        </select>
                    </div>

                </div>

                <div class="participation-inputs-row">
                        <p>Количество дней продвижения:</p>
                        <x-input.range-slider model="days"/>
                </div>
            </div>

            <div class="participation-outputs">
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
                    Отправить заявку
                </button>
                <p style="font-size: 20px; color: #bdbdbd"><i>* - обязательны для заполнения</i></p>
            </div>
            <a href="{{route('help_collection')}}" target="_blank" style="font-size: 20px;" class="link"><i>Нужна
                    помощь</i></a>
        </div>

    </form>

</div>

@push('page-js')


@endpush

