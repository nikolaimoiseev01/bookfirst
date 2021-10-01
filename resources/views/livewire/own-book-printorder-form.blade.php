<div>
    <style>
        .participation-inputs-row {
            margin-bottom: 10px !important;
        }
    </style>
    {{----------- БЛОК ПЕЧАТИ -----------}}
    <div id='print_block' style="padding-bottom: 20px; border-bottom: none;" class="ob-applic-block">
        <div wire:ignore style="margin-top: 10px; display: block" class="prints-needed ptint-block">
            <div class="ob-applic-block-top" style="display: flex;">
                <div>
                    <div style="margin-bottom: 10px;">
                        <p style="margin-bottom: 10px;">Итоговое количество страниц: {{$own_book['pages']}} ({{$own_book['color_pages'] ?? 0}} цветных)</p>
                        <p>Стиль обложки:</p>
                        <div style="margin-left: 10px;" class="switch-wrap">
                            <input @if($own_book->printorder['cover_type'] ?? 'soft' === 'soft') checked @endif type="radio"
                                   value="cover_style_soft" id="cover_style_soft"
                                   name="cover_style">
                            <label for="cover_style_soft">
                                мягкая
                            </label>

                            <input @if($own_book->printorder['cover_type'] ?? 0 === 'hard') checked @endif type="radio"
                                   value="cover_style_hard" id="cover_style_hard"
                                   name="cover_style">
                            <label for="cover_style_hard">
                                твердая
                            </label>
                        </div>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <p>Цветность обложки:</p>
                        <div style="margin-left: 10px;" class="switch-wrap">
                            <input @if(intval($own_book->printorder['cover_color'] ?? 1) === 1) checked @endif type="radio"
                                   value="cover_color_yes" id="cover_color_yes"
                                   name="cover_color">
                            <label for="cover_color_yes">
                                цветная
                            </label>
                            <input @if(intval($own_book->printorder['cover_color'] ?? 'no_print') === 0) checked @endif type="radio"
                                   value="cover_color_no" id="cover_color_no"
                                   name="cover_color">
                            <label for="cover_color_no">
                                черно-белая
                            </label>
                        </div>
                    </div>

                    <div style="margin-top: 7px; margin-bottom: 10px;">
                        <p>Цветность блока:</p>
                        <div style="margin-left: 10px;" class="switch-wrap">
                            <input @if(intval($own_book->printorder['color_pages'] ?? 0) === 0) checked @endif type="radio"
                                   class="show-hide" value="inside_color_no"
                                   id="inside_color_no"
                                   name="color_pages">
                            <label for="inside_color_no">
                                черно-белый
                            </label>

                            <input @if(intval($own_book->printorder['color_pages'] ?? 'no_print') > 0) checked @endif type="radio"
                                   value="inside_color_yes" class="show-hide"
                                   id="inside_color_yes"
                                   name="color_pages">
                            <label for="inside_color_yes">
                                цветной
                            </label>
                        </div>

                    </div>
                </div>

                <div style="padding:0; margin: auto; width: 80%; border-left: none; "
                     class="participation-outputs prints-needed participation-price">
                    <h1 style="font-size: 62px;" id="print_price">{{$own_book['print_price']}}</h1>
                    <h1 style="font-size: 57px;">&nbsp;руб.</h1>
                    <div class="participation-price-desc">
                        <p style="margin-top: 5px; font-size: 24px;">За печать (<span id="print_needed">1</span>&nbsp;экз.)
                        </p></div>
                </div>
            </div>


            <div style="flex-direction: row;     align-items: center;"
                 class="participation-inputs-row">
                <p style="    width: 35%;">Количество экземпляров:</p>
                <label for="prints-num"></label><input style="max-width: 80px; margin-right: 40px;"
                                                       type="number"
                                                       name="prints-num"

                                                       value="1" id="prints-num">
                <div class="slider-wrap">
                    <div id="slider-nonlinear" class="slider">
                        <div id="custom-handle" class="ui-slider-handle">
                            <div class="slider-tooltip"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="flex-direction: row;     align-items: center;"
                 class="participation-inputs-row">
                <div class="input-group">
                    <p>Фио получателя</p>
                    <input wire:model="send_to_name" type="text"
                           value="{{ Auth::user()->surname}} {{ Auth::user()->name}}"
                           name="send_to_name" id="send_to_name">
                </div>
                <div class="input-group">
                    <p>Адрес с индексом</p>
                    <input wire:model="send_to_address" type="text" name="send_to_address"
                           id="send_to_address">
                </div>

                <div class="input-group">
                    <p>Телефон</p>
                    <input wire:model="send_to_tel" type="number"
                           name="send_to_tel"
                           id="send_to_tel">
                </div>
            </div>
            <a id="save_form" class="header-button-wrap  button">Сохранить</a>
            <a name="create_form" style="margin-left: 10px;" class="header-button-wrap  show-hide button">Отменить</a>
        </div>
    </div>

    {{----------- // БЛОК ПЕЧАТИ -----------}}


    <script>
        var
            pages = {{$own_book['pages']}},

            cover_color = 0,
            cover_type = "",
            cover_comment = null,
            cover_files_to_php = '',

            print_needed = 0,
            tirag_coef = 0,
            pages_coef = 0,
            inside_color = 0,
            cover_color_coef = 0,
            cover_style_coef = 0,
            color_pages = {{$own_book['color_pages'] ?? 0}},

            total_price = 0;

        // -----------------------------PRICES---------------------------------------------

        print_needed = {{$own_book->printorder['books_needed'] ?? 1}};

        function calculation() {

            //Обрабатываем значение печати
            if (print_needed < 10) {
                tirag_coef = 1
            } else if (print_needed < 50) {
                tirag_coef = 0.95
            } else {
                tirag_coef = 0.9
            }

            if (print_needed < 100) {
                pages_coef = 1.8
            } else {
                pages_coef = 1
            }

            if ($("#cover_color_yes").prop("checked") === true) {
                cover_color_coef = 1;
                cover_color = 1;
            } else {
                cover_color_coef = 0.7;
                cover_color = 0;
            }

            if ($("#cover_style_hard").prop("checked") === true) {
                cover_style_coef = 2.1
                cover_type = "hard"
            } else {
                cover_style_coef = 1
                cover_type = "soft"
            }

            if ($("#inside_color_yes").prop("checked") === false) {
                color_pages = 0
            }
            else {
                color_pages = {{$own_book['color_pages'] ?? 0}}
            }

            // --------------------------------

            print_price = (pages - color_pages + (color_pages * 3)) * 0.7 * tirag_coef * cover_color_coef * cover_style_coef * pages_coef * print_needed * 2.2;

            //--------------------------------

            console.log('--------------------------------');
            console.log('pages: ' + pages);
            console.log('color_pages: ' + color_pages);
            console.log('tirag_coef: ' + tirag_coef);
            console.log('cover_color_coef: ' + cover_color_coef);
            console.log('cover_style_coef: ' + cover_style_coef);
            console.log('pages_coef: ' + pages_coef);
            console.log('print_needed: ' + print_needed);


            console.log('print_price: ' + Math.round(print_price));

            console.log('PRINT: ' + pages + ' - ' + color_pages + ' + ' + color_pages * 3 + ' * ' + 0.7 + ' * ' + tirag_coef + ' * '
                + cover_color_coef + ' * ' + cover_style_coef + ' * ' + pages_coef + ' * ' + print_needed + ' * ' + 2.2 + ' = ' + print_price);
            console.log('--------------------------------');


            $('#pages').html(pages);
            $('#print_needed').html(print_needed);
            $('#print_price').html(parseInt(Math.round(print_price)).toLocaleString());


        };

        // Меняем цифру кол-ва экземпляров
        $("#prints-num").keyup(function get_print_val() {

            if($(this).val() < {{$own_book->printorder['books_needed'] ?? 1}})
                {
                    $("#prints-num").val({{$own_book->printorder['books_needed'] ?? 1}})
            }
            print_needed = $(this).val();
            calculation();
        })
        // ------------------------------------

        // Меняем цифру кол-ва цветных страниц
        $("#color_pages").keyup(function () {
            color_pages = {{$own_book['color_pages'] ?? 0}};
            calculation();
        })
        // ------------------------------------

        // Запускаем калькуляцию при обновлении livewire
        window.addEventListener('load_pages_from_doc', event => {
            pages_from_doc = event.detail.pages
            calculation();
        })
        // ------------------------------------

        // Запускаем калькуляцию при любом вводе
        $('input').on('change', function () {
            calculation();
        });
        // ------------------------------------

        // -----------------------------// PRICES---------------------------------------------

        // -----------------------------Slider---------------------------------------------

        var min = 0,
            max = 100,
            range = [],
            i = min,
            step = 1;

        do {

            range.push(i);
            i += step;


            if (i >= -1 && i < 5) {
                step = 1;
            }

            if (i >= 5 && i < 50) {
                step = 1;
            }

            if (i >= 50 && i < 100) {
                step = 1;
            }

        } while (i <= max);

        var slider_min = 1,
            slider_max = range.length - 1,
            cur_val = $("#prints-num").val(),
            handle = $("#custom-handle"),
            c = $("#prints-num").val();

        $("#slider-nonlinear").slider({
            values: [slider_min],
            min: slider_min,
            max: slider_max,
            animate: "slow",

            slide: function (event, ui) {

                c = ui.value;
                $("#prints-num").val(c);

                print_needed = c
                handle.text("");
                if (ui.value < 100) {
                    $('.ui-slider-handle').append('<div class="ui-slider-tooltip"> <p style="font-size: 18px;">' + ui.value + '</p></div>');
                } else {
                    $('.ui-slider-handle').append('<div class="ui-slider-tooltip"> <p style="font-size: 18px;">' + ">100, подробнее" + '</p></div>');
                }
                ;
                calculation();
                // jQueryUI position
                $('#ui-slider-tooltip').position({
                    of: $(".ui-slider-handle"),
                    at: 'center top',
                    my: 'center bottom'
                });
            },
            stop: function (event, ui) {
                if (ui.value < 100) {
                    $(".ui-slider-tooltip").remove();
                }
            }
        });

        function delay(callback, ms) {
            var timer = 0;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        $('#prints-num').keyup(delay(function (e) {

            if (typeof c == 'undefined') {
                if (typeof cur_val == 'undefined') {
                    cur_val = 1
                }
            } else {
                cur_val = c
            }
            ;
            var val = parseInt($(this).val());
            if (val > 100) {
                val = 100;
            }

            var i = cur_val;
            if (cur_val < val) {


                function myLoop() {
                    setTimeout(function () {
                        $(".ui-slider-tooltip").text(i);
                        $("#slider-nonlinear").slider("option", "values", [i]);
                        i++;
                        if (i - 1 < val) {
                            myLoop();
                        }
                    }, 5)
                }

                myLoop();
                cur_val = parseInt($(this).val());
            } else {

                var i = cur_val;

                function myLoop() {
                    setTimeout(function () {
                        $(".ui-slider-tooltip").text(i);
                        $("#slider-nonlinear").slider("option", "values", [i]);

                        i--;
                        if (i + 1 > val) {
                            myLoop();
                        }
                    }, 5)
                }

                myLoop();
                cur_val = parseInt($(this).val());
            }
            ;

        }, 400));

        // ----------------------------- // Slider---------------------------------------------

        calculation();
        @if($own_book['print_price'] > 0)
        $("#prints-num").val({{$own_book->printorder['books_needed'] ?? 1}})
        $("#slider-nonlinear").slider("option", "values", [{{$own_book->printorder['books_needed']}}]);
        $('#print_price').html({{$own_book['print_price']}});
        @endif
    </script>

    <script>

        document.addEventListener('livewire:load', function () {

            $("#save_form").click(function (event) {
                event.preventDefault();

            @this.set("pages", pages);
            @this.set("print_price", print_price);
            @this.set("cover_type", cover_type);
            @this.set("cover_color", cover_color);
            @this.set("color_pages", color_pages);
            @this.set("books_needed", print_needed);
            @this.set("send_to_name", $('#send_to_name').val());
            @this.set("send_to_address", $('#send_to_address').val());
            @this.set("send_to_tel", $('#send_to_tel').val());


                @if ($form_type === 'create')
                Livewire.emit('create_printorder')
                @elseif ($form_type === 'edit')
                Livewire.emit('edit_printorder')
                @endif
            });
        })

    </script>

    <script>
        document.addEventListener('close_form', function () {
            $('#block_create_form').hide();
        })
    </script>
</div>
