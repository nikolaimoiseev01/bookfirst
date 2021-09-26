@extends('layouts.portal_layout')

@section('page-title')
    {{$collection['title']}}
@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/collection-page.css">
    <link rel="stylesheet" href="/plugins/slick/slick.css">
    <style>
        .step p:first-child {
            color: #33b195;
            margin-bottom: 5px;
            font-size: 27px;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="bread">
            <a href="{{route('actual_collections')}}"><p>Сборники</p></a> / <p>{{$collection['title']}}</p>
        </div>
        <div class="collection-block">
            <div style="display: flex; align-items: center;">
                <img class="cover" src="/{{$collection['cover_3d']}}" alt="">
            </div>
            <div class="right-collection-info">
                <div class="col-text">
                    <h3>{{$collection['title']}}</h3>
                    <p>{{$collection['col_desc']}}</p>
                </div>
                <div class="col-card">
                    <div class="container">
                        <div class="row">
                            Статус сборника:&nbsp;<span>{{$collection->col_status['col_status']}}</span>
                        </div>
                        <div class="row">
                            Тираж сборника:&nbsp;<span>~ 100 экземпляров</span>
                        </div>
                        <div class="row">
                            Обложка:&nbsp;<span>Мягкая, цветная</span>
                        </div>
                        <div class="row">
                            <a style="    font-size: 25px; padding: 3px 35px;"
                               href="{{route('participation_create',$collection['id'])}}" class="log_check button">Принять
                                участие!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-info-block">
        <div class="container">
            <div class="nav">
                <a href="#process" class="current">Порядоя участия</a>
                <a href="#calculator">Калькулятор</a></li>
                <a href="#dates">Даты издания</a>
            </div>
            <div style="transition: .3s ease-in-out" class="list-wrap">
                <div id="process" class="process">
                    <div class="process-slider">
                        <div class="step">
                            <p>Шаг 1. Заполнение заявки</p>
                            <p> Нажмите "принять участие", чтобы заполнить заявку.
                                При заполнении можно сразу указать необходимое количество печатных экземпляров.
                                Все права на произведения всегда остаются строго за автором.
                                <br><i>Оплата производится только после подтверждения нашей готовности включить Вас в
                                    сборник!</i>
                            </p>
                        </div>
                        <div class="step">
                            <p>Страница участия</p>
                            <p> Сразу после заполнения заявки, Вы будете перенаправлены на отдельную страницу конкретно
                                Вашего участия в личном кабинете.
                                Это главная страница участия, на ней Вы сможете отслеживать весь процесс издания.
                                Так же на ней будет доступен чат с поддержкой на случай каких-либо вопросов.
                            </p>
                        </div>
                        <div class="step">
                            <p>Шаг 2. Ожидание подтверждения</p>
                            <p>После того, как заявка была отправлена, произведения проходят цензуру.
                                В них не должно быть призывов к терроризму или иного запрещенного контента.
                                Сразу после нашего подтверждения заявки, Вы получите оповещения (Email в том числе) о
                                необходимости оплаты.</p>
                        </div>
                        <div class="step">
                            <p>Шаг 3. Оплата участия</p>
                            <p>После нашей проверки на странице участия в личном кабинете будет доступна форма оплаты.
                                Ее можно будет произвести через одну из многочисленных платежных систем
                                (Оплата любой картой, Yandex money, Western Union, PayPal, номером телефона и
                                другие)</p>
                        </div>
                        <div class="step">
                            <p>Шаг 4. Предварительная проверка</p>
                            <p><a class="triger_dates link">В указанную дату</a> на странице вашего участия в личном
                                кабинете будет открыт блок предварительной проверки.
                                В этом блоке можно будет скачать PDF файл сборника и указать необходимые изменения в
                                специальной форме.
                                Как только исправление будет учтено, Вы будете об этом оповещены.</p>
                        </div>
                        <div class="step">
                            <p>Шаг 5. Получение печатного экземпляра</p>
                            <p>Если кроме участия Вы заказывали печатные экземпляры, то <a class="triger_dates link">в
                                    указанную дату</a> на странице вашего участия в личном кабинете будет доступна
                                ссылка для отслеживания сборника.
                                По умолчанию мы посылаем сборники Почтой России, но при необходимости готовы
                                использовать любую другую транспортную компанию.</p>
                        </div>
                    </div>
                </div>
                <div id="calculator" class="hide">

                    <div class="calc-inputs">
                        <div class="calc-row">
                            <label for="pages"><p>Выберите количетсво страниц (максимум 28)</p></label>
                            <select id="pages" name="pages">
                                <option value="1000">1-7</option>
                                <option value="1900">8-14</option>
                                <option value="2850">15-21</option>
                                <option value="3800">22-28</option>
                            </select>
                        </div>

                        <div class="calc-row">
                            <p>Мне также необходимо экземпляров:</p><input min="0" value="1" id="print_needed_input"
                                                                           class="number-input" type="number">
                        </div>

                        <div class="calc-row no-wrap">
                            <div class="check-block">
                                <label for="text-check">
                                    <p>
                                       <span
                                           style="margin-right: -6px; margin-left: 8px; position: relative !important;"
                                           class="question-mark tooltip"
                                           title="Включает в себя проверку орфографии и пунктуации текста.">
                                            <svg style="vertical-align: middle;" id="question-circle" data-name="Capa 1"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 40.12 40.12">
                                                <path
                                                    d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                                <path
                                                    d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                                <path
                                                    d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                            </svg>
                                        </span>
                                        Мне нужна проверка:
                                    </p>
                                </label>
                                <input id="text-check" type="checkbox">
                            </div>
                            <div class="check-block">
                                <label for="promo"><p>У меня есть скидка в 20%:</p></label>
                                <input id="promo" type="checkbox">
                            </div>
                        </div>
                    </div>
                    <div class="calc-outputs">
                        <div class="prices-seperate">
                            <div class="participation-price">
                                <div id="part_price" class="price-number">1000</div>
                                <p class="price-desc">Участие</p>
                            </div>
                            <div class="participation-price">
                                <p class="participation-price-plus price-desc">+</p>
                            </div>
                            <div class="participation-price">
                                <div id="print_price" class="price-number">800</div>
                                <p class="price-desc">Печать (<span id="print_needed">1</span> экз.)</p>
                            </div>
                            <div style="display: none;" id="text_check_plus" class="participation-price">
                                <p class="participation-price-plus price-desc">+</p>
                            </div>
                            <div style="display: none;" id="text_check_wrap" class="participation-price">
                                <div id="text_check" class="price-number">800</div>
                                <p class="price-desc">Проверка </p>
                            </div>
                        </div>
                        <div class="price-total">
                            <p class="price-desc">Итого:&nbsp;</p>
                            <div id="total_price" class="price-number">800</div>
                            <p class="price-desc">&nbsp;руб.</p>
                        </div>

                    </div>
                </div>
                <div id="dates" class="hide">
                    {{App::setLocale('ru')}}
                    <div class="dates-wrap">
                        <div class="date-block">
                            <h4>{{ Date::parse($collection['col_date1'])->format('j F') }}</h4>
                            <p>Конец приема заявок</p>
                            <span class="question-mark tooltip"
                                  title="Прием заявок заканчивается в 23:59 МСК указанного дня">
                                   <svg id="question-circle" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 40.12 40.12">
                                        <path
                                            d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                        <path d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                        <path
                                            d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                    </svg>
                                </span>
                        </div>
                        <div class="date-block">
                            <h4>{{ Date::parse($collection['col_date2'])->format('j F') }}</h4>
                            <p>Отправка предварительного варианта сборника</p>
                            <span class="question-mark tooltip"
                                  title="До 23:59 МСК указанного дня в вашем личном кабинете будет доступно скачивание предварительного экземпляра сборника, а также форма указания исправлений">
                                   <svg id="question-circle" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 40.12 40.12">
                                        <path
                                            d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                        <path d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                        <path
                                            d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                                    </svg>
                             </span>
                        </div>
                        <div class="date-block">
                            <h4>{{ Date::parse($collection['col_date3'])->format('j F') }}</h4>
                            <p>Отправка сборника в печать</p>
                        </div>
                        <div class="date-block">
                            <h4>{{ Date::parse($collection['col_date4'])->format('j F') }}</h4>
                            <p>Отправка экземпляров авторам</p>
                            <span class="question-mark tooltip"
                                  title="После отправки печатных экземпляров в вашем личном кабинете будет доступна ссылка для отслеживания посылки.">
                                   <svg id="question-circle" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="/js/col-info-block.js"></script>
    <script src="/plugins/slick/slick.min.js"></script>
    <script>
        $('.process-slider').slick({
            infinite: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: true,
            // adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 700,
                    settings: {
                        slidesToShow: 1,
                    }
                }],
        });

        $('.triger_dates').on('click', function () {
            $('a[href$="#dates"]').trigger('click');
        })
    </script>



    <script>

        var pages_price = 1000,
            part_discount = 1,
            part_price = 1000,

            print_needed = 1,
            text_check = 0,

            print_discount = 1,
            print_price = 300;
        total_price = 1300;

        function calculation() {
            pages_price = $('#pages').val();
            print_needed = $('#print_needed_input').val();

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

            if ($('#promo').is(':checked')) {
                part_discount = 0.8;
            } else {
                part_discount = 1;
            }
            ;

            if ($('#text-check').is(':checked')) {
                text_check = 0.65 * pages_price;

            } else {
                text_check = 0;
            }
            ;

            print_price = print_needed * (300 * print_discount)
            part_price = pages_price * part_discount;

            total_price = part_price + text_check + print_price;

            $('#part_price').html(part_price);
            $('#print_needed').html(print_needed);
            $('#print_price').html(print_price);
            $('#text_check').html(text_check);
            $('#total_price').html(total_price);

            console.log('pages price: ' + pages_price);

            console.log('print_needed: ' + print_needed);

            console.log('print_discount: ' + print_discount);
            console.log('part_discount: ' + part_discount);

            console.log('text_check: ' + text_check);

            console.log('print_price: ' + print_price);
            console.log('total_price: ' + total_price);
            console.log('---------------------------------------');


        }

        calculation()

        $('.number-input').keyup(function () {
            if ($('#print_needed_input').val() === '') {
                $('#print_needed_input').val(0)
            }
            calculation()

        })

        $('select, input').on('change', function () {
            calculation()
        })

        $('#text-check').on('change', function () {
            $("#text_check_wrap").animate({width: 'toggle'}, 350);
            $("#text_check_plus").animate({width: 'toggle'}, 350, function () {
                $('.list-wrap').css('height', $('#calculator').innerHeight())
            });

        })
    </script>


@endsection
