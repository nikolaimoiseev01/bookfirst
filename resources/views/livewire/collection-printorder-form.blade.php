<div>
    <style>
        .participation-inputs-row {
            margin-bottom: 10px !important;

        }
    </style>
    {{----------- БЛОК ПЕЧАТИ -----------}}
    <div id='print_block' style="padding: 0 0 20px 0; border-bottom: none;" class="ob-applic-block">
        <div wire:ignore style="padding:0; display: block" class="prints-needed">
            <div style="width:100%; padding:0;" class="participation-inputs">
                <input style="display: none;" value="{{$collection['id']}}" name="collection_id" type="number">

                <div wire:ignore style="margin-top: 20px; margin-bottom: 20px;" class="ptint-block">
                    <div style="margin:0 !important; flex-direction: row;     align-items: center;"
                         class="participation-inputs-row">
                        <p style="    width: 35%;">Количество экземпляров:</p>
                        <label for="prints-num"></label><input style="max-width: 80px; margin-right: 40px;"
                                                               type="number"
                                                               name="prints-num"
                                                               min="{{$participation->printorder['books_needed'] ?? 1}}"
                                                               value="1" id="prints-num">
                        <div style="width: 50%;" class="slider-wrap">
                            <div id="slider-nonlinear" class="slider">
                                <div id="custom-handle" class="ui-slider-handle">
                                    <div class="slider-tooltip"></div>
                                </div>
                            </div>
                        </div>
                        <div style="width: 50%; border-left: none;" id="print-price"
                             class="participation-outputs participation-price">
                            <h1 id="print_price">300</h1>
                            <h1> руб.</h1>
                            @if($participation['print_price'] > 0)
                                <p><i>доплатить: <span id="extra_pay">0</span></i></p>
                            @endif
                            <div style="margin:0;" class="participation-price-desc"><p>За печать (
                                <p id="print_needed">1</p>
                                <p>экз.)</p></div>
                        </div>

                    </div>
                    <div style="margin-bottom: 0; flex-direction: row;     align-items: center;"
                         class="participation-inputs-row">
                        <div style="margin-bottom: 0;" class="input-group">
                            <p>Фио получателя</p>
                            <input wire:model="send_to_name" type="text"
                                   value="{{ Auth::user()->surname}} {{ Auth::user()->name}}" placeholder="Фио"
                                   name="send_to_name" id="send_to_name">
                        </div>
                        <div style="margin-bottom: 0;" class="input-group">
                            <p>Адрес с индексом</p>
                            <input wire:model="send_to_address" type="text" name="send_to_address"
                                   id="send_to_address">
                        </div>

                        <div style="margin-bottom: 0;" class="input-group">
                            <p>Телефон</p>
                            <input wire:model="send_to_tel" type="text"
                                   name="send_to_tel"
                                   id="send_to_tel">
                        </div>
                    </div>

                </div>
            </div>
            <a id="save_form" class="header-button-wrap  button">Оплатить дополнительные экземпляры</a>
            <a name="create_form" style="margin-left: 10px;" class="header-button-wrap  show-hide button">Отменить</a>
        </div>
    </div>

    {{----------- // БЛОК ПЕЧАТИ -----------}}


    <script>
        document.addEventListener('close_form', function () {
            $('#block_create_form').hide();
        })
    </script>


    <script>
        {{Session(['back_after_add' => \Livewire\str(Request::url())])}}


        // -----------------------------PRICES---------------------------------------------
        $("#prints-num").keyup(function get_print_val() {
            print_needed = $(this).val();
            calcuation();
        })

        var print_needed = {{$participation->printorder['books_needed'] ?? 1}},
            print_price = {{$participation['print_price'] ?? 300}},
            print_discount = 1;
        pay_extra = 0;

        function calcuation() {

            if (print_needed <= 5) {
                print_discount = 1;
            } else if (print_needed > 5 && print_needed <= 10) {
                print_discount = 0.95
            } else if (print_needed > 10 && print_needed <= 20) {
                print_discount = 0.90;
            } else if (print_needed > 20) {
                print_discount = 0.85;
            }
            ;

            print_price = print_needed * (300 * print_discount);
            pay_extra = print_price - {{$participation['print_price']}};

            $('#print_needed').html(print_needed);
            $('#print_price').html(print_price);
            $('#extra_pay').html(pay_extra);


        };
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


        var slider_min = {{$participation->printorder['books_needed'] ?? 1}},
            slider_max = range.length - 1,
            cur_val = $("#prints-num").val(),
            handle = $("#custom-handle");

        $("#slider-nonlinear").slider({
            values: [slider_min],
            min: slider_min,
            max: slider_max,
            animate: "slow",
            create: function () {
                // handle.text( $( this ).slider( "value" ) );
            },
            slide: function (event, ui) {

                c = ui.value;
                $("#prints-num").val(c);
                print_needed = c
                calcuation();
                handle.text("");
                if (ui.value < 100) {
                    $('.ui-slider-handle').append('<div class="ui-slider-tooltip"> <p style="font-size: 18px;">' + ui.value + '</p></div>');
                } else {
                    $('.ui-slider-handle').append('<div class="ui-slider-tooltip"> <p style="font-size: 18px;">' + ">100, подробнее" + '</p></div>');
                }
                ;
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

        @if($participation['print_price'] > 0)
        $("#prints-num").val({{$participation->printorder['books_needed'] ?? 1}})
        $("#slider-nonlinear").slider("option", "values", [{{$participation->printorder['books_needed']}}]);
        $('#print_price').html({{$participation['print_price']}});
        @endif

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

            if ($(this).val() < {{$participation->printorder['books_needed'] ?? 1}}) {
                $("#prints-num").val({{$participation->printorder['books_needed'] ?? 1}})
            }
            print_needed = $(this).val();

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

                calcuation();
                myLoop();
                cur_val = parseInt($(this).val());

            }
            ;
            // -----------------------------Slider---------------------------------------------


        }, 400));


        document.addEventListener('livewire:load', function () {
            $("#save_form").click(function (event) {
            @this.set("print_needed", print_needed);
            @this.set("print_price", print_price);
            @this.set("pay_extra", pay_extra);
                Livewire.emit('save_printorder')

            })
        });
    </script>
</div>
