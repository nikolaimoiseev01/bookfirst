@extends('layouts.portal_layout')

@section('page-title')
    Издание собственной книги
@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/collection-page.css">
    <link rel="stylesheet" href="/plugins/slick/slick.css">
    <link rel="stylesheet" href="/css/create-participation.css">

    <style>
        .step p:first-child {
            color: #33b195;
            margin-bottom: 5px;
            font-size: 27px;
        }
    </style>
@endsection
<style>
    #check_needed input {
        margin-left: 10px !important;
        margin-right: 15px;
        margin-top: 0;
    }

    #price-parts-wrap {
        display: flex;
        align-items: center;
        justify-content: space-around;
        flex-wrap: wrap;
    }
</style>

@section('content')
    <div style="margin-top: 110px;" class="content">

        <div class="collection-block">
            <div style="display: flex; align-items: center;">
                <img class="cover" src="/img/own_book_example_cover.png" alt="">
            </div>
            <div class="right-collection-info">
                <div class="col-text">
                    <h3>Издать собственную книгу</h3>
                    <p>Кроме составления различных литературных сборников мы также предлагаем составить Вашу собственную
                        книгу. Мы возьмем весь процесс на себя, начиная от верстки, проверки текста, составления
                        содержания, и заканчивая регистрацией книги, присвоения ей уникального номера ISBN, а также ее
                        размещение на всемирных книжных интернет площадках (Amazon.com, Ozon.ru, Books.ru и т. д.).</p>
                </div>
                <div class="col-card">
                    <div class="container">
                        <div class="row">
                            Кол-во страниц:&nbsp;<span>>30</span>
                        </div>
                        <div class="row">
                            Возможный тираж:&nbsp;<span>от 1-го экземпляра</span>
                        </div>
                        <div class="row">
                            Обложка:&nbsp;<span>мягкая/твердая</span>
                        </div>
                        <div class="row">
                            Внутренний блок:&nbsp;<span>цветной/чб</span>
                        </div>
                        <div class="row">
                            <a style="    font-size: 25px; padding: 3px 35px;" href="{{route('own_book_create')}}"
                               class="log_check button">Подать заявку!</a>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-info-block">
        <div class="container">
            <div class="nav">
                <a href="#process" class="cont_nav_item current">Порядок издания</a>
                <a href="#calculator" class="cont_nav_item">Калькулятор</a>
                <a style="float: right;" href="{{route('help_own_book')}}" target="_blank">Инструкция</a>
            </div>
            <div style="" class="list-wrap">

                <div id="process" class="process">
                    <div class="process-slider">
                        <div class="step">
                            <p>Шаг 1. Заполнение заявки</p>
                            <p> Нажмите "Подать заявку", чтобы начать процесс издания.
                                При заполнении можно будет указать комментарии к обложке в случае, если она неготова,
                                необходимое количество печатных экземпляров и параметры печати.
                                Все права на произведения всегда остаются строго за автором.
                                <br><i>Оплата производится только после подтверждения нашей готовности включить Вас в
                                    сборник!</i>
                            </p>
                        </div>
                        <div class="step">
                            <p>Страница издания</p>
                            <p> Сразу после заполнения заявки, Вы будете перенаправлены на отдельную страницу конкретно
                                Вашего издания в личном кабинете.
                                Это главная страница издания, на ней Вы сможете отслеживать статусы издания, а также
                                любые изменения в процессе.
                                Так же на ней будет доступен чат с Вашим личным менеджером на случай каких-либо
                                вопросов.

                            </p>
                        </div>
                        <div class="step">
                            <p>Шаг 2. Ожидание подтверждения</p>
                            <p>После того, как заявка была отправлена, произведения проходят цензуру.
                                В них не должно быть призывов к терроризму или иного запрещенного контента.
                                Срок проверки макетов - 3 рабочих дня.
                                В случае отказа Вы получите подробную информацию о причинах нашего решения.
                                Сразу после нашего подтверждения заявки, Вы получите оповещения (Email в том числе) о
                                необходимости оплаты.</p>
                        </div>
                        <div class="step">
                            <p>Шаг 3. Оплата издания</p>
                            <p>После нашей проверки на странице издания в личном кабинете будет доступна форма оплаты.
                                Ее можно будет произвести через одну из многочисленных платежных систем
                                (Оплата любой картой, Yandex money, Western Union, PayPal и другие).
                                На этом этапе оплата производится за все услуги кроме печати книги, так как параметры
                                внутреннего блока могут поменяться в процессе редактуры.</p>
                        </div>
                        <div class="step">
                            <p>Шаг 4. Предварительная проверка</p>
                            <p>Если была заказаны услуга редактирвоания внутреннего блока или создания обложки,
                                то в течение 14-ти рабочих дней после оплаты на странице Вашего издания в личном
                                кабинете будет доступен блок предварительной проверки.
                                В нем Вы сможете в свободной форме указать все нобходимые изменения или утвердить его,
                                если все будет устраивать.
                            </p>
                        </div>
                        <div class="step">
                            <p>Шаг 5. Оплата печати</p>
                            <p>Как только макеты (внутренний блок и обложка) будут полностью утверждены, будет доступна
                                оплата печати в случае заказа печатных экземпляров.
                                Сразу после оплаты книги будут отправлены в печать. В течение 14-ти рабочих дней книги
                                будут напечатаны и отправлены автору.
                            </p>
                        </div>
                        <div class="step">
                            <p>Шаг 5. Получение печатного экземпляра</p>
                            <p>Как только книги будут напечатаны, на странице вашего издания в личном кабинете будет
                                доступна ссылка для отслеживания.
                                По умолчанию мы посылаем сборники Почтой России, но при необходимости готовы
                                использовать любую другую транспортную компанию.</p>
                        </div>
                    </div>
                </div>

                <div id="calculator" class="hide">

                    <div style="width: 60%; padding-bottom: 20px; padding-right: 50px;" class="calc-inputs">
                        <div id="inputs_for_height">


                            <div class="calc-row">
                                <p>Страниц в моей книге:</p>
                                <input min="0" value="1" id="pages"
                                       class="number-input" type="number">
                            </div>

                            <div class="calc-row">
                                <div style="display: flex; align-items: center; margin-top: 10px;">
                                    <p>Макет полностью готов?</p>
                                    <div style="margin-left: 10px;" class="switch-wrap">
                                        <input checked type="radio" id="inside_status_yes" name="inside_status"
                                               class="up-down">
                                        <label for="inside_status_yes">
                                            Да
                                        </label>

                                        <input type="radio" id="inside_status_no" value="show" name="inside_status"
                                               class="up-down">
                                        <label for="inside_status_no">
                                            Нет
                                        </label>
                                    </div>
                                    <span style="margin-left: 10px; display:flex;" class="tooltip"
                                          title="Макет можно считать готовым, если файл полностью подготовлен к общепринятым правилам издания. Никакая редактура не потребуется.">
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

                                <div style="margin-top: 10px; display: none" id="check_needed"
                                     class="inside_status check-block">
                                    <label for="textcheck_needed"><p style="margin:0;">Провера правописания</p></label>
                                    <input style="margin-left: 0;" id="textcheck_needed" type="checkbox">

                                    <label for="textdesign_needed"><p style="margin:0;">Дизайн текста</p></label>
                                    <input style="margin-left: 0;" id="textdesign_needed" type="checkbox">
                                </div>
                            </div>

                            <div class="calc-row no-wrap">
                                <p style="    display: flex; align-items: center; justify-content: space-between;">
                                    Обложка
                                <div style="margin-left: 10px;" class="switch-wrap">
                                    <input checked type="radio" value="cover_status_no" id="cover_status_no"
                                           name="cover_status"
                                           class="show-hide">
                                    <label for="cover_status_no">
                                        Нужна помощь
                                    </label>

                                    <input type="radio" id="cover_status_yes" value="cover_status_yes"
                                           name="cover_status"
                                           class="show-hide">
                                    <label for="cover_status_yes">
                                        Готовая
                                    </label>
                                </div>
                                </p>
                            </div>

                            <div id='print_block' class="calc-row ob-applic-block">
                                <div class="check-block">
                                    <label for="prints-needed"><p>Мне также необходимы печатные экземпляры</p></label>
                                    <input id="prints-needed" name="prints-needed" class="up-down" type="checkbox">
                                </div>
                                <div wire:ignore style="margin-top: 10px; display: none;"
                                     class="prints-needed ptint-block">

                                    <div style="margin-bottom: 7px;">
                                        <p>Стиль обложки:</p>
                                        <div style="margin-left: 10px;" class="switch-wrap">
                                            <input checked type="radio" value="cover_style_soft" id="cover_style_soft"
                                                   name="cover_style">
                                            <label for="cover_style_soft">
                                                мягкая
                                            </label>

                                            <input type="radio" value="cover_style_hard" id="cover_style_hard"
                                                   name="cover_style">
                                            <label for="cover_style_hard">
                                                твердая
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <p>Цветность обложки:</p>
                                        <div style="margin-left: 10px;" class="switch-wrap">
                                            <input checked type="radio" value="cover_color_yes" id="cover_color_yes"
                                                   name="cover_color">
                                            <label for="cover_color_yes">
                                                цветная
                                            </label>

                                            <input type="radio" value="cover_color_no" id="cover_color_no"
                                                   name="cover_color">
                                            <label for="cover_color_no">
                                                черно-белая
                                            </label>
                                        </div>
                                    </div>


                                    <div style="margin-top: 7px; margin-bottom: 7px;">
                                        <p>Цветность блока:</p>
                                        <div style="margin-left: 10px;" class="switch-wrap">
                                            <input checked type="radio" class="show-hide" value="inside_color_no"
                                                   id="inside_color_no"
                                                   name="color_pages">
                                            <label for="inside_color_no">
                                                черно-белый
                                            </label>

                                            <input type="radio" value="inside_color_yes" class="show-hide"
                                                   id="inside_color_yes"
                                                   name="color_pages">
                                            <label for="inside_color_yes">
                                                цветной
                                            </label>
                                        </div>
                                        <div
                                        <div style="display:inline-block;">
                                            <div style="display:none;" id="block_inside_color_yes" class="color_pages">
                                                <p>, цветных страниц: </p>
                                                <input id="color_pages"
                                                       style="width: 50px; font-size: 18px; height: 30px"
                                                       type="number">
                                            </div>
                                        </div>
                                    </div>

                                    <div style="flex-direction: row;     align-items: center;"
                                         class="participation-inputs-row">
                                        <p style="    width: 35%;">Количество экземпляров:</p>
                                        <label for="prints-num"></label><input
                                            style="max-width: 80px; margin-right: 40px;"
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
                                </div>
                            </div>

                            <div class="calc-row">
                                <div class="check-block">
                                    <label for="promo-needed"><p>Мне необходимо продвижение</p></label>
                                    <input id="promo-needed" name="promo-needed" class="up-down" type="checkbox">
                                </div>


                                <div style="display:none;" class="promo-needed">
                                    <div style="margin-top: 10px;" id="check_needed" class="check-block">
                                        <label for="promo_var_1"><p style="margin:0;">Вариант 1</p></label>
                                        <input checked value="500" style="margin-left: 0;" name="promo_input"
                                               id="promo_var_1"
                                               type="radio">
                                        <span style="display:flex;" class="tooltip"
                                              title="Разместить в блоке 'Наши авторы'">
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

                                    <div style="margin-top: 10px;" id="check_needed" class="check-block">
                                        <label for="promo_var_2"><p style="margin:0;">Вариант 2</p></label>
                                        <input value="2000" style="margin-left: 0;" name="promo_input" id="promo_var_2"
                                               type="radio">
                                        <span style="display:flex;" class="tooltip"
                                              title="Бессрочное размещение на сайте и в соц. сетях.">
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
                        {{----------- // БЛОК ПЕЧАТИ -----------}}

                    </div>

                    <div style="width: 40%;" wire:ignore class="participation-outputs">
                        <div id="prices_for_height">
                            <div id="price-parts-wrap">
                                <div class="participation-price">
                                    <h1 id="layout_work_price_price">300</h1>
                                    <h1>&nbsp;руб.</h1>
                                    <div class="participation-price-desc">
                                        <div>
                                            <p>работа с макетом (</p>
                                            <p id="pages_output">0</p>
                                            <p>&nbsp;стр.)</p>
                                        </div>

                                        <p style="display: none; line-height: 20px; font-size: 20px;"
                                           class="inside_status">
                                            <i>Включая:</i></p>
                                        <p style="display: none; line-height: 20px; font-size: 20px; margin-right: auto;"
                                           class="inside_status"><i>Проверка
                                                правописания: <span id="text_check_price">123</span> руб.</i></p>
                                        <p style="display: none; line-height: 25px; font-size: 20px; margin-right: auto;"
                                           class="inside_status"><i>Дизайн
                                                текста: <span id="text_design_price">123</span> руб.</i></p>

                                    </div>
                                </div>
                                <div class="participation-price">
                                    <h1 id="participation_price">500</h1>
                                    <h1>&nbsp;руб.</h1>
                                    <div class="participation-price-desc">
                                        <p>присвоение ISBN</p>
                                        </p>
                                    </div>
                                </div>
                                <div style="display: none" id="print-price" class="prints-needed participation-price">
                                    <h1 id="print_price">300</h1>
                                    <h1>&nbsp;руб.</h1>
                                    <div class="participation-price-desc">
                                        <div></div>
                                        <p>За печать (<span id="print_needed">1</span>&nbsp;экз.)</p></div>
                                </div>

                                <div id="cover-price-total" class="cover-needed participation-price">
                                    <h1 id="cover_price">1500</h1>
                                    <h1>руб.</h1>
                                    <div class="participation-price-desc">
                                        <div></div>
                                        <p>создание обложки</p></div>
                                </div>


                                <div style="display: none" id="promo-needed" class="promo-needed participation-price">
                                    <h1 id="promo_price">0</h1>
                                    <h1> руб.</h1>
                                    <div class="participation-price-desc">
                                        <div></div>
                                        <p>Продвижение (вар.:&nbsp;<span id="promo_var_num"></span>)</p></div>
                                </div>

                            </div>

                            <div id="price-total-wrap">
                                <div style="margin-top: 0;" class="total_price participation-price">
                                    <h1 id="total_price">800</h1>
                                    <h1>&nbsp;руб.</h1>
                                    <div class="participation-price-desc"><p>Итог</p></div>
                                </div>
                            </div>
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
    {{-------- Авто функции скрытия - показ блоков ---------}}
    <script>

        $('.show-hide').on('click', function () {
            $('.' + $(this).attr('name')).each(function () {
                $(this).hide();
            })

            $('#block_' + $(this).attr('id')).toggle();
        })

        $('.up-down').on('change', function () {

            old_prices_inside_height = $('#prices_for_height').innerHeight();
            old_prices_outside_height = $('.participation-outputs').innerHeight();

            old_inputs_outside_height = $('.calc-inputs').innerHeight();
            old_inputs_outside_height = $('#inputs_for_height').innerHeight();

            if ($(this).val() === 'show' || $(this).prop('checked') & $(this).prop('type') === 'checkbox') {

                input_adding_block = $('.' + $(this).attr('name'));


                // Быстро показать блок, проверить высоты, скрыть блок
                input_adding_block.show();

                new_prices_inside_height = $('#prices_for_height').innerHeight();
                new_prices_outside_height = $('.participation-outputs').innerHeight();

                new_inputs_inside_height = $('#inputs_for_height').innerHeight();
                new_inputs_outside_height = $('.calc-inputs').innerHeight();

                input_adding_block.hide();
                // -------------------------------------------


                // Медленно показать блок
                input_adding_block.slideDown('200');


                // Изменить высоту контейнера
                $('.list-wrap').css('transition', 'all .1s linear');
                $('.list-wrap').animate({
                    height: Math.max(new_inputs_outside_height, new_prices_outside_height)
                }, function () {
                    $('.list-wrap').css('transition', '');
                });


            } else {

                input_adding_block = $('.' + $(this).attr('name'));

                // Быстро показать блок, проверить высоты, скрыть блок
                input_adding_block.hide();

                new_prices_inside_height = $('#prices_for_height').innerHeight();
                new_prices_outside_height = $('.participation-outputs').innerHeight();

                new_inputs_inside_height = $('#inputs_for_height').innerHeight();
                new_inputs_outside_height = $('.calc-inputs').innerHeight();

                input_adding_block.show();
                // -------------------------------------------

                // Медленно скрыть блок
                $('.' + $(this).attr('name')).slideUp();

                // Изменить высоту контейнера
                $('.list-wrap').css('transition', 'all .1s linear');
                $('.list-wrap').animate({
                    height: Math.max(new_inputs_outside_height, new_prices_outside_height)
                }, function () {
                    $('.list-wrap').css('transition', '');
                });

            }


            // if ($(this).val() === 'show' || $(this).prop('checked') & $(this).prop('type') === 'checkbox') {
            //     $('.' + $(this).attr('name')).show();
            //     var height = $('.' + $(this).attr('name')).innerHeight();
            //
            //     $('.' + $(this).attr('name')).slideDown('200')
            //
            //     new_block_height = $('#prices_for_height').innerHeight();
            //
            //     if (new_block_height != old_block_height) {
            //         $('.list-wrap').css('transition', 'all .1s linear');
            //         $('.list-wrap').animate({
            //             height: $('.participation-outputs').innerHeight() + height
            //         }, function () {
            //             $('.list-wrap').css('transition', '');
            //         });
            //     }
            //
            //
            // } else {
            //     var height = $('.' + $(this).attr('name')).innerHeight();
            //     // alert(height);
            //     $('.' + $(this).attr('name')).slideUp()
            //
            //     new_block_height = $('#prices_for_height').innerHeight();
            //
            //     if (new_block_height != old_block_height) {
            //
            //         $('.list-wrap').css('transition', 'all .1s linear');
            //         $('.list-wrap').animate({
            //             height: $('.participation-outputs').innerHeight() - height
            //         }, function () {
            //             $('.list-wrap').css('transition', '');
            //         });
            //     }
            // }
        })

    </script>
{{--    -----------------// Авто функции скрытия - показ блоков-----------------------}}

    {{--SLICK SLIDER--}}
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
    </script>
    {{-- //cSLICK SLIDER--}}


    <script src="/js/make_own_book_prices.js"></script>

    <script>

        {{-- Манипуляции страницы и расчёт цен --}}
        function calculation() {

            // GET ALL VALUES
            pages = $('#pages').val();
            print_needed = $('#prints-num').val();

            // ГЛАВНЫЙ РАСЧЁТ ЦЕН
            make_own_book_prices();

            $('#pages_output').html(pages);
            $('#text_design_price').html(parseInt(text_design_price).toLocaleString());
            $('#text_check_price').html(parseInt(text_check_price).toLocaleString());
            $('#layout_work_price_price').html(parseInt(text_design_price + text_check_price + 300).toLocaleString());
            $('#print_needed').html(print_needed);
            $('#print_price').html(parseInt(Math.round(print_price)).toLocaleString());
            $('#promo_price').html(parseInt(promo_price).toLocaleString());
            $('#promo_var_num').html(promo_var_num);
            $('#total_price').html(parseInt(total_price).toLocaleString());
        }

    </script>

    {{-------------------------------Slider-----------------------------------------------}}
    <script>


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

    </script>

    <script>

        $('.number-input').keyup(function () {
            calculation()
        })

        $('select, input').on('input', function () {
            calculation()
        })
    </script>

@endsection
