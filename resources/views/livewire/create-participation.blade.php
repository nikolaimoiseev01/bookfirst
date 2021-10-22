<form
    wire:submit.prevent="storeParticipation(Object.fromEntries(new FormData($event.target)))"
    enctype="multipart/form-data">
    <div class="create-participation-form">
        <div>
            <div class="container">
                @csrf
                <div class="participation-inputs">
                    <input style="display: none;" value="{{$collection['id']}}" name="collection_id" type="number">
                    <div class="participation-inputs-row">
                        <div class="input-group">
                            <p>Имя</p>
                            <input required wire:model.self="name" type="text"
                                   placeholder="Имя" name="name" id="name">
                        </div>
                        <div class="input-group">
                            <p>Фамилия</p>
                            <input required wire:model="surname" type="text"
                                   placeholder="Фамилия" name="surname"
                                   id="surname">
                        </div>

                        <div class="input-group">
                            <p>Псевдоним</p>
                            <input wire:model="nickname" type="text" value="{{ Auth::user()->nickname}}"
                                   placeholder="Псевдоним"
                                   name="nickname"
                                   id="nickname">
                        </div>

                    </div>

                    <div wire:ignore style="flex-flow: column;" class="participation-inputs-row">
                        <p>Произведения для участия</p>
                        <div>

                            <div class="add-work-block">

                                <span class="question-mark tooltip"
                                      title="Порядок произведений можно менять перетаскиванием">
                                   <svg id="question-circle" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 40.12 40.12">
                                        <path
                                            d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                        <path d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                        <path
                                            d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                    </svg>
                                </span>

                                <div

                                    class="add-work-button">
                                    <a class="add-work-button-link">
                                        <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 512 512">
                                            <path
                                                d="M256,512C114.84,512,0,397.16,0,256S114.84,0,256,0,512,114.84,512,256,397.16,512,256,512Zm0-480C132.48,32,32,132.48,32,256S132.48,480,256,480,480,379.52,480,256,379.52,32,256,32Z"/>
                                            <path d="M368,272H144a16,16,0,0,1,0-32H368a16,16,0,0,1,0,32Z"/>
                                            <path
                                                d="M256,384a16,16,0,0,1-16-16V144a16,16,0,0,1,32,0V368A16,16,0,0,1,256,384Z"/>
                                        </svg>
                                        Добавить
                                    </a>

                                    <div class="custom-scroll work-menu">

                                        <h2 style="font-size: 24px; margin-bottom: 10px;">Мои произведения:</h2>
                                        @if(count($user_works) < 1)
                                            <p style="    font-size: 19px; line-height: 22px; margin-bottom: 10px;">У
                                                Вас еще нет произведений!
                                                Для того, чтобы учавствовать в сборниках, произведения должны сначала
                                                быть добавлены в нашу систему,
                                                а затем выбраны из этого списка.</p>
                                        @endif

                                        <input id="work_search" placeholder="поиск..."
                                               style="height: 30px; width: 100%; margin-bottom: 14px;" type="text">
                                        @foreach($user_works as $work)

                                            <div id="work-container-{{$work['id']}}" class="container">
                                                <p>{{Str::limit($work['title'], 20)}}</p>
                                                <div class="one-work-button">
                                                    <a class="add_remove_buttons">
                                                        <svg
                                                            id="not-in-{{$work['id']}}"
                                                            viewBox="0 0 448.13 490.8">
                                                            <path class="cls-1"
                                                                  d="M231.7,3.13a10.67,10.67,0,0,0-15.09,15.08L443.73,245.35,216.59,472.46a10.67,10.67,0,0,0,14.82,15.35l.26-.27L466.34,252.88a10.66,10.66,0,0,0,0-15.09Z"
                                                                  transform="translate(-21.34 0)"/>
                                                            <path class="cls-1"
                                                                  d="M274.36,237.79,39.7,3.13A10.67,10.67,0,0,0,24.61,18.21L251.73,245.35,24.59,472.46a10.67,10.67,0,0,0,14.82,15.35l.27-.27L274.34,252.88A10.67,10.67,0,0,0,274.36,237.79Z"
                                                                  transform="translate(-21.34 0)"/>
                                                            <path
                                                                d="M224.14,490.68a10.67,10.67,0,0,1-7.55-18.22L443.73,245.35,216.59,18.23A10.66,10.66,0,0,1,231.67,3.15L466.34,237.82a10.65,10.65,0,0,1,0,15.08L231.68,487.57A10.69,10.69,0,0,1,224.14,490.68Z"
                                                                transform="translate(-21.34 0)"/>
                                                            <path
                                                                d="M32.14,490.68a10.67,10.67,0,0,1-7.55-18.22L251.73,245.35,24.59,18.23A10.68,10.68,0,0,1,39.7,3.13L274.36,237.8a10.65,10.65,0,0,1,0,15.08L39.7,487.54A10.68,10.68,0,0,1,32.14,490.68Z"
                                                                transform="translate(-21.34 0)"/>
                                                        </svg>

                                                        <svg data-rows="{{$work['rows']}}" class="in"
                                                             id="in-{{$work['id']}}" data-name="Capa 1"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 229.15 226.47">
                                                            <path
                                                                d="M92.36,223.55c7.41,7.5,23.91,5,25.69-6.78,11-73.22,66.38-135,108.24-193.19C237.9,7.45,211.21-7.87,199.75,8.07,161.49,61.25,113.27,117.21,94.41,181.74c-21.56-22-43.2-43.85-67.38-63.21-15.31-12.26-37.21,9.35-21.74,21.74C36.79,165.5,64,194.92,92.36,223.55Z"
                                                                transform="translate(0 -1.34)"/>
                                                        </svg>

                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="add-to-stystem-wrap">

                                            <a onclick="location.href='{{route('work.create')}}';"
                                               class="fast-load link">
                                                <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 448 448">
                                                    <path
                                                        d="M408,184H272a8,8,0,0,1-8-8V40a40,40,0,0,0-80,0V176a8,8,0,0,1-8,8H40a40,40,0,0,0,0,80H176a8,8,0,0,1,8,8V408a40,40,0,0,0,80,0V272a8,8,0,0,1,8-8H408a40,40,0,0,0,0-80Z"
                                                        transform="translate(0 0)"/>
                                                </svg>
                                                Добавить вручную
                                            </a>
                                            <a onclick="location.href='{{route('create_from_doc')}}';"
                                               class="fast-load link">
                                                <svg id="Слой_1" viewBox="0 0 404.85 511">
                                                    <g id="surface1">
                                                        <path
                                                            d="M329.27,3A12.38,12.38,0,0,0,320.38-1H121C84.26-1,53.89,29.24,53.89,66V443c0,36.78,30.37,67,67.15,67H391.6c36.78,0,67.14-30.24,67.14-67V143.66a13.27,13.27,0,0,0-3.58-8.64Zm3.57,39.62,84.31,88.5h-54.8a29.39,29.39,0,0,1-29.51-29.37ZM391.6,485.32H121C98,485.32,78.58,466.19,78.58,443V66c0-23.08,19.26-42.33,42.46-42.33H308.16v78a54,54,0,0,0,54.19,54.06h71.71V443A42.67,42.67,0,0,1,391.6,485.32Z"
                                                            transform="translate(-53.89 1)"/>
                                                        <path
                                                            d="M357.9,400.15H154.74a12.35,12.35,0,1,0,0,24.69H358a12.35,12.35,0,1,0-.13-24.69Z"
                                                            transform="translate(-53.89 1)"/>
                                                        <path
                                                            d="M247.31,355.84a12.25,12.25,0,0,0,18,0l72.33-77.64a12.31,12.31,0,0,0-18-16.79l-51,54.68V181.31a12.34,12.34,0,0,0-24.68,0V316.09l-50.86-54.68a12.31,12.31,0,0,0-18,16.79Z"
                                                            transform="translate(-53.89 1)"/>
                                                    </g>
                                                </svg>
                                                Добавить файлом
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="works-to-go">

                                </div>
                            </div>

                        </div>

                    </div>

                    <div style="margin-bottom: 0;" wire:ignore class="participation-inputs-row">
                        <div style="margin-bottom: 0;" id="print_need" class="check-block">
                            <label for="prints-needed"><p>Мне необходимы печатные экземпляры</p></label>
                            <input onchange="calcuation()" id="prints-needed" type="checkbox">
                        </div>

                        <div style="margin-bottom: 0;" wire:ignore class="check-block">
                            <label for="text-check"><p>Мне нужна проверка:</p>
                                <span style="top: 3px;" class="tooltip"
                                      title="Услуга проверки пунктуации и орфографии">
                                       <svg id="question-circle" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 40.12 40.12">
                                            <path
                                                d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                            <path d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                            <path
                                                d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                        </svg>
                                    </span>
                            </label>
                            <input onchange="calcuation()" id="text-check" type="checkbox">
                        </div>
                    </div>
                    <div wire:ignore style="margin-top: 20px; margin-bottom: 0; display: none" class="ptint-block">
                        <div style="flex-direction: row;     align-items: center;" class="participation-inputs-row">
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
                        <div style="margin-bottom: 0; flex-direction: row;     align-items: center;"
                             class="participation-inputs-row">
                            <div style="margin-bottom: 0;" class="input-group">
                                <p>ФИО получателя</p>
                                <input wire:model="send_to_name" type="text"
                                       value="{{ Auth::user()->surname}} {{ Auth::user()->name}}" placeholder="ФИО"
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

                <div wire:ignore class="participation-outputs">
                    <h2>Стоимость</h2>
                    <div class="participation-price">
                        <div style="display: flex;">
                            <h1 id="participation_price">0</h1>
                            <h1 style="margin-left:10px;">руб.</h1>
                        </div>
                        <div style="position: relative;" class="participation-price-desc"><p>За участие (
                            <p id="pages">0</p>
                            <p>стр.)</p>
                            <span style="bottom: 0; right: -30px;" class="question-mark tooltip"
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
                    <div style="display: none" id="print-price" class="participation-price">
                        <div style="display: flex;">
                            <h1 id="print_price">300</h1>
                            <h1 style="margin-left:10px;">руб.</h1>
                        </div>
                        <div class="participation-price-desc"><p>За печать (
                            <p id="print_needed">1</p>
                            <p>экз.)</p></div>
                    </div>
                    <div style="display: none" id="check-price" class="participation-price">
                        <div style="display: flex;">
                            <h1 id="check_needed">0</h1>
                            <h1 style="margin-left:10px;">руб.</h1>
                        </div>
                        <div class="participation-price-desc"><p>За проверку</p></div>
                    </div>
                    <div class="participation-price">
                        <div style="display: flex;">
                            <h1 id="total_price">0</h1>
                            <h1 style="margin-left:10px;">руб.</h1>
                        </div>
                        <p style="margin-top: 5px;">Итого</p>
                    </div>

                    <a wire:ignore id="i_have_promo_link" class="link">У меня есть промокод</a>
                    <p style="text-align: center" id="promo_ok"></p>
                    <div id="i_have_promo_inputs" style="display: none;">
                        <div style="display: flex; align-items: center;">
                            <div style="margin-left: 20px;" class="search-bar-wrap">
                                <input wire:model="promo_search_input" required placeholder="Промокод..."
                                       id="promo_search_input" name="promo_search_input" type="text">

                                <a wire:click.prevent="check_promo" style="opacity: 1;" id="work_input_search_link">
                                    <span class="tooltip" title="Проверить">
                                    <svg width="14px" viewBox="0 0 188.9 210"><defs><style>.cls-1 {
                                                    fill: none;
                                                    stroke: #6dc4b1;
                                                    stroke-linecap: round;
                                                    stroke-linejoin: round;
                                                    stroke-width: 23px;
                                                }</style></defs><polyline class="cls-1"
                                                                          points="11.5 98.4 84.3 198.5 177.4 11.5"/></svg>
                                    </span>
                                </a>
                            </div>
                            <span id="close_promo_inputs" style="margin-left: 8px; margin-top: 1px;"
                                  class="cursor tooltip" title="Отменить">
                            <svg width="13px" viewBox="0 0 50 50"><defs><style>.cls-11 {
                                            fill: #ff6565;
                                        }</style></defs><path class="cls-11"
                                                              d="M45.8,50a4,4,0,0,1-2.9-1.2L1.2,7.1a4.23,4.23,0,0,1,0-5.9,4.23,4.23,0,0,1,5.9,0L48.8,42.9a4.15,4.15,0,0,1-3,7.1Z"
                                                              transform="translate(0 0)"/><path class="cls-11"
                                                                                                d="M4.2,50a4,4,0,0,1-2.9-1.2,4.23,4.23,0,0,1,0-5.9L42.9,1.2a4.23,4.23,0,0,1,5.9,0,4.23,4.23,0,0,1,0,5.9L7.1,48.8A4.18,4.18,0,0,1,4.2,50Z"
                                                                                                transform="translate(0 0)"/></svg>
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <button style="margin-right:20px;" type="submit" id="save_form" class="preloader_button button">
        <span class="button__text">Отправить заявку</span>
    </button>

