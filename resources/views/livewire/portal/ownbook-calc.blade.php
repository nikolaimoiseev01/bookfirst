<div x-data class="portal_calc_wrap">

    <div class="calc-inputs">
        <div class="calc-row">
            <p>Страниц в моей книге</p>
{{--            <x-input.range-slider model="pages"/>--}}
            <input wire:model="pages" min="30" class="number-input" type="number">
            <x-question-mark>
                Минмальное количество страниц: 30.
            </x-question-mark>
        </div>

        <div class="inside_options_block_wrap">

            <div class="calc-row">
                <p>Макет полностью готов?</p>

                <x-question-mark>
                    Макет можно считать готовым, если файл полностью подготовлен к общепринятым правилам издания.
                    Никакая редактура не потребуется.
                </x-question-mark>

                <div class="switch-wrap">
                    <input wire:model="inside_ready" checked type="radio" id="inside_ready_1" value="1"
                           name="inside_ready">
                    <label for="inside_ready_1">Да</label>

                    <input wire:model="inside_ready" type="radio" value="0" id="inside_ready_0" name="inside_ready">
                    <label for="inside_ready_0">Нет</label>
                </div>
            </div>

            <div x-cloak
                 x-show="$wire.inside_ready == '0'"
                 x-transition.opacity.duration.500ms
                 class="inside_works_wrap">
                <div class="calc-row">
                    <div class="check-block">
                        <label for="need_design"><p>Дизайн текста</p></label>
                        <x-question-mark>
                            Подбор шрифтов, цветов, общего оформления и подготовка к печати
                        </x-question-mark>
                        <input wire:model="need_design" id="need_design" type="checkbox">
                    </div>
                    <div class="check-block">
                        <label for="need_check"><p>Проверка правописания</p></label>
                        <x-question-mark>
                            Услуга проверки пунктуации и орфографии
                        </x-question-mark>
                        <input wire:model="need_check" id="need_check" type="checkbox">
                    </div>
                </div>
            </div>
        </div>

        <div class="calc-row">
            <p>Облока полностью готова?</p>

            <x-question-mark>Обложка считается готовой, если подходит под все параметры профессиональной печати
            </x-question-mark>

            <div class="switch-wrap">
                <input wire:model="cover_ready" checked type="radio" id="cover_ready_1" value="1"
                       name="cover_ready">
                <label for="cover_ready_1">Да</label>

                <input wire:model="cover_ready" type="radio" value="0" id="cover_ready_0" name="cover_ready">
                <label for="cover_ready_0">Нет</label>
            </div>
        </div>

        <div class="print_info_block_wrap">


            <div class="calc-row">
                <div class="check-block">
                    <label for="print_need"><p>Мне также необходимы печатные экземпляры</p></label>
                    <input wire:model="need_print" id="print_need" type="checkbox">
                </div>
            </div>
            <div x-cloak
                 x-show="$wire.need_print"
                 x-transition.opacity.duration.500ms
                 class="print_info_wrap">
                <div class="calc-row">
                    <p>Количество экземпляров:</p>
                    <x-input.range-slider model="prints"/>
                </div>
                <div class="calc-row">
                    <p>Стиль обложки</p>

                    <div class="switch-wrap">
                        <input wire:model="cover_type" checked type="radio" id="cover_type_soft" value="soft"
                               name="cover_type">
                        <label for="cover_type_soft">мягкая</label>

                        <input wire:model="cover_type" type="radio" value="hard" id="cover_type_hard" name="cover_type">
                        <label for="cover_type_hard">твердая</label>
                    </div>
                </div>
                <div class="calc-row">
                    <p>Цветность блока</p>

                    <div class="switch-wrap">
                        <input wire:model="inside_color" type="radio" value="0" id="inside_color_0" name="inside_color">
                        <label for="inside_color_0">черно-белый</label>

                        <input wire:model="inside_color" checked type="radio" id="inside_color_1" value="1"
                               name="inside_color">
                        <label for="inside_color_1">цветной</label>
                    </div>
                    <p x-cloak x-show="$wire.inside_color == '1'">, цветных страниц</p>
                    <input x-cloak x-show="$wire.inside_color == '1'" wire:model="pages_color" min="1"
                           class="color_pages_input number-input" type="number">
                </div>
            </div>
        </div>

        <div class="inside_options_block_wrap">
            <div class="calc-row">
                <div class="check-block">
                    <label for="need_promo"><p>Мне необходимо продвижение книги</p></label>
                    <input wire:model="need_promo" id="need_promo" type="checkbox">
                </div>
            </div>
            <div x-cloak
                 x-show="$wire.need_promo"
                 x-transition.opacity.duration.500ms
                 class="calc-row promo_var_wrap">
                <div class="check-block">
                    <label for="promo_type_1"><p>Вариант 1</p></label>
                    <input wire:model="promo_type" checked="" value="1" name="promo_type" id="promo_type_1"
                           type="radio">
                    <x-question-mark>
                        Разместить в блоке 'Наши авторы'
                    </x-question-mark>
                </div>

                <div class="check-block">
                    <label for="promo_type_2"><p>Вариант 2</p></label>
                    <input wire:model="promo_type" checked="" value="2" name="promo_type" id="promo_type_2"
                           type="radio">
                    <x-question-mark>
                        Бессрочное размещение на всех страницах сайта и соц. сетях.
                    </x-question-mark>
                </div>
            </div>
        </div>

    </div>

    <div class="calc-outputs">

        <div class="price_inside_wrap participation-price">
            <div id="part_price" class="price-number">
                {{number_format($price_inside, 0, '', ' ')}}
            </div>
            <p class="price-desc">Работа с макетом ({{$pages}} стр.)</p>
            <div class="more_info_wrap">
                <p x-cloak
                   x-show="$wire.need_design"
                   x-transition.opacity.duration.500ms
                   class="price-desc out_design_wrap">Включая дизайн текста: {{$price_design}}</p>
                <p x-cloak
                   x-show="$wire.need_check"
                   x-transition.opacity.duration.500ms
                   class="price-desc out_check_wrap">Включая проверку правописания: {{$price_check}}</p>
            </div>
        </div>
        <div x-cloak
             x-show="$wire.price_print > 0"
             x-transition.opacity.duration.500ms
             class="participation-price out_print_wrap">
            <div id="print_price" class="price-number">
                <p class="participation-price-plus price-desc">+</p>
                {{number_format($price_print, 0, '', ' ')}}
                @if($prints <= 4)
                    <x-question-mark>
                        Стоимость 1,2,3,4 экземпляров будет одинаковая, так как мы печатаем книгу изначально на листе А3.
                    </x-question-mark>
                @endif
            </div>
            <p class="price-desc">Печать ({{$prints}} экз.)</p>
            @if($prints > 1)
                <p class="price-desc">{{ceil($price_print / $prints)}}/шт.</p>
            @endif
        </div>


        <div x-cloak
             x-show="$wire.cover_ready === '0'"
             x-transition.opacity.duration.500ms
             class="participation-price out_cover_wrap">
            <div id="print_price" class="price-number">
                <p class="participation-price-plus price-desc">+</p>
                {{number_format($price_cover, 0, '', ' ')}}
            </div>
            <p class="price-desc">Создание обложки</p>
        </div>


        <div x-cloak
             x-show="$wire.need_promo"
             x-transition.opacity.duration.500ms
             class="participation-price out_promo_wrap">
            <div id="print_price" class="price-number">
                <p class="participation-price-plus price-desc">+</p>
                {{number_format($price_promo, 0, '', ' ')}}
            </div>
            <p class="price-desc">Продвижение</p>
        </div>

        <div class="price-total">
            <p class="price-desc">Итого:&nbsp;</p>
            <div id="total_price" class="price-number">{{number_format($price_total, 0, '', ' ') }}</div>
            <p class="price-desc rub">&nbsp;руб.</p>
        </div>
    </div>
</div>




@push('page-js')
    <script>

        $('input[name="inside_ready"]').change(function () {
            $(".inside_works_wrap").slideToggle(500);
        })

        $("#need_design").change(function () {
            $(".out_design_wrap").slideToggle(500);
        });

        $("#need_check").change(function () {
            $(".out_check_wrap").slideToggle(500);
        });


        $('input[name="cover_ready"]').change(function () {
            $(".out_cover_wrap").slideToggle(500);
        })


        $("#print_need").change(function () {
            $(".print_info_wrap").slideToggle(500);
            $(".out_print_wrap").slideToggle(500);
        });


        $("#need_promo").change(function () {
            $(".promo_var_wrap").slideToggle(500);
            $(".out_promo_wrap").slideToggle(500);
        });
    </script>
@endpush

