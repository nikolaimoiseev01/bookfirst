@extends('layouts.portal_layout')

@section('page-title')
    Привлечение читателей
@endsection

@section('page-style')
    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection

@section('content')
    <div class="page_content_wrap collection_page_wrap own_book_page_wrap">
        <div class="content">

            <div class="collection-block">
                <div>
                    <img class="cover" src="/img/own_book_example_cover.png" alt="">
                </div>
                <div class="right-collection-info">
                    <div class="col-text">
                        <h3>Привлечение читателей на других сайтах</h3>
                        <p>Кроме составления различных литературных сборников и издания книг мы также предлагаем необычную услугу:
                            продвижение вашего творчества на больших порталаха.
                            Мы в разы увеличиваем количество посетителей ваших страниц.
                            Данная услуга - это не литературное продвижение или раскрутка вашего творчества. Это только расширение аудитории для Ваших произведений, реклама ваших творений. Всё остальное зависит лишь от вас и ваших произведений!
                        </p>
                    </div>
                    <div class="col-card">
                        <div class="container">
                            <div class="row">
                                Сайты для продвижения:&nbsp;<span>stihi.ru, proza.ru, chitalnya.ru, poembook.ru</span>
                            </div>
                            <div class="row">
                                "Возраст" аккаунта:&nbsp;<span>от 2-х недель</span>
                            </div>
                            <div class="row">
                                Новых читателей:&nbsp;<span>до <b>500</b> в сутки</span>
                            </div>
                            <div class="row">
                                <a style="    font-size: 25px; padding: 3px 35px;" href="{{route('make_ext_promotion')}}"
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
                    <a href="#process" class="cont_nav_item current">Порядок получения услуги</a>
                    <a href="#calculator" class="cont_nav_item">Калькулятор продвижения</a>
                    <a style="float: right;" href="{{route('help_own_book')}}" target="_blank">Инструкция</a>
                </div>
                <div style="" class="list-wrap">

                    <div id="process" class="process">
                        <div class="process-slider">
                            <div class="step">
                                <p>Шаг 1. Заполнение заявки</p>
                                <p> Нажмите "Подать заявку", чтобы начать процесс продвижения.
                                    При заполнении нужно будет указать конкретный сайт, на котором планируется продвижение.
                                    <br>
                                    <i style="color: #47AF98">Оплата производится только после подтверждения нашей готовности продвигать вашу страницу!</i>
                                </p>
                            </div>
                            <div class="step">
                                <p>Страница продвижения</p>
                                <p> Сразу после заполнения заявки, Вы будете перенаправлены на отдельную страницу
                                    вашего конкретного продвижения в личном кабинете.
                                    На ней Вы сможете отслеживать статусы процесса, а также
                                    любые изменения.
                                    Так же на ней будет доступен чат с Вашим личным менеджером на случай каких-либо
                                    вопросов.
                                </p>
                            </div>
                            <div class="step">
                                <p>Шаг 2. Ожидание подтверждения</p>
                                <p>После того, как заявка была отправлена, мы должны проверить ваш аккаунт.
                                    В нем не должно быть призывов к терроризму или иного запрещенного контента.
                                    Срок проверки - 3 рабочих дня.
                                    В случае отказа Вы получите подробную информацию о причинах нашего решения.
                                    Сразу после нашего подтверждения заявки, Вы получите оповещения (Email в том числе)
                                    о необходимости оплаты услуги.</p>
                            </div>
                            <div class="step">
                                <p>Шаг 3. Оплата продвижения</p>
                                <p>После нашей проверки на странице продвижения в личном кабинете будет доступна форма
                                    оплаты.
                                    Ее можно будет произвести через одну из многочисленных платежных систем
                                    (Оплата любой картой, Yandex money, Western Union, PayPal и другие).
                                </p>
                            </div>
                            <div class="step">
                                <p>Шаг 4. Отслеживание процесса</p>
                                <p>
                                    После оплаты продвижение запускается в течение суток. Как только оно запустится, вы получите оповещения,
                                    а на странице процесса будет вся информация: статистика, оставшиеся дни и т.д.
                                </p>
                            </div>
                            <div class="step">
                                <p>Шаг 5. Продление услуги</p>
                                <p>
                                    Как только оплаченный период подойдет к концу, продвижение автоматически остановится.
                                    Его можно будет продлить на странице процесса.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="calculator" class="hide">
                        @livewire('portal.ext-promotion-calc')
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
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


    <script src="/js/col-info-block.js"></script>

@endpush