</form>

<a style="display:none;" id="go-to-part-page" class="fast-load">Главная</a>

@section('page-js')

    <script>
        {{Session(['back_after_add' => \Livewire\str(Request::url())])}}

        function close_swal() {
            Swal.close();
        }

        $("#print_need input").change(function () {
            $(".ptint-block").slideToggle("slow");
            $("#print-price").slideToggle("slow");
            calcuation();
        });

        $("#text-check").change(function () {
            $("#check-price").slideToggle("slow");
            calcuation();
        });

        menu = $('.work-menu');

        $(document).mouseup(e => {
            if (!menu.is(e.target) // if the target of the click isn't the container...
                && menu.has(e.target).length === 0) {
                menu.removeClass('is-active');
            }
        });

        $('.add-work-button a').click(function (event) {
            event.preventDefault();
            menu.addClass('is-active');
        });

        $('#work_search').keyup(function () {
            worksearch = this.value

            $('.work-menu .container').each(function () {
                if (worksearch != "") {
                    if ($(this).find("p:first").text().toLowerCase().indexOf(worksearch) == -1) {
                        $(this).css('display', 'none');
                    } else {
                        $(this).css('display', 'flex');
                    }
                } else {
                    $(this).css('display', 'flex');
                }
            })
        })


        // WORK WITH PROMO_DISCOUNT BLOCK

        var promo_discount = 0;


        $('#i_have_promo_link').on('click', function () {
            $('#i_have_promo_inputs').toggle();
            $(this).toggle();
        })

        $('#close_promo_inputs').on('click', function () {
            $('#i_have_promo_inputs').toggle();
            $('#i_have_promo_link').toggle();
        })


        window.addEventListener('update_promo_discount', event => {
            promo_discount = event.detail.promo_discount;

            $('#i_have_promo_link').remove();
            $('#i_have_promo_inputs').remove();
            $('#promo_ok').html('<i>Cкидка в ' + promo_discount + '% учтена.</i>')
            Swal.fire({
                title: 'Промокод успешно учтен!',
                icon: 'success',
                html: '<p>Ваша скидка на участие составляет: ' + promo_discount + '%</p>',
                showConfirmButton: false,
            })

            calcuation();
        })
        // ----------------------------------------------------

        if (jq_loaded != 1) {


            // -----------------------------PRICES---------------------------------------------
            $("#prints-num").keyup(function get_print_val() {
                print_needed = $(this).val();
                calcuation();
            })

            var pages = 0,
                rows = 0,
                check_needed = 0,
                print_needed = 0,
                number_works = 0,
                participation_price = 0,
                print_price = 0,
                pagtotal_pricees = 0,
                print_discount = 1,


                print_needed = $("#prints-num").val();


            function calcuation() {

                rows = 0;
                number_works = 0;
                $('.works-to-go .container').each(function () {
                    number_works += 1;
                    rows += parseInt($(this).attr('data-rows'), 0);
                });

                if (rows === 0) {
                    participation_price = 0
                } else if (rows < 245) {
                    participation_price = 1000
                } else if (rows < 490) {
                    participation_price = 1900
                } else if (rows < 735) {
                    participation_price = 800
                } else if (rows < 980) {
                    participation_price = 3200
                } else {
                    participation_price = 3200 + (((row - 980) / 35) * 300);
                }

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

                promo = (100 - promo_discount) / 100;

                participation_price = participation_price * promo;

                if ($("#prints-needed").prop("checked") === false) {
                    print_needed = 0;
                    $("#prints-num").prop('required', false);
                    $("#send_to_name").prop('required', false);
                    $("#send_to_address").prop('required', false);
                    $("#send_to_tel").prop('required', false);
                } else {
                    print_needed = $("#prints-num").val();
                    $("#prints-num").prop('required', true);
                    $("#send_to_name").prop('required', true);
                    $("#send_to_address").prop('required', true);
                    $("#send_to_tel").prop('required', true);
                }

                if ($("#text-check").prop("checked") === false) {
                    check_needed = 0;
                } else {
                    check_needed = rows * 10
                }

                pages = Math.ceil(rows / 33)

                print_price = print_needed * (300 * print_discount);

                total_price = print_price + participation_price + check_needed


                $('#pages').html(pages);
                $('#print_needed').html(print_needed);
                $('#check_needed').html(check_needed);

                $("#participation_price").html(participation_price);
                $('#print_price').html(print_price);
                $('#total_price').html(total_price);


                console.log('--------------------------------');
                console.log('rows: ' + rows + '; pages: ' + pages);
                console.log('number_works: ' + number_works);

                console.log('print_needed: ' + print_needed);
                console.log('check_needed: ' + check_needed);

                console.log('discount: ' + (1 - promo_discount));

                console.log('part_price: ' + participation_price);
                console.log('print_price: ' + Math.round(print_price));
                console.log('total_price: ' + total_price);

                console.log('--------------------------------');

                $(function () {
                    $(".works-to-go").sortable({
                        placeholder: "to-drop",
                        revert: true,
                        start: function (event, ui) {
                            ui.item.addClass("start-anim")
                        },
                        stop: function (event, ui) {
                            ui.item.addClass("stop-anim")
                        }
                    });
                });
            };
            // -----------------------------// PRICES---------------------------------------------


            // -----------------------------Works Adding----------------------------------------------
            $(".one-work-button svg").on('click', function () {


                parts = $(this).attr("id").split('-');
                var id = parts.pop();
                rows = $(this).attr("data-rows");
                var text = $("#work-container-" + id + " p").text();
                $("#not-in-" + id).css('opacity', '0');
                $("#in-" + id).css('opacity', '1');

                $("#work-container-" + id + " a").css('pointer-events', "none");
                $("<div style=\"transition: none;\" data-rows='" + rows + "'id='work_to_go_" + id + "' class=\"container\">" +
                    "<p>" + text + "</p>" +
                    "<div id='remove_" + id + "' class='remove-work-wrap'>" +
                    "<a><img src='/img/cancel.svg'></a>" +
                    "</div>" + "<input style=\"display:none\" name=\"work[" + id + "]\" value=" + id + " type=\"number\">" +
                    "</div>").appendTo(".works-to-go");


                calcuation();//идем на расчет цены

                close_work = $('.remove-work-wrap')

                for (var i = 0; i < close_work.length; i++) {
                    close_work[i].addEventListener('click', function () {
                        parts = $(this).attr("id").split('_');
                        var id = parts.pop();
                        $("#work_to_go_" + id).remove();
                        $("#not-in-" + id).css('opacity', '1');
                        $("#in-" + id).css('opacity', '0');
                        $("#work-container-" + id + " a").css('pointer-events', "inherit");
                        if (!$(".work-menu").hasClass('is-active')) {
                            $('.work-menu').addClass('is-active');
                        }

                        calcuation(); //идем на расчет цены

                    }, false);
                }
            });

            // ---------------------------------------------------------------------------

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


            // -----------------------------Slider---------------------------------------------


        }

        jq_loaded = 1;

        document.addEventListener('livewire:load', function () {
            calcuation();

            $("#save_form").click(function (event) {
                event.preventDefault();
                works_to_php = '';
                $('.works-to-go .container').each(function () {
                    parts = $(this).attr("id").split('_');
                    works_to_php += parts.pop() + ";"
                })
                works_to_php = works_to_php.slice(0, -1)

            @this.set("works", works_to_php);
            @this.set("rows", rows);
            @this.set("pages", pages);
            @this.set("number_works", number_works);
            @this.set("number_works", number_works);

            @this.set("print_needed", print_needed);

            @this.set("part_price", participation_price);
            @this.set("print_price", print_price);
            @this.set("check_needed", check_needed);
            @this.set("total_price", total_price);

                Livewire.emit('storeParticipation')
            })
        });
    </script>
@endsection

