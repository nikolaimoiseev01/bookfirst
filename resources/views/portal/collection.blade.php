@extends('layouts.portal_layout')

@section('page-title')
    {{$collection['title']}}
@endsection

@section('page-style')
    {{--    <link rel="stylesheet" href="/css/collection-page.css">--}}
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
    <div class="page_content_wrap collection_page_wrap">
        <div class="header content">
            <div class="bread">
                <a href="{{route('actual_collections')}}"><p>Сборники</p></a> / <p>{{$collection['title']}}</p>
            </div>
            <div class="collection-block">
                <div style="display: flex; align-items: center;">
                    <img class="cover" src="{{config('app.url') . '/' . $collection['cover_3d']}}" alt="">
                </div>
                <div class="right-collection-info">
                    <div class="col-text">
                        <h3 style="border-bottom: 1px #4C4B46 solid;">{{$collection['title']}}</h3>
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
                                Формат:&nbsp;<span>
                                @if(strpos($collection['title'], 'Дух') !== false)Стихи
                                    @elseif(strpos($collection['title'], 'Мысли') !== false)Проза
                                    @elseif(strpos($collection['title'], 'Гарри Поттер') !== false)Проза/стихи
                                    @else
                                        любой тематики
                                    @endif
                            </span>
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
                    <a href="#process" class="cont_nav_item current">Порядок участия</a>
                    <a href="#calculator" class="cont_nav_item">Калькулятор</a>
                    <a href="#dates" class="cont_nav_item">Даты издания</a>
                    <a href="#contest" class="cont_nav_item" style="color: #f79e9e;">Бесплатное участие!</a>
                    <a style="float: right;" href="{{route('help_collection')}}" target="_blank">Инструкция</a>
                </div>
                <div style="transition: .3s ease-in-out" class="list-wrap">

                    <div id="process" class="process">
                        <div class="process-slider">
                            <div class="step">
                                <p>Шаг 1. Заполнение заявки</p>
                                <p> Нажмите "принять участие", чтобы заполнить заявку.
                                    При заполнении можно сразу указать необходимое количество печатных экземпляров.
                                    Все права на произведения всегда остаются строго за автором.
                                    <br><i>Оплата производится только после подтверждения нашей готовности включить Вас
                                        в
                                        сборник!</i>
                                </p>
                            </div>
                            <div class="step">
                                <p>Страница участия</p>
                                <p> Сразу после заполнения заявки, Вы будете перенаправлены на отдельную страницу
                                    конкретно
                                    Вашего участия в личном кабинете.
                                    Это главная страница участия, на ней Вы сможете отслеживать весь процесс издания.
                                    Так же на ней будет доступен чат с поддержкой на случай каких-либо вопросов.
                                </p>
                            </div>
                            <div class="step">
                                <p>Шаг 2. Ожидание подтверждения</p>
                                <p>После того, как заявка была отправлена, произведения проходят цензуру.
                                    В них не должно быть призывов к терроризму или иного запрещенного контента.
                                    Сразу после нашего подтверждения заявки, Вы получите оповещения (Email в том числе)
                                    о
                                    необходимости оплаты.</p>
                            </div>
                            <div class="step">
                                <p>Шаг 3. Оплата участия</p>
                                <p>После нашей проверки на странице участия в личном кабинете будет доступна форма оплаты.
                                    Ее можно будет произвести через одну из многочисленных платежных систем.
                                    Если у Вас нет счета в банке РФ, можно сделать прямой перевод по нашим иностранным реквизитам (банки Европы и Казахстана).
                                </p>
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
                                <p>Если вы заказывали печатные экземпляры, то <a
                                        class="triger_dates link">в
                                        указанную дату</a> на странице участия будет доступна
                                    ссылка для отслеживания.
                                    <span style="color: #47af98">На этом этапе отдельно оплачивается фактическая стоимость пересылки.</span>
                                    Обычно мы посылаем сборники Почтой России, но при необходимости готовы
                                    использовать любую другую транспортную компанию.</p>
                            </div>
                        </div>
                    </div>
                    <div id="contest" class="champ_block_wrap hide">
                        <h4 class="title">Объявлен
                            <span style="color: #47af98;">
                                <b>КОНКУРС</b>
                            </span>
                            среди участников сборника!
                        </h4>
                        <p>Участие в данном сборнике может быть бесплатным именно для Вас! <br>
                        <div class="desc_wrap">
                            <div>
                                <h4>Правила конкурса:</h4>
                                <p> Каждый включенный в сборник автор автоматически становится участником конкурса. (<a
                                        class="triger_process link">порядок участия</a>).
                                    В период предварительной проверки авторам предоставляется возможность проголосовать
                                    за понравившиеся произведения.
                                    Опираясь на голоса авторов, наша команда подводит итоги конкурса и объявляет
                                    победителей в <a href="https://vk.com/yourfirstbook" class="link">нашей группе
                                        ВК</a>
                                </p>
                            </div>
                            <div>
                                <h4>Призы:</h4>

                                <p><b style="color: #47af98">1 место:</b> Бесплатное участие, печатный экземпляр
                                    сборника и пересылка</p>

                                <p><b style="color: #47af98">2 место:</b> Половина стоимости участия и 50% промокод для
                                    участия в следующем сборнике</p>

                                <p><b style="color: #47af98">3 место:</b> Бесплатный печатный экземпляр и пересылка</p>
                                </p>
                            </div>
                        </div>
                        <p class="desc_details">
                            *Подробная информация о правилах получения
                                будет предоставлена призеру лично.
                        </p>
                    </div>
                    <div id="calculator" class="hide">
                        @livewire('portal.col-part-calc')
                    </div>
                    <div id="dates" class="hide">
                        <div class="dates-wrap">
                            <div class="date-block">
                                <h4>{{ Date::parse($collection['col_date1'])->format('j F') }}</h4>
                                <p>Конец приема заявок</p>
                                <x-question-mark>
                                    Прием заявок заканчивается в 23:59 МСК указанного дня
                                </x-question-mark>
                            </div>
                            <div class="date-block">
                                <h4>{{ Date::parse($collection['col_date2'])->format('j F') }}</h4>
                                <p>Отправка предварительного варианта сборника</p>
                                <x-question-mark>
                                    До 23:59 МСК указанного дня в вашем личном кабинете будет доступно скачивание предварительного экземпляра сборника, а также форма указания исправлений
                                </x-question-mark>

                            </div>
                            <div class="date-block">
                                <h4>{{ Date::parse($collection['col_date3'])->format('j F') }}</h4>
                                <p>Отправка сборника в печать</p>
                            </div>
                            <div class="date-block">
                                <h4>{{ Date::parse($collection['col_date4'])->format('j F') }}</h4>
                                <p>Отправка экземпляров авторам</p>
                                <x-question-mark>
                                    После отправки печатных экземпляров в вашем личном кабинете будет доступна ссылка для отслеживания посылки.
                                </x-question-mark>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
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

        $('.triger_process').on('click', function () {
            $('a[href$="#process"]').trigger('click');
        })
    </script>

@endpush
