@extends('layouts.app')

@section('page-style')

@endsection

@section('page-tab-title')
    Страница издания
@endsection

@section('page-title')
    <div class="account-header">
        <h1>{{$own_book['author']}}: {{$own_book['title']}}</h1>
    </div>
@endsection

@section('content')
    @php
        $part_not_available = "#cbcbcb";
        $part_action_needed="#ffa500";
        $part_all_good="#47AF98";
    @endphp

    {{------- Стили для иконки успешного блока -------}}
    <defs>
        <style>
            .cls-11 {
                fill: #fff;
                stroke: {{$part_all_good}};
                stroke-miterlimit: 10;
                stroke-width: 5px;
            }

            .cls-22 {
                fill: none;
                stroke: {{$part_all_good}};
                stroke-linecap: round;
                stroke-linejoin: round;
                stroke-width: 11px;
            }
        </style>
    </defs>
    {{------- // Стили для иконки успешного блока -------}}





    {{-- Общая информация о книге--}}
    <div class="participation-wrapper">
        <div style="    display: flex; flex-direction: column;">
            <div style="float: left">
                <p>Общий статус: <b><i>{{$own_book->own_book_status['status_title']}}</i></b></p>
            </div>
            <div style="float: left">
                <p>Статус обложки: <b><i>{{$own_book->own_book_cover_status['status_title']}}</i></b></p>
            </div>
            <div style="float: left">
                <p>Статус ВБ: <b><i>{{$own_book->own_book_inside_status['status_title']}}</i></b></p>
            </div>
        </div>

        <div style="float: right">

            <div>
                <div class="legend-row">
                    <div style="background:{{$part_not_available}}" class="legend-circle"></div>
                    <p style="font-size: 19px">Пункт недоступен</p>
                </div>
                <div class="legend-row">
                    <div style="background:{{$part_action_needed}}" class="legend-circle"></div>
                    <p style="font-size: 19px">Необходимо действие</p>
                </div>
                <div class="legend-row">
                    <div style="background:{{$part_all_good}}" class="legend-circle"></div>
                    <p style="font-size: 19px">Успешно выполнено</p>
                </div>
            </div>

        </div>


    </div>
    {{-- // Общая информация о книге--}}

    <a id="chat_button" style="margin-left: 30px; margin-top: 20px; width: 95%; text-align: center; max-width: 1000px;" class="button">Развернуть чат</a>
    <div class="participation-wrap">

        {{-- Чат книги --}}
        <div id="book_chat" style="display: none; margin: 0 0 30px 0; width: 100%; max-width: 1000px;" class="chat">
            <div style="margin: 0; width: 100%; max-width: 1000px;" class="container">
                @livewire('chat',['chat_id'=>$chat_id])
            </div>
        </div>
        {{-- // Чат книги --}}

        {{-- БЛОК ИНФОРМАЦИИ О ЗАЯВКЕ --}}
        <div class="part"
             style="padding-top: 25px;
                 border-top: 2px {{$part_all_good}} solid;
                 border-left: 2px {{$part_all_good}} solid;
                 border-right: 2px {{$part_all_good}} solid;
                 border-radius: 10px 10px 0 0;
                 ">
            <div style="background: {{$part_all_good}};" class="line"></div>
            <svg id="Capa_1" class="circle_status" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 234.15 234.15">
                <circle class="cls-11" cx="117.08" cy="117.08" r="114.58"/>
                <polyline class="cls-22" points="50.03 111.7 108.85 192.66 184.12 41.49"/>
            </svg>
            <div style="box-shadow: 0 0 10px 1px {{$part_all_good}}85;" class="container">
                <div style="border-bottom: 1px #47AF98 solid" class=hero>
                    <h2 style="color: {{$part_all_good}};">Моя заявка</h2>
                </div>
                <div class="info">
                    <div class="part_part">
                        <h2>Участие:</h2>
                        <span><p style="margin: 0;">Автор: <i>{{$own_book['author']}}</i></p></span>
                        <span><p style="margin: 0;">Название: <i>{{$own_book['title']}}</i></p></span>
                        @if($own_book['nickname'] <> "")
                            <span><p style="margin: 0;">Псевдоним: <i>{{$own_book['nickname']}}</i></p></span>
                        @endif
                        <span><p style="margin: 0;">Страниц: <i>{{$own_book['pages']}}</i></p></span>
                    </div>
                    <div class="print_part">
                        <h2>Печатные экземпляры:</h2>
                        @if($own_book['print_price'] ?? 0 > 0)
                            <span><p
                                    style="margin: 0;">Печатных экземпляров: {{$own_book->printorder['books_needed']}}</p></span>
                            <span><p
                                    style="margin: 0;">ФИО Адресата: {{$own_book->printorder['send_to_name']}}</p></span>

                            <span><p style="margin: 0;">Телефон: {{$own_book->printorder['send_to_tel']}}</p></span>
                        @else
                            <p>Печатные эезкемпляры не требуются.</p>
                            <a href="#part_print" class="link">Создать заказ</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- // БЛОК ИНФОРМАЦИИ О ЗАЯВКЕ --}}

        {{-- БЛОК ОПЛАТЫ--}}
        <div class="own_book part"
             style="border-left: 2px
             @if ($own_book['own_book_status_id'] === 1)
             {{$part_not_available}}
             @elseif ($own_book['own_book_status_id'] === 2)
             {{$part_action_needed}}
             @else
             {{$part_all_good}}
             @endif solid;
                 border-right: 2px
             @if ($own_book['own_book_status_id'] === 1)
             {{$part_not_available}}
             @elseif ($own_book['own_book_status_id'] === 2)
             {{$part_action_needed}}
             @else
             {{$part_all_good}}
             @endif solid;
                 ">
            <div style="background:
            @if ($own_book['own_book_status_id'] === 1)
            {{$part_not_available}}
            @elseif ($own_book['own_book_status_id'] === 2)
            {{$part_action_needed}}
            @else
            {{$part_all_good}}
            @endif
                " class="line"></div>

            @if($own_book['own_book_status_id'] <= 2)
                <svg style="fill:
            @if ($own_book['own_book_status_id'] === 1)
                {{$part_not_available}}
                @elseif ($own_book['own_book_status_id'] === 2)
                {{$part_action_needed}}
                @else
                {{$part_all_good}}
                @endif" id="Слой_1" class="circle_status" data-name="Слой 1"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496.52 516.53">
                    <defs>
                        <style>.cls-1 {
                                fill: none;
                                stroke: #000;
                                stroke-miterlimit: 10;
                                stroke-width: 14px;
                            }</style>
                    </defs>
                    <path
                        d="M307,193.9a36.83,36.83,0,0,0,12.6-27.7V142.7a14.72,14.72,0,0,0,14.7-14.7V113.3a14.72,14.72,0,0,0-14.7-14.7H157.45a14.72,14.72,0,0,0-14.7,14.7V128a14.72,14.72,0,0,0,14.7,14.7v23.5a36.83,36.83,0,0,0,12.6,27.7l46.4,40.6v8l-46.4,40.6a36.83,36.83,0,0,0-12.6,27.7v23.5a14.72,14.72,0,0,0-14.7,14.7v14.7a14.72,14.72,0,0,0,14.7,14.7h162.1a14.72,14.72,0,0,0,14.7-14.7V349a14.72,14.72,0,0,0-14.7-14.7V310.8A36.83,36.83,0,0,0,307,283.1l-46.4-40.6v-8Zm-149.5-80.7h162.1v14.7H157.45Zm14.8,29.5h132.6v23.5a22.51,22.51,0,0,1-4.5,13.4H176.75a22.07,22.07,0,0,1-4.5-13.4Zm147.3,221H157.45V349h162.1ZM251,253.6l46.4,40.6a22.55,22.55,0,0,1,7.6,16.6v23.5H172.25V310.8a22,22,0,0,1,7.6-16.6l46.4-40.6a14.64,14.64,0,0,0,5-11.1v-8a14.64,14.64,0,0,0-5-11.1l-33.2-29.1h91.3l-33.2,29.1a14.74,14.74,0,0,0-5.1,11.1v8A14.22,14.22,0,0,0,251,253.6Z"
                        transform="translate(11.27 33.78)"/>
                    <path d="M231.15,260.6h14.7v14.7h-14.7Z" transform="translate(11.27 33.78)"/>
                    <path d="M231.15,290.1h14.7v14.7h-14.7Z" transform="translate(11.27 33.78)"/>
                    <path class="cls-1" d="M531.26,200.22" transform="translate(11.27 33.78)"/>
                    <path
                        d="M465.53,193.88C486.61,335.72,377,460.38,245.19,465.41,128.72,469.86,15.94,379.16,7,253.13-1.7,130,92.07,19.61,217.17,8.48l-19.92,32L211,51.67,266.79,8.38a17.48,17.48,0,0,0-1.88-13.73L203.27-33.78,191.78-20.46l15,15.37C76.05,11-19.5,127.2-10.71,255.29c9.81,143.09,144.82,228.23,257,227.45,89.76-.62,150.58-56.15,161.56-66.52,93.26-88.14,77-213.4,75.16-225.91Z"
                        transform="translate(11.27 33.78)"/>
                    <path
                        d="M456.05,161.93l16.73-6c-2.12-5.53-4.46-11.16-7-16.58l-16.28,7.26C451.81,151.53,454,156.73,456.05,161.93Z"
                        transform="translate(11.27 33.78)"/>
                    <path d="M352.8,24.49c-5.47-2.71-11.16-5.31-16.73-7.58l-7,15.92c5.25,2.16,10.49,4.55,15.62,7.15Z"
                          transform="translate(11.27 33.78)"/>
                    <path
                        d="M465.53,193.88l17.5-3.57c-.61-2.86-1.3-5.79-2.06-8.76A228.55,228.55,0,0,0,472.78,156l-16.73,6a231.06,231.06,0,0,1,7.54,23.83C464.3,188.51,464.94,191.22,465.53,193.88Z"
                        transform="translate(11.27 33.78)"/>
                    <path
                        d="M423,103.66l14.17-10.5c-3.68-4.77-7.7-9.43-11.71-13.87L412.12,90.77C415.91,95,419.59,99.33,423,103.66Z"
                        transform="translate(11.27 33.78)"/>
                    <path
                        d="M441.78,131.61l15.61-8.34c-3-5.2-6.14-10.4-9.48-15.38L433,117.31C436,122,439,126.73,441.78,131.61Z"
                        transform="translate(11.27 33.78)"/>
                    <path d="M318.79,10.41C312.88,8.46,307,6.73,301.06,5.32L296.6,22.1c5.46,1.41,10.92,2.93,16.5,4.77Z"
                          transform="translate(11.27 33.78)"/>
                    <path d="M384.35,43.23c-5-3.58-10.26-6.83-15.39-10L359.6,48c4.79,2.82,9.59,6,14.27,9.21Z"
                          transform="translate(11.27 33.78)"/>
                    <path d="M412.67,66.3c-4.34-4.12-9-8.24-13.71-12L387.47,67.49c4.35,3.57,8.7,7.36,12.72,11.15Z"
                          transform="translate(11.27 33.78)"/>
                </svg>
            @else
                <svg id="Capa_1" class="circle_status" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 234.15 234.15">
                    <circle class="cls-11" cx="117.08" cy="117.08" r="114.58"/>
                    <polyline class="cls-22" points="50.03 111.7 108.85 192.66 184.12 41.49"/>
                </svg>
            @endif
            <div style="
            @if ($own_book['own_book_status_id'] === 1)

            @elseif ($own_book['own_book_status_id'] === 2)
                box-shadow: 0 0 10px 1px {{$part_action_needed}}36
            @else
                box-shadow: 0 0 10px 1px {{$part_all_good}}85;
            @endif" class="container">
                <div style="border-bottom: 1px
                @if ($own_book['own_book_status_id'] === 1)
                {{$part_not_available}}
                @elseif ($own_book['own_book_status_id'] === 2)
                {{$part_action_needed}}
                @else
                {{$part_all_good}}
                @endif solid" class=hero>
                    <h2 style="color:
                    @if ($own_book['own_book_status_id'] === 1)
                    {{$part_not_available}}
                    @elseif ($own_book['own_book_status_id'] === 2)
                    {{$part_action_needed}}
                    @else
                    {{$part_all_good}}
                    @endif;">
                        @if ($own_book['own_book_status_id'] <= 2)
                            Оплата участия
                        @else
                            Оплата успешно принята!
                        @endif
                    </h2>
                </div>

                @if ($own_book['own_book_status_id'] === 1)
                    <div class="no-access">
                        <span style="font-size: 30px;">После создания или редактирования заявки нам необходимо ее подтвердить.
                            Оплата станет доступна сразу после подтверждения Вашей заявки.
                        </span>
                    </div>
                @elseif ($own_book['own_book_status_id'] === 2)
                    <div style="display: flex;">
                        <div
                            style="width:50%; flex-direction: column; display: flex; justify-content: center; text-align: center;"
                            class="payment-info">
                            <p style="padding: 15px;">Отлично, Ваша заявка подтверждена!Для начала издания необходимо
                                произвести оплату.</p>
                            <form style="display:inline-block"
                                  action="{{ route('pay_for_own_book',$own_book['id']) }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <input value="{{$own_book['id']}}" type="text" name="own_book_status_id"
                                       style="display:none" class="form-control"
                                       id="own_book_status_id">
                                <button id="btn-submit" type="submit" style="height: fit-content; max-width:250px;"
                                        class="pay-button button">
                                    Оплатить
                                </button>
                            </form>
                        </div>

                        <div style="padding: 10px; width:90%;" class="participation-outputs">
                            <div style="display: flex; flex-wrap:wrap; justify-content: space-evenly;">
                                <div class="participation-price">
                                    <h1 id="layout_work_price_price">{{ 300 + $own_book['text_design_price'] +  $own_book['text_check_price']}}</h1>
                                    <h1>&nbsp;руб.</h1>
                                    <div class="participation-price-desc">
                                        <div>
                                            <p>работа с макетом</p>
                                        </div>
                                        @if($own_book['text_check_price'] > 0 || $own_book['text_design_price'] > 0)
                                            <div style="display: flex; flex-direction: column;">
                                                <p style="    margin: auto; line-height: 20px; font-size: 20px;"
                                                   class="inside_status">
                                                    <i>Включая:</i></p>
                                                @if($own_book['text_check_price'] > 0)
                                                    <p style="    margin: auto; line-height: 20px; font-size: 20px; margin-right: auto;"
                                                       class="inside_status"><i>Проверка
                                                            правописания: {{$own_book['text_check_price']}} руб.</i></p>
                                                @endif
                                                @if($own_book['text_design_price'] > 0)
                                                    <p style="line-height: 25px; font-size: 20px; margin-right: auto;"
                                                       class="inside_status">
                                                        <i>Дизайн {{$own_book['text_design_price']}} руб.</i></p>
                                                @endif
                                            </div>
                                        @endif

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

                                @if($own_book['cover_price'] > 0)
                                    <div id="cover-price-total" class="cover-needed participation-price">
                                        <h1 id="cover_price">{{$own_book['cover_price']}}</h1>
                                        <h1>руб.</h1>
                                        <div class="participation-price-desc">
                                            <div></div>
                                            <p>создание обложки</p></div>
                                    </div>
                                @endif

                                @if($own_book['promo_price'] > 0)
                                    <div id="promo-needed" class="promo-needed participation-price">
                                        <h1 id="promo_price">{{$own_book['promo_price']}}</h1>
                                        <h1> руб.</h1>
                                        <div class="participation-price-desc">
                                            <div></div>
                                            <p>Продвижение (вар.:&nbsp;<span id="promo_var_num"></span>)</p></div>
                                    </div>
                                @endif

                                @if($own_book['print_price'] > 0)
                                    <div class="participation-price">
                                        <h1 style="color: #dfdfdf !important; font-size: 38px;">{{$own_book['print_price']}}
                                            руб.</h1>
                                        <div class="participation-price-desc">
                                            <p style="color: #dfdfdf !important; font-size: 23px;">За печать
                                                ({{$own_book->printorder['books_needed']}} экз.)</p>
                                        </div>
                                    </div>
                                @endif

                                <div style="margin-top: 20px;" class="participation-price">
                                    <h1 style="color:{{$part_action_needed}} !important;">{{$own_book['total_price'] - $own_book['print_price']}}</h1>
                                    <h1 style="color:{{$part_action_needed}} !important;"> руб.</h1>
                                    <p style="color:{{$part_action_needed}} !important;">Итого*</p>
                                </div>
                            </div>
                            <p><i>*На данном этапе оплата производится за все услуги, исключая печать, так как цена
                                    печати может измениться после утверждения макетов.</i></p>

                        </div>
                    </div>
                @else
                    <div style="display: flex;">
                        <div style="padding: 10px; width:100%;" class="participation-outputs">
                            <div style="display: flex; flex-wrap:wrap; justify-content: space-evenly;">
                                <div class="participation-price">
                                    <h1 id="layout_work_price_price">{{ 300 + $own_book['text_design_price'] +  $own_book['text_check_price']}}</h1>
                                    <h1>&nbsp;руб.</h1>
                                    <div class="participation-price-desc">
                                        <div>
                                            <p>работа с макетом</p>
                                        </div>
                                        @if($own_book['text_check_price'] > 0 || $own_book['text_design_price'] > 0)
                                            <div style="display: flex; flex-direction: column;">
                                                <p style="line-height: 20px; font-size: 20px;" class="inside_status">
                                                    <i>Включая:</i></p>
                                                @if($own_book['text_check_price'] > 0)
                                                    <p style="    margin: auto; line-height: 20px; font-size: 20px; margin-right: auto;"
                                                       class="inside_status"><i>Проверка
                                                            правописания: {{$own_book['text_check_price']}} руб.</i></p>
                                                @endif
                                                @if($own_book['text_design_price'] > 0)
                                                    <p style="    margin: auto; line-height: 25px; font-size: 20px; margin-right: auto;"
                                                       class="inside_status">
                                                        <i>Дизайн {{$own_book['text_design_price']}} руб.</i></p>
                                                @endif
                                            </div>
                                        @endif

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

                                @if($own_book['cover_price'] > 0)
                                    <div id="cover-price-total" class="cover-needed participation-price">
                                        <h1 id="cover_price">{{$own_book['cover_price']}}</h1>
                                        <h1>руб.</h1>
                                        <div class="participation-price-desc">
                                            <div></div>
                                            <p>создание обложки</p></div>
                                    </div>
                                @endif

                                @if($own_book['promo_price'] > 0)
                                    <div id="promo-needed" class="promo-needed participation-price">
                                        <h1 id="promo_price">{{$own_book['promo_price']}}</h1>
                                        <h1> руб.</h1>
                                        <div class="participation-price-desc">
                                            <div></div>
                                            <p>Продвижение (вар.:&nbsp;<span id="promo_var_num"></span>)</p></div>
                                    </div>
                                @endif

                                @if($own_book['print_price'] > 0)
                                    <div class="participation-price">
                                        <h1 style="color: #dfdfdf !important; font-size: 38px;">{{$own_book['print_price']}}
                                            руб.</h1>
                                        <div class="participation-price-desc">
                                            <p style="    color: #dfdfdf !important; font-size: 23px;">За печать
                                                ({{$own_book->printorder['books_needed']}} экз.)</p>
                                        </div>
                                    </div>
                                @endif

                                <div style="margin-top: 20px;" class="participation-price">
                                    <h1 style="color:{{$part_all_good}} !important;">{{$own_book['total_price'] - $own_book['print_price']}}</h1>
                                    <h1 style="color:{{$part_all_good}} !important;"> руб.</h1>
                                    <p style="color:{{$part_all_good}} !important;">Итого*</p>
                                </div>
                            </div>
                            <p><i>*На данном этапе оплата производится за все услуги, исключая печать, так как цена
                                    печати может измениться после утверждения макетов.</i></p>

                        </div>
                    </div>
                @endif
            </div>

        </div>
        {{-- // БЛОК ОПЛАТЫ--}}


        {{-- БЛОК ПРЕДВАРИТЕЛЬНОЙ ПРОВЕРКИ --}}
        <div class="part" style="border-left: 2px
        @if ($own_book['own_book_status_id']< 3 || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] < 2
            || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] === 4 || $own_book['own_book_inside_status_id'] === 4 & $own_book['own_book_cover_status_id'] < 2)
        {{$part_not_available}}
        @elseif ($own_book['own_book_inside_status_id'] == 2 || $own_book['own_book_cover_status_id'] == 2
                || $own_book['own_book_inside_status_id'] == 3 || $own_book['own_book_cover_status_id'] == 3
            )
        {{$part_action_needed}}
        @elseif  ($own_book['own_book_status_id'] > 3)
        {{$part_all_good}}
        @endif solid;
            border-right: 2px
        @if ($own_book['own_book_status_id']< 3 || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] < 2
            || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] === 4 || $own_book['own_book_inside_status_id'] === 4 & $own_book['own_book_cover_status_id'] < 2)
        {{$part_not_available}}
        @elseif ($own_book['own_book_inside_status_id'] == 2 || $own_book['own_book_cover_status_id'] == 2
                || $own_book['own_book_inside_status_id'] == 3 || $own_book['own_book_cover_status_id'] == 3
            )
        {{$part_action_needed}}
        @elseif  ($own_book['own_book_status_id'] > 3)
        {{$part_all_good}}
        @endif solid;
            ">

            <div style="background:
        @if ($own_book['own_book_status_id']< 3 || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] < 2
            || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] === 4 || $own_book['own_book_inside_status_id'] === 4 & $own_book['own_book_cover_status_id'] < 2
            || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] === 4 || $own_book['own_book_inside_status_id'] === 4 & $own_book['own_book_cover_status_id'] < 2)
            {{$part_not_available}}
            @elseif ($own_book['own_book_inside_status_id'] == 2 || $own_book['own_book_cover_status_id'] == 2
                    || $own_book['own_book_inside_status_id'] == 3 || $own_book['own_book_cover_status_id'] == 3
                )
            {{$part_action_needed}}
            @elseif  ($own_book['own_book_status_id'] > 3)
            {{$part_all_good}}
            @endif
                " class="line"></div>
            @if ($own_book['own_book_status_id'] <= 3)
                <svg id="Слой_1" class="circle_status"
                     style="fill:
        @if ($own_book['own_book_status_id']< 3 || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] < 2
            || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] === 4 || $own_book['own_book_inside_status_id'] === 4 & $own_book['own_book_cover_status_id'] < 2)
                     {{$part_not_available}}
                     @elseif ($own_book['own_book_inside_status_id'] == 2 || $own_book['own_book_cover_status_id'] == 2
                             || $own_book['own_book_inside_status_id'] == 3 || $own_book['own_book_cover_status_id'] == 3
                         )
                     {{$part_action_needed}}
                     @elseif  ($own_book['own_book_status_id'] > 3)
                     {{$part_all_good}}
                     @endif" data-name="Слой 1"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496.52 516.53">
                    <defs>
                        <style>.cls-1 {
                                fill: none;
                                stroke: #000;
                                stroke-miterlimit: 10;
                                stroke-width: 14px;
                            }</style>
                    </defs>
                    <path
                        d="M307,193.9a36.83,36.83,0,0,0,12.6-27.7V142.7a14.72,14.72,0,0,0,14.7-14.7V113.3a14.72,14.72,0,0,0-14.7-14.7H157.45a14.72,14.72,0,0,0-14.7,14.7V128a14.72,14.72,0,0,0,14.7,14.7v23.5a36.83,36.83,0,0,0,12.6,27.7l46.4,40.6v8l-46.4,40.6a36.83,36.83,0,0,0-12.6,27.7v23.5a14.72,14.72,0,0,0-14.7,14.7v14.7a14.72,14.72,0,0,0,14.7,14.7h162.1a14.72,14.72,0,0,0,14.7-14.7V349a14.72,14.72,0,0,0-14.7-14.7V310.8A36.83,36.83,0,0,0,307,283.1l-46.4-40.6v-8Zm-149.5-80.7h162.1v14.7H157.45Zm14.8,29.5h132.6v23.5a22.51,22.51,0,0,1-4.5,13.4H176.75a22.07,22.07,0,0,1-4.5-13.4Zm147.3,221H157.45V349h162.1ZM251,253.6l46.4,40.6a22.55,22.55,0,0,1,7.6,16.6v23.5H172.25V310.8a22,22,0,0,1,7.6-16.6l46.4-40.6a14.64,14.64,0,0,0,5-11.1v-8a14.64,14.64,0,0,0-5-11.1l-33.2-29.1h91.3l-33.2,29.1a14.74,14.74,0,0,0-5.1,11.1v8A14.22,14.22,0,0,0,251,253.6Z"
                        transform="translate(11.27 33.78)"/>
                    <path d="M231.15,260.6h14.7v14.7h-14.7Z" transform="translate(11.27 33.78)"/>
                    <path d="M231.15,290.1h14.7v14.7h-14.7Z" transform="translate(11.27 33.78)"/>
                    <path class="cls-1" d="M531.26,200.22" transform="translate(11.27 33.78)"/>
                    <path
                        d="M465.53,193.88C486.61,335.72,377,460.38,245.19,465.41,128.72,469.86,15.94,379.16,7,253.13-1.7,130,92.07,19.61,217.17,8.48l-19.92,32L211,51.67,266.79,8.38a17.48,17.48,0,0,0-1.88-13.73L203.27-33.78,191.78-20.46l15,15.37C76.05,11-19.5,127.2-10.71,255.29c9.81,143.09,144.82,228.23,257,227.45,89.76-.62,150.58-56.15,161.56-66.52,93.26-88.14,77-213.4,75.16-225.91Z"
                        transform="translate(11.27 33.78)"/>
                    <path
                        d="M456.05,161.93l16.73-6c-2.12-5.53-4.46-11.16-7-16.58l-16.28,7.26C451.81,151.53,454,156.73,456.05,161.93Z"
                        transform="translate(11.27 33.78)"/>
                    <path d="M352.8,24.49c-5.47-2.71-11.16-5.31-16.73-7.58l-7,15.92c5.25,2.16,10.49,4.55,15.62,7.15Z"
                          transform="translate(11.27 33.78)"/>
                    <path
                        d="M465.53,193.88l17.5-3.57c-.61-2.86-1.3-5.79-2.06-8.76A228.55,228.55,0,0,0,472.78,156l-16.73,6a231.06,231.06,0,0,1,7.54,23.83C464.3,188.51,464.94,191.22,465.53,193.88Z"
                        transform="translate(11.27 33.78)"/>
                    <path
                        d="M423,103.66l14.17-10.5c-3.68-4.77-7.7-9.43-11.71-13.87L412.12,90.77C415.91,95,419.59,99.33,423,103.66Z"
                        transform="translate(11.27 33.78)"/>
                    <path
                        d="M441.78,131.61l15.61-8.34c-3-5.2-6.14-10.4-9.48-15.38L433,117.31C436,122,439,126.73,441.78,131.61Z"
                        transform="translate(11.27 33.78)"/>
                    <path d="M318.79,10.41C312.88,8.46,307,6.73,301.06,5.32L296.6,22.1c5.46,1.41,10.92,2.93,16.5,4.77Z"
                          transform="translate(11.27 33.78)"/>
                    <path d="M384.35,43.23c-5-3.58-10.26-6.83-15.39-10L359.6,48c4.79,2.82,9.59,6,14.27,9.21Z"
                          transform="translate(11.27 33.78)"/>
                    <path d="M412.67,66.3c-4.34-4.12-9-8.24-13.71-12L387.47,67.49c4.35,3.57,8.7,7.36,12.72,11.15Z"
                          transform="translate(11.27 33.78)"/>
                </svg>
            @elseif ($own_book['own_book_status_id'] > 3)
                <svg id="Capa_1" class="circle_status" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 234.15 234.15">
                    <circle class="cls-11" cx="117.08" cy="117.08" r="114.58"/>
                    <polyline class="cls-22" points="50.03 111.7 108.85 192.66 184.12 41.49"/>
                </svg>
            @endif
            <div style="
            @if ($own_book['own_book_status_id']< 3 || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] < 2
            || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] === 4 || $own_book['own_book_inside_status_id'] === 4 & $own_book['own_book_cover_status_id'] < 2)
            {{$part_not_available}}
            @elseif ($own_book['own_book_inside_status_id'] == 2 || $own_book['own_book_cover_status_id'] == 2
                    || $own_book['own_book_inside_status_id'] == 3 || $own_book['own_book_cover_status_id'] == 3
                )
                box-shadow: 0 0 10px 1px {{$part_action_needed}}36
            @elseif  ($own_book['own_book_status_id'] > 3)
                box-shadow: 0 0 10px 1px {{$part_all_good}}85;
            @endif" class="container">

                <div style="justify-content: space-between; border-bottom: 1px
                @if ($own_book['own_book_status_id']< 3 || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] < 2
            || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] === 4 || $own_book['own_book_inside_status_id'] === 4 & $own_book['own_book_cover_status_id'] < 2)
                {{$part_not_available}}
                @elseif ($own_book['own_book_inside_status_id'] == 2 || $own_book['own_book_cover_status_id'] == 2
                        || $own_book['own_book_inside_status_id'] == 3 || $own_book['own_book_cover_status_id'] == 3
                    )
                {{$part_action_needed}}
                @elseif  ($own_book['own_book_status_id'] > 3)
                {{$part_all_good}}
                @endif solid" class=hero>
                    <h2 style="color:
                    @if ($own_book['own_book_status_id']< 3 || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] < 2
            || $own_book['own_book_inside_status_id'] < 2 & $own_book['own_book_cover_status_id'] === 4 || $own_book['own_book_inside_status_id'] === 4 & $own_book['own_book_cover_status_id'] < 2)
                    {{$part_not_available}}
                    @elseif ($own_book['own_book_inside_status_id'] == 2 || $own_book['own_book_cover_status_id'] == 2
                            || $own_book['own_book_inside_status_id'] == 3 || $own_book['own_book_cover_status_id'] == 3
                        )
                    {{$part_action_needed}}
                    @elseif  ($own_book['own_book_status_id'] > 3)
                    {{$part_all_good}}
                    @endif;">
                        Предварительная проверка
                    </h2>
                    <div class="pre_var_swither pre_var_chose_wrap">
                        <div style="margin-left: 10px;" class="switch-wrap">
                            <input checked type="radio" value="pre_var_inside" id="pre_var_inside"
                                   name="pre_var_show" class="show-hide">
                            <label for="pre_var_inside">
                                Внутренний блок
                            </label>

                            <input type="radio" value="pre_var_cover" id="pre_var_cover"
                                   name="pre_var_show" class="show-hide">
                            <label for="pre_var_cover">
                                Обложка
                            </label>
                        </div>
                    </div>
                </div>

                <div id="block_pre_var_inside" class="pre_var_show">

                    @if($own_book['own_book_status_id'] < 3)
                        <div class="no-access">
                            <span>
                                Предварительная проверка внутреннего блока будет доступна после его создания или проверки в случае, если он уже готов от автора.
                            </span>
                        </div>
                    @elseif ($own_book['own_book_status_id'] == 3 & $own_book['own_book_inside_status_id'] < 2)
                        <div class="no-access">
                            <span>
                                На данный момент идет работа над внутренним блоком. Предварительный вариант появится здесь до {{$own_book['inside_deadline']}}
                            </span>
                        </div>
                    @elseif ($own_book['own_book_inside_status_id'] >= 2)
                        <div style=" padding: 15px 30px 15px 30px;" class="pre_var_wrap">
                            <div
                                style="@if ($own_book['own_book_inside_status_id'] === 2) min-height: 450px; @endif    display: flex;flex-direction: column; justify-content: space-between;">
                                <h2>Статус</h2>
                                <p style="font-size: 20px;">
                                    @if ($own_book['own_book_inside_status_id'] === 2)
                                        На данный момент внутренний блок находится на этапе предварительной
                                        проверки. Это означает, что все регистрационные
                                        номера прсвоены и блок сверстан. Сейчас необходимо скачать файл и
                                        указать комментарии, что бы вы хотели исправить в блоке.
                                        Пожалуйста, укажите страницу исправления, а также описание того, что нужно
                                        исправить.
                                    @elseif ($own_book['own_book_inside_status_id'] === 3)
                                        На данный момент мы вносим указанные изменения. Как только они будут учтены,
                                        Вы получите оповещение об этом на почте и внутри нашей системы.
                                        Далее внутренний блок можно будет еще раз проверить, а затем запросить
                                        дополнительные изменения или утвердить его.
                                    @elseif ($own_book['own_book_inside_status_id'] === 4)
                                        Поздравляем! Внутренний блок был успешно Вами утвержден.
                                        Как только будет утверждены обложка и внутренний блок, можно будет переходить к
                                        следующим этапам издания.
                                    @elseif ($own_book['own_book_inside_status_id'] === 9)
                                        В заявке указано, что внутренний блок полностью готов к печати. Сейчас мы это
                                        проверяем.
                                        Если все в порядке, мы сменим статус ВБ на "готов к изданию".
                                        Если нет, то укажем комментарий по исправлению в чате на этой странице.
                                        Вы получите об этом оповещение на почте в том числе.
                                    @endif
                                </p>

                                <a style="display: flex; margin-top:20px;" class="button"
                                   href="/{{$own_book['inside_file']}}"
                                   download>
                                    <svg style="width:40px;" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 404.85 511">
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
                                    Предварительный вариант блока
                                </a>
                            </div>
                            <div style="@if ($own_book['own_book_inside_status_id'] === 2) min-height: 450px; @endif"
                                 class="pre_var_right">
                                <h2>Мои исправления</h2>
                                @if ($own_book['own_book_inside_status_id'] === 2)
                                    @livewire('preview-comment',['collection_id' => 0, 'own_book_id' => $own_book->id,
                                    'own_book_comment_type' => 'inside'])
                                @elseif ($own_book['own_book_inside_status_id'] > 2)
                                    <div style=" padding:0; height: 100%;" class="messages">
                                        @if(count($inside_comments) > 0)
                                            @foreach($inside_comments as $comment)
                                                <div style="position: relative; margin-top: 30px; margin-bottom:20px;"
                                                     class="message">
                                                    <div
                                                        style="background: @if($comment['status_done'] === 0) #acacac @else #47AF98 @endif"
                                                        class="message-wrap">
                                                        Страница {{$comment['page']}}: {{$comment['text']}}
                                                    </div>
                                                    <p style="margin-right: 5px; margin-top: -5px;font-size: 17px; float:right;">
                                                        Статус: @if($comment['status_done'] === 0) выполняется @else
                                                            учтено @endif</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>Вы не делали исправлений в этой книге.</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div style="display: none;" id="block_pre_var_cover" class="pre_var_show">
                    @if($own_book['own_book_status_id'] < 2)
                        <div class="no-access">
                            <span>
                                Предварительная проверка обложки будет доступна сразу после ее создания или проверки в случае, если она уже готова от автора.
                            </span>
                        </div>
                    @elseif ($own_book['own_book_status_id'] == 3 & $own_book['own_book_cover_status_id'] < 2)
                        <div class="no-access">
                            <span>
                                На данный момент идет работа над обложкой. Предварительный вариант появится здесь до {{$own_book['cover_deadline']}}
                            </span>
                        </div>
                    @elseif ($own_book['own_book_cover_status_id'] >= 2)
                        <div style=" padding: 15px 30px 15px 30px;" class="pre_var_wrap">
                            <div
                                style="@if ($own_book['own_book_cover_status_id'] === 2) min-height: 450px; @endif    display: flex;flex-direction: column; justify-content: space-between;">
                                <h2>Статус обложки</h2>
                                <p style="font-size: 20px;">

                                    @if ($own_book['own_book_cover_status_id'] === 2)
                                        На данный момент обложка находится на этапе предварительной
                                        проверки. Сейчас необходимо указать комментарии, что бы вы хотели исправить.
                                        Пожалуйста, укажите Ваши комментарии в форме рядом или утвердите обложку, если
                                        исправления не требуются.
                                    @elseif ($own_book['own_book_cover_status_id'] === 3)
                                        На данный момент мы вносим указанные изменения. Как только они будут учтены,
                                        Вы получите оповещение об этом на почте и внутри нашей системы.
                                        Далее обложку можно будет еще раз проверить, а затем запросить дополнительные
                                        изменения или утвердить ее.
                                    @elseif ($own_book['own_book_cover_status_id'] === 4)
                                        Поздравляем! Обложка была успешно Вами утверждена.
                                        Как только будут утверждены обложка и внутренний блок, можно будет переходить к
                                        следующим этапам издания.
                                    @elseif ($own_book['own_book_cover_status_id'] === 9)
                                        В заявке указано, что обложка полностью готова к печати. Сейчас мы это
                                        проверяем.
                                        Если все в порядке, мы сменим статус обложки на "готова к изданию".
                                        Если нет, то укажем комментарий по исправлению в чате на этой странице.
                                        Вы получите об этом оповещение на почте в том числе.
                                    @endif
                                </p>
                                @if ($own_book['own_book_cover_status_id'] <> 9)
                                    <a style="display: flex; margin-top:20px;" class="button"
                                       href="/{{$own_book['cover_3d']}}"
                                       download>
                                        <svg style="width:40px;" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 404.85 511">
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
                                        Предварительный вариант обложки
                                    </a>
                                @endif
                            </div>
                            <div style="@if ($own_book['own_book_cover_status_id'] === 2) min-height: 450px; @endif"
                                 class="pre_var_right">
                                <h2>Мои исправления</h2>
                                @if ($own_book['own_book_cover_status_id'] === 2)
                                    @livewire('preview-comment',['collection_id' => 0, 'own_book_id' => $own_book->id,
                                    'own_book_comment_type' => 'cover'])
                                @elseif ($own_book['own_book_cover_status_id'] > 2)
                                    <div style=" padding:0; height: 100%;" class="messages">
                                        @if(count($cover_comments) > 0)
                                            @foreach($cover_comments as $comment)
                                                <div style="position: relative; margin-top: 30px; margin-bottom:20px;"
                                                     class="message">
                                                    <div
                                                        style="background: @if($comment['status_done'] === 0) #acacac @else #47AF98 @endif"
                                                        class="message-wrap">
                                                        Страница {{$comment['page']}}: {{$comment['text']}}
                                                    </div>
                                                    <p style="margin-right: 5px; margin-top: -5px;font-size: 17px; float:right;">
                                                        Статус: @if($comment['status_done'] === 0) выполняется @else
                                                            учтено @endif</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>Вы не делали исправлений по обложке.</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- // БЛОК ПРЕДВАРИТЕЛЬНОЙ ПРОВЕРКИ --}}


        {{-- БЛОК ПЕЧАТИ КНИГИ --}}
        <div id="part_print" class="part"
             style="padding-bottom: 25px;
             @if ($own_book['own_book_status_id'] < 4)
                 border-bottom: 2px {{$part_not_available}} solid;
                 border-left: 2px {{$part_not_available}} solid;
                 border-right: 2px {{$part_not_available}} solid;
             @elseif ($own_book['own_book_status_id'] < 9)
                 border-bottom: 2px {{$part_action_needed}} solid;
                 border-left: 2px {{$part_action_needed}} solid;
                 border-right: 2px {{$part_action_needed}} solid;
             @elseif ($own_book['own_book_status_id'] == 9)
                 border-bottom: 2px {{$part_all_good}} solid;
                 border-left: 2px {{$part_all_good}} solid;
                 border-right: 2px {{$part_all_good}} solid;
             @endif
                 border-radius: 0 0 10px 10px;
                 ">
            <div style="background:
            @if ($own_book['own_book_status_id'] < 4)
            {{$part_not_available}}
            @elseif ($own_book['own_book_status_id'] < 9)
            {{$part_action_needed}}
            @elseif ($own_book['own_book_status_id'] == 9)
            {{$part_all_good}}
            @endif

                ;" class="line"></div>
            @if ($own_book['own_book_status_id'] < 9)
                <svg id="Слой_1" class="circle_status" style="fill:
                @if ($own_book['own_book_status_id'] < 4)
                {{$part_not_available}}
                @elseif ($own_book['own_book_status_id'] < 9)
                {{$part_action_needed}}
                @elseif ($own_book['own_book_status_id'] == 9)
                {{$part_all_good}}
                @endif;" data-name="Слой 1"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496.52 516.53">
                    <defs>
                        <style>.cls-1 {
                                fill: none;
                                stroke: #000;
                                stroke-miterlimit: 10;
                                stroke-width: 14px;
                            }</style>
                    </defs>
                    <path
                        d="M307,193.9a36.83,36.83,0,0,0,12.6-27.7V142.7a14.72,14.72,0,0,0,14.7-14.7V113.3a14.72,14.72,0,0,0-14.7-14.7H157.45a14.72,14.72,0,0,0-14.7,14.7V128a14.72,14.72,0,0,0,14.7,14.7v23.5a36.83,36.83,0,0,0,12.6,27.7l46.4,40.6v8l-46.4,40.6a36.83,36.83,0,0,0-12.6,27.7v23.5a14.72,14.72,0,0,0-14.7,14.7v14.7a14.72,14.72,0,0,0,14.7,14.7h162.1a14.72,14.72,0,0,0,14.7-14.7V349a14.72,14.72,0,0,0-14.7-14.7V310.8A36.83,36.83,0,0,0,307,283.1l-46.4-40.6v-8Zm-149.5-80.7h162.1v14.7H157.45Zm14.8,29.5h132.6v23.5a22.51,22.51,0,0,1-4.5,13.4H176.75a22.07,22.07,0,0,1-4.5-13.4Zm147.3,221H157.45V349h162.1ZM251,253.6l46.4,40.6a22.55,22.55,0,0,1,7.6,16.6v23.5H172.25V310.8a22,22,0,0,1,7.6-16.6l46.4-40.6a14.64,14.64,0,0,0,5-11.1v-8a14.64,14.64,0,0,0-5-11.1l-33.2-29.1h91.3l-33.2,29.1a14.74,14.74,0,0,0-5.1,11.1v8A14.22,14.22,0,0,0,251,253.6Z"
                        transform="translate(11.27 33.78)"/>
                    <path d="M231.15,260.6h14.7v14.7h-14.7Z" transform="translate(11.27 33.78)"/>
                    <path d="M231.15,290.1h14.7v14.7h-14.7Z" transform="translate(11.27 33.78)"/>
                    <path class="cls-1" d="M531.26,200.22" transform="translate(11.27 33.78)"/>
                    <path
                        d="M465.53,193.88C486.61,335.72,377,460.38,245.19,465.41,128.72,469.86,15.94,379.16,7,253.13-1.7,130,92.07,19.61,217.17,8.48l-19.92,32L211,51.67,266.79,8.38a17.48,17.48,0,0,0-1.88-13.73L203.27-33.78,191.78-20.46l15,15.37C76.05,11-19.5,127.2-10.71,255.29c9.81,143.09,144.82,228.23,257,227.45,89.76-.62,150.58-56.15,161.56-66.52,93.26-88.14,77-213.4,75.16-225.91Z"
                        transform="translate(11.27 33.78)"/>
                    <path
                        d="M456.05,161.93l16.73-6c-2.12-5.53-4.46-11.16-7-16.58l-16.28,7.26C451.81,151.53,454,156.73,456.05,161.93Z"
                        transform="translate(11.27 33.78)"/>
                    <path d="M352.8,24.49c-5.47-2.71-11.16-5.31-16.73-7.58l-7,15.92c5.25,2.16,10.49,4.55,15.62,7.15Z"
                          transform="translate(11.27 33.78)"/>
                    <path
                        d="M465.53,193.88l17.5-3.57c-.61-2.86-1.3-5.79-2.06-8.76A228.55,228.55,0,0,0,472.78,156l-16.73,6a231.06,231.06,0,0,1,7.54,23.83C464.3,188.51,464.94,191.22,465.53,193.88Z"
                        transform="translate(11.27 33.78)"/>
                    <path
                        d="M423,103.66l14.17-10.5c-3.68-4.77-7.7-9.43-11.71-13.87L412.12,90.77C415.91,95,419.59,99.33,423,103.66Z"
                        transform="translate(11.27 33.78)"/>
                    <path
                        d="M441.78,131.61l15.61-8.34c-3-5.2-6.14-10.4-9.48-15.38L433,117.31C436,122,439,126.73,441.78,131.61Z"
                        transform="translate(11.27 33.78)"/>
                    <path d="M318.79,10.41C312.88,8.46,307,6.73,301.06,5.32L296.6,22.1c5.46,1.41,10.92,2.93,16.5,4.77Z"
                          transform="translate(11.27 33.78)"/>
                    <path d="M384.35,43.23c-5-3.58-10.26-6.83-15.39-10L359.6,48c4.79,2.82,9.59,6,14.27,9.21Z"
                          transform="translate(11.27 33.78)"/>
                    <path d="M412.67,66.3c-4.34-4.12-9-8.24-13.71-12L387.47,67.49c4.35,3.57,8.7,7.36,12.72,11.15Z"
                          transform="translate(11.27 33.78)"/>
                </svg>

            @else
                <svg id="Capa_1" class="circle_status" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 234.15 234.15">
                    <circle class="cls-11" cx="117.08" cy="117.08" r="114.58"/>
                    <polyline class="cls-22" points="50.03 111.7 108.85 192.66 184.12 41.49"/>
                </svg>
            @endif
            <div style="
            @if ($own_book['own_book_status_id'] < 4)
            @elseif ($own_book['own_book_status_id'] < 9)
                box-shadow: 0 0 10px 1px {{$part_action_needed}}36;
            @elseif ($own_book['own_book_status_id'] == 9)
                box-shadow: 0 0 10px 1px {{$part_all_good}}85;
            @endif" class="container">
                <div style="border-bottom: 1px
                @if ($own_book['own_book_status_id'] < 4)
                {{$part_not_available}}
                @elseif ($own_book['own_book_status_id'] < 9)
                {{$part_action_needed}}
                @elseif ($own_book['own_book_status_id'] == 9)
                {{$part_all_good}}
                @endif solid" class=hero>
                    <h2 style="color:
                @if ($own_book['own_book_status_id'] < 4)
                    {{$part_not_available}}
                    @elseif ($own_book['own_book_status_id'] < 9)
                    {{$part_action_needed}}
                    @elseif ($own_book['own_book_status_id'] == 9)
                    {{$part_all_good}}
                    @endif">Печать книги</h2>
                </div>

                @if($own_book['print_price'])
                    @if ($own_book['own_book_status_id'] < 4)
                        <div class="no-access">
                        <span>У Вас предусмотрена печать, но процесс оплаты и непосредственной печати будет доступен только после утверждения внутреннего блока и обложки.
                        </span>
                        </div>
                    @elseif ($own_book['own_book_status_id'] === 4)
                        <div class="no-access">
                        <span>Файлы утверждены! У вас есть заказ печати. Его нужно оплатить:
                        </span>

                            <form style="display:inline-block"
                                  action="{{ route('pay_for_own_book_print',$own_book['id']) }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf

                                <button id="btn-submit" type="submit" style="height: fit-content; max-width:250px;"
                                        class="pay-button button">
                                    Оплатить
                                </button>
                            </form>
                            <a name="create_form" id="create_form" class="show-hide link">Изменить заказ</a>
                        </div>
                        <div style="display: none;" id="block_create_form" class="create_form">
                            @livewire('own-book-printorder-form', ['own_book' => $own_book, 'form_type' => 'edit'])
                        </div>

                    @elseif ($own_book['own_book_status_id'] === 5)
                        <div class="no-access">
                        <span>Печать успешно оплачена! Ожидаем подтверждение от типографии (1-3 дня).
                        </span>
                        </div>
                    @elseif ($own_book['own_book_status_id'] === 6)
                        <div class="no-access">
                        <span>Прямо сейчас идет печать книги. Ссылка для отслеживания будет доступна после отправки экземпляров.
                        </span>
                        </div>
                    @else
                        <div class="no-access">
                        <span>Был заказ, и он выполнен!
                        </span>
                            <br>
                            <a target="_blank" class="button"
                               href="https://www.pochta.ru/tracking#{{$own_book->printorder['track_number']}}">Отследить
                                книгу</a>
                        </div>
                    @endif
                @elseif ($own_book['own_book_status_id'] == 9)
                    <div class="no-access">
                        <span>Процесс издания завершен! У Вас не было заказа печатных экземпляров, но Вы можете создать его здесь:
                        </span>
                        <a name="create_form" id="create_form" class="show-hide link">Создать заказ</a>
                    </div>
                    <div style="display: none;" id="block_create_form" class="create_form">
                        @livewire('own-book-printorder-form', ['own_book' => $own_book, 'form_type' => 'create'])
                    </div>

                @elseif ($own_book['own_book_status_id'] < 4)
                    <div class="no-access">
                        <span>У Вас нет заказа печатных экземпляров. Вы сможете заказать печать, но процесс печати будет доступен только после утверждения макетов.
                        </span>
                        <a name="create_form" id="create_form" class="show-hide link">Создать заказ</a>
                    </div>
                    <div style="display: none;" id="block_create_form" class="create_form">
                        @livewire('own-book-printorder-form', ['own_book' => $own_book, 'form_type' => 'create'])
                    </div>
                @endif
            </div>
        </div>
        {{-- // БЛОК ОТСЛЕЖИВАНИЯ ПОСЫЛКИ --}}
    </div>
    </div>
    <script>
        $('#chat_button').click(function () {
            $('#book_chat').slideToggle(function () {
                if ($('#book_chat').is(":visible")) {
                    $('#chat_button').html('Свернуть чат');
                } else {
                    $('#chat_button').html('Развернуть чат');
                }
            });
        });
    </script>
@endsection

@section('page-js')

@endsection
