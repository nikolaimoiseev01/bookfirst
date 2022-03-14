@extends('layouts.app')

@section('page-style')
    <link rel="stylesheet" href="/css/chat.css">
@endsection

@section('page-tab-title')
    Страница участия
@endsection

@section('page-title')
    <div style="flex-direction: column; align-items: flex-start;" class="account-header">
        <h1 id="participation-index-h1" style="margin-left: 30px;">Мое участие в сборнике {{$collection['title']}}</h1>
        <a target="_blank" style="margin-left:30px;" href="{{route('help_collection')}}#application_pay" class="link">Инструкция по этой странице</a>
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

    {{-- Общая информация о заявке--}}
    <div class="participation-wrapper">
        <div style="    display: flex; flex-direction: column;">
            <div style="float: left">
                <p>Мой статус участия: <b><i>{{$participation->pat_status['pat_status_title']}}</i></b></p>
            </div>
            <div style="float: left">
                <p>Статус издания сборника: <b><i>{{$collection->col_status['col_status']}}</i></b></p>
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
    {{-- // Общая информация о заявке--}}
    <a id="chat_button" style="margin-left: 30px; margin-top: 20px; width: 95%; text-align: center; max-width: 1000px;"
       class="button">Чат по моему изданию</a>
    <div class="participation-wrap">
        <div id="book_chat" style="display: none; margin: 0 0 30px 0; width: 100%; max-width: 1000px;" class="chat">
            <div style="margin: 0; width: 100%; max-width: 1000px;" class="container">
                @livewire('chat',['chat_id'=>$chat_id])
            </div>
        </div>

        {{-- БЛОК ИНФОРМАЦИИ О ЗАЯВКЕ --}}
        <div class="part"
             style="z-index: 1;
                 padding-top: 25px;
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
                    @if ($participation['paid_at'] === null)
                        <a style="box-shadow: none; font-size: 16px; margin-left: auto; margin-right: 25px;" href="{{route('participation_edit', [
                 'participation_id'=>$participation['id'],
                 'collection_id' => $collection['id']
                 ])
                 }}" class="button">Редактировать</a>
                    @endif
                </div>
                <div class="info">
                    <div class="part_part">
                        <h2>Участие:</h2>
                        <span><p style="margin: 0;">Имя: <i>{{$participation['name']}}</i></p></span>
                        <span><p style="margin: 0;">Фамилия: <i>{{$participation['surname']}}</i></p></span>
                        @if($participation['nickname'] <> "")
                            <span><p style="margin: 0;">Псевдоним: <i>{{$participation['nickname']}}</i></p></span>
                        @endif
                        <span><p style="margin: 0;">Произведений: <i>{{$participation['works_number']}}</i></p></span>
                        <span><p style="margin: 0;">Строчек: <i>{{$participation['rows']}}</i></p></span>
                        <span><p style="margin: 0;">Страниц с сборнике: <i>{{$participation['pages']}}</i></p></span>
                    </div>
                    <div class="print_part">
                        <h2>Печатные экземпляры:</h2>
                        @if($participation->printorder['books_needed'] ?? 0 > 0)
                            <span><p
                                    style="margin: 0;">Печатных экземпляров: {{$participation->printorder['books_needed']}}</p></span>
                            <span><p style="margin: 0;">ФИО Адресата: {{$participation->printorder['send_to_name']}}</p></span>
                            <span><p
                                    style="margin: 0;">Адрес: {{$participation->printorder['send_to_address']}}</p></span>
                            <span><p
                                    style="margin: 0;">Телефон: {{$participation->printorder['send_to_tel']}}</p></span>
                            @if ($collection['col_status_id'] < 3 && $participation['paid_at'] <> null)
                                <a style="font-size: 25px;" href="#print_part" class="link">Заказать дополнительные
                                    экземпляры</a>
                            @endif
                        @else
                            <p>Печатные эезкемпляры не требуются.</p>
                            @if ($collection['col_status_id'] < 3 && $participation['paid_at'] <> null)
                                <a style="font-size: 25px;" href="#print_part" class="link">Создать заказ</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- // БЛОК ИНФОРМАЦИИ О ЗАЯВКЕ --}}

        {{-- БЛОК ОПЛАТЫ--}}
        <div class="part"
             style="margin-top: -85px;
                 padding-top: 85px;
                 border-left: 2px
             @if ($participation['pat_status_id'] === 1)
             {{$part_not_available}}
             @elseif ($participation['pat_status_id'] === 2)
             {{$part_action_needed}}
             @else
             {{$part_all_good}}
             @endif solid;
                 border-right: 2px
             @if ($participation['pat_status_id'] === 1)
             {{$part_not_available}}
             @elseif ($participation['pat_status_id'] === 2)
             {{$part_action_needed}}
             @else
             {{$part_all_good}}
             @endif solid;
                 "
             id="payment_block">
            <div style="background:
            @if ($participation['pat_status_id'] === 1)
            {{$part_not_available}}
            @elseif ($participation['pat_status_id'] === 2)
            {{$part_action_needed}}
            @else
            {{$part_all_good}}
            @endif
                " class="line"></div>

            @if($participation['pat_status_id'] <= 2)
                <svg style="fill:
            @if ($participation['pat_status_id'] === 1)
                {{$part_not_available}}
                @elseif ($participation['pat_status_id'] === 2)
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
            @if ($participation['pat_status_id'] === 1)

            @elseif ($participation['pat_status_id'] === 2)
                box-shadow: 0 0 10px 1px {{$part_action_needed}}36
            @else
                box-shadow: 0 0 10px 1px {{$part_all_good}}85;
            @endif" class="container">
                <div style="border-bottom: 1px
                @if ($participation['pat_status_id'] === 1)
                {{$part_not_available}}
                @elseif ($participation['pat_status_id'] === 2)
                {{$part_action_needed}}
                @else
                {{$part_all_good}}
                @endif solid" class=hero>
                    <h2 style="color:
                    @if ($participation['pat_status_id'] === 1)
                    {{$part_not_available}}
                    @elseif ($participation['pat_status_id'] === 2)
                    {{$part_action_needed}}
                    @else
                    {{$part_all_good}}
                    @endif;">
                        @if ($participation['pat_status_id'] <= 2)
                            Оплата участия
                        @else
                            Оплата успешно принята!
                        @endif
                    </h2>
                </div>

                @if ($participation['pat_status_id'] === 1)
                    <div class="no-access">
                        <span style="font-size: 30px;">После создания или редактирования заявки нам необходимо ее подтвердить (до 3-х рабочих дней).
                            Оплата станет доступна сразу после подтверждения Вашей заявки.
                        </span>
                    </div>
                @elseif ($participation['pat_status_id'] === 2)
                    <div class="need_to_pay_wrap" style="display: flex;">
                        <div
                            style="padding: 10px; width:50%; flex-direction: column; display: flex; justify-content: space-evenly; text-align: center;"
                            class="payment-info">
                            <p>Отлично, Ваша заявка подтверждена! Для включения Вас в сборник необходимо произвести
                                оплату.</p>
                            <form style="display:inline-block"
                                  action="{{ route('payment.create_part_payment', [$participation['id'], $participation['total_price']])}}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <input value="{{$participation['id']}}" type="text" name="pat_id"
                                       style="display:none" class="form-control"
                                       id="pat_id">

                                <button id="btn-submit" type="submit" style="height: fit-content; max-width:250px;"
                                        class="pay-button button">
                                    Оплатить {{$participation['total_price']}} руб.
                                </button>
                            </form>

                            {{--                            <div id="payment-form"></div>--}}
                            {{--                            <span id="yookassa_token" style="display: none;" data-id="{{$yookassa_token}}"></span>--}}
                            {{--                            <script src="https://yookassa.ru/checkout-widget/v1/checkout-widget.js"></script>--}}
                            {{--                            <script>--}}
                            {{--                                //Инициализация виджета. Все параметры обязательные.--}}
                            {{--                                var token = $('#yookassa_token').attr('data-id');--}}

                            {{--                                function make_redirect() {--}}
                            {{--                                    @php--}}
                            {{--                                        session()->flash('show_modal', 'yes');--}}
                            {{--                                        session()->flash('alert_type', 'success');--}}
                            {{--                                        session()->flash('alert_title', 'Оплата успешно принята!');--}}
                            {{--                                    @endphp--}}
                            {{--                                        return window.location.href;--}}
                            {{--                                }--}}

                            {{--                                const checkout = new window.YooMoneyCheckoutWidget({--}}
                            {{--                                    confirmation_token: token, //Токен, который перед проведением оплаты нужно получить от ЮKassa--}}
                            {{--                                    return_url: make_redirect(), //Ссылка на страницу завершения оплаты, это может быть любая ваша страница--}}
                            {{--                                    error_callback: function (error) {--}}
                            {{--                                        console.log(error)--}}
                            {{--                                    },--}}

                            {{--                                });--}}

                            {{--                                //Отображение платежной формы в контейнере--}}
                            {{--                                checkout.render('payment-form');--}}
                            {{--                            </script>--}}
                        </div>

                        <div style="padding: 10px; width:50%;" class="participation-outputs">
                            <div class="prices_inputs" style="display: flex;">
                                <div class="participation-price">
                                    <h1 style="font-size: 38px;">{{$participation['part_price']}} руб.</h1>
                                    <div class="participation-price-desc">
                                        <p style="font-size: 23px;">За участие <br>({{$participation['pages']}}
                                            стр.)</p>
                                    </div>
                                </div>

                                @if($participation['print_price'] > 0)
                                    <svg class="plus-svg" viewBox="0 0 448 448">
                                        <path
                                            d="M408,184H272a8,8,0,0,1-8-8V40a40,40,0,0,0-80,0V176a8,8,0,0,1-8,8H40a40,40,0,0,0,0,80H176a8,8,0,0,1,8,8V408a40,40,0,0,0,80,0V272a8,8,0,0,1,8-8H408a40,40,0,0,0,0-80Z"
                                            transform="translate(0 0)"/>
                                    </svg>
                                    <div class="participation-price">
                                        <h1 style="font-size: 38px;">{{$participation['print_price']}} руб.</h1>
                                        <div class="participation-price-desc">
                                            <p style="font-size: 23px;">За печать<br>
                                                ({{$participation->printorder['books_needed']}} экз.)</p>
                                        </div>
                                    </div>
                                @endif

                                @if($participation['check_price'] > 0)
                                    <svg class="plus-svg" viewBox="0 0 448 448">
                                        <path
                                            d="M408,184H272a8,8,0,0,1-8-8V40a40,40,0,0,0-80,0V176a8,8,0,0,1-8,8H40a40,40,0,0,0,0,80H176a8,8,0,0,1,8,8V408a40,40,0,0,0,80,0V272a8,8,0,0,1,8-8H408a40,40,0,0,0,0-80Z"
                                            transform="translate(0 0)"/>
                                    </svg>
                                    <div class="participation-price">
                                        <h1 style="font-size: 38px;">{{$participation['check_price']}} руб.</h1>
                                        <div class="participation-price-desc">
                                            <p style="font-size: 23px;">За проверку</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div style="margin-top: 20px;" class="participation-price">
                                <h1 style="color:{{$part_action_needed}} !important;">{{$participation['total_price']}}</h1>
                                <h1 style="color:{{$part_action_needed}} !important;"> руб.</h1>
                                <p style="color:{{$part_action_needed}} !important;">Итого</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div style="display: flex;">
                        <div style="padding: 10px; width:100%;" class="participation-outputs">
                            <div class="prices_inputs" style="display: flex;">
                                <div class="participation-price">
                                    <h1 style="font-size: 38px;">{{$participation['part_price']}} руб.</h1>
                                    <div class="participation-price-desc">
                                        <p style="font-size: 23px;">За участие ({{$participation['pages']}}
                                            стр.)</p>
                                    </div>
                                </div>

                                @if($participation['print_price'] > 0)
                                    <svg class="plus-svg" viewBox="0 0 448 448">
                                        <path
                                            d="M408,184H272a8,8,0,0,1-8-8V40a40,40,0,0,0-80,0V176a8,8,0,0,1-8,8H40a40,40,0,0,0,0,80H176a8,8,0,0,1,8,8V408a40,40,0,0,0,80,0V272a8,8,0,0,1,8-8H408a40,40,0,0,0,0-80Z"
                                            transform="translate(0 0)"/>
                                    </svg>
                                    <div class="participation-price">
                                        <h1 style="font-size: 38px;">{{$participation['print_price']}} руб.</h1>
                                        <div class="participation-price-desc">
                                            <p style="font-size: 23px;">За печать
                                                ({{$participation->printorder['books_needed']}} экз.)</p>
                                        </div>
                                    </div>
                                @endif

                                @if($participation['check_price'] > 0)
                                    <svg class="plus-svg" viewBox="0 0 448 448">
                                        <path
                                            d="M408,184H272a8,8,0,0,1-8-8V40a40,40,0,0,0-80,0V176a8,8,0,0,1-8,8H40a40,40,0,0,0,0,80H176a8,8,0,0,1,8,8V408a40,40,0,0,0,80,0V272a8,8,0,0,1,8-8H408a40,40,0,0,0,0-80Z"
                                            transform="translate(0 0)"/>
                                    </svg>
                                    <div class="participation-price">
                                        <h1 style="font-size: 38px;">{{$participation['check_price']}} руб.</h1>
                                        <div class="participation-price-desc">
                                            <p style="font-size: 23px;">За проверку</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div style="margin-top: 20px;" class="participation-price">
                                <h1 style="color:{{$part_all_good}} !important;">{{$participation['total_price']}}</h1>
                                <h1 style="color:{{$part_all_good}} !important;"> руб.</h1>
                                <p style="color:{{$part_all_good}} !important;">Итого</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
        {{-- // БЛОК ОПЛАТЫ--}}


        {{-- БЛОК ПРЕДВАРИТЕЛЬНОЙ ПРОВЕРКИ --}}
        <div class="part" style="border-left: 2px
        @if ($collection['col_status_id'] === 1)
        {{$part_not_available}}
        @elseif ($collection['col_status_id'] === 2)
        {{$part_action_needed}}
        @else
        {{$part_all_good}}
        @endif solid;
            border-right: 2px
        @if ($collection['col_status_id'] === 1)
        {{$part_not_available}}
        @elseif ($collection['col_status_id'] === 2)
        {{$part_action_needed}}
        @else
        {{$part_all_good}}
        @endif solid;
            ">
            <div style="background:
             @if ($collection['col_status_id'] === 1)
            {{$part_not_available}}
            @elseif ($collection['col_status_id'] === 2)
            {{$part_action_needed}}
            @else
            {{$part_all_good}}
            @endif
                " class="line"></div>
            @if ($collection['col_status_id'] <= 2)
                <svg id="Слой_1" class="circle_status"
                     style="fill:
             @if ($collection['col_status_id'] === 1)
                     {{$part_not_available}}
                     @elseif ($collection['col_status_id'] === 2)
                     {{$part_action_needed}}
                     @else
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
            @else
                <svg id="Capa_1" class="circle_status" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 234.15 234.15">
                    <circle class="cls-11" cx="117.08" cy="117.08" r="114.58"/>
                    <polyline class="cls-22" points="50.03 111.7 108.85 192.66 184.12 41.49"/>
                </svg>
            @endif
            <div style="
            @if ($collection['col_status_id'] === 1)

            @elseif ($collection['col_status_id'] === 2)
                box-shadow: 0 0 10px 1px {{$part_action_needed}}36
            @else
                box-shadow: 0 0 10px 1px {{$part_all_good}}85;
            @endif" class="container">
                <div style="border-bottom: 1px
                @if ($collection['col_status_id'] === 1)
                {{$part_not_available}}
                @elseif ($collection['col_status_id'] === 2)
                {{$part_action_needed}}
                @else
                {{$part_all_good}}
                @endif solid" class=hero>
                    <h2 style="color:
                    @if ($collection['col_status_id'] === 1)
                    {{$part_not_available}}
                    @elseif ($collection['col_status_id'] === 2)
                    {{$part_action_needed}}
                    @else
                    {{$part_all_good}}
                    @endif;">
                        @if ($collection['col_status_id'] <= 2)
                            Предварительная проверка
                        @else
                            Предварительная проверка завершена!
                        @endif
                    </h2>
                </div>
                @if ($collection['col_status_id'] >= 2 && $participation['paid_at'] === null)
                    <div class="no-access">
                        <span>Сейчас сборник проходит предварительную проверку, но из-за отствия оплаты Вы не были включены в список участников.
                        </span>
                    </div>
                @elseif($collection['col_status_id'] === 1)
                    <div class="no-access">
                        {{App::setLocale('ru')}}
                        <span>Предварительная проверка сборника станет доступна {{ Date::parse($collection['col_date2'])->format('j F Y') }}.
                        </span>
                    </div>
                @elseif ($collection['col_status_id'] === 2)
                    <div class="pre_var_wrap">
                        <div>
                            <p style="font-size: 20px;">На данный момент сборник находится на этапе предварительной
                                проверки. Это означает, что все регистрационные
                                номера присвоены, и блок сверстан. Сейчас необходимо скачать файл, найти свой блок и
                                указать комментарии, что бы вы хотели исправить в своем блоке.
                                Пожалуйста, укажите страницу исправления, а также описание того, что нужно исправить.
                            </p>
                            <a style="display: flex; margin-top:20px;" class="button" href="/{{$collection['pre_var']}}"
                               download>
                                <svg style="height: 40px; width:40px;" data-name="Слой 1"
                                     xmlns="http://www.w3.org/2000/svg"
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
                                Предварительный вариант сборника

                            </a>
                        </div>
                        <div class="pre_var_right">
                            <h2>Мои исправления</h2>
                            @livewire('preview-comment',['collection_id' => $collection->id, 'own_book_id' => 0,
                            'own_book_comment_type' => 'inside'])
                        </div>
                    </div>

                @else
                    <div class="pre_var_wrap">
                        <div>
                            {{App::setLocale('ru')}}
                            <p style="font-size: 20px;">На данный момент предварительная проверка сборника завершена.
                                Сборник уже находится в печати и скоро будет отправлен авторам. Предварительная дата
                                отправки: {{ Date::parse($collection['col_date4'])->format('j F Y') }}.
                            </p>
                            <a style="display: flex; margin-top:20px;" class="button"
                               href="/{{$collection['pre_var']}}"
                               download>
                                <svg style="height: 40px; width:40px;" data-name="Слой 1"
                                     xmlns="http://www.w3.org/2000/svg"
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
                                Предварительный вариант сборника

                            </a>
                        </div>
                        <div style="justify-content: flex-start;" class="pre_var_right">
                            <h2>Мои исправления:</h2>
                            <div style=" height: auto;
                            @if(count($participation->preview_comment) === 0)
                                padding-left: 0;
                            @endif
                                "
                                 class="messages">
                                @if(count($participation->preview_comment) > 0)
                                    @foreach($participation->preview_comment as $comment)
                                        <div style="position: relative; margin-bottom:20px;" class="message">
                                            <div style="background:#47AF98" class="message-wrap">
                                                Страница {{$comment['page']}}: {{$comment['text']}}
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p>Вы не делали исправлений в этом сборнике.</p>
                                @endif
                            </div>

                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- // БЛОК ПРЕДВАРИТЕЛЬНОЙ ПРОВЕРКИ --}}

        {{-- БЛОК ГОЛОСОВАНИЯ --}}
        <div class="part"

             style="border-left: 2px
             @if ($collection['col_status_id'] === 1)
             {{$part_not_available}}
             @elseif ($collection['col_status_id'] === 2 && !isset($voted_to['user_id']))
             {{$part_action_needed}}
             @else
             {{$part_all_good}}
             @endif solid;
                 border-right: 2px
             @if ($collection['col_status_id'] === 1)
             {{$part_not_available}}
             @elseif ($collection['col_status_id'] === 2 && !isset($voted_to['user_id']))
             {{$part_action_needed}}
             @else
             {{$part_all_good}}
             @endif solid;">
            <div style="background:
             @if ($collection['col_status_id'] === 1)
            {{$part_not_available}}
            @elseif ($collection['col_status_id'] === 2 && !isset($voted_to['user_id']))
            {{$part_action_needed}}
            @else
            {{$part_all_good}}
            @endif" class="line"></div>
            @if ($collection['col_status_id'] <= 2 && !isset($voted_to['user_id']))
                <svg class="circle_status" style="fill:
             @if ($collection['col_status_id'] === 1)
                {{$part_not_available}}
                @elseif ($collection['col_status_id'] === 2 && !isset($voted_to['user_id']))
                {{$part_action_needed}}
                @else
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

            @else
                <svg id="Capa_1" class="circle_status" data-name="Capa 1" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 234.15 234.15">
                    <circle class="cls-11" cx="117.08" cy="117.08" r="114.58"/>
                    <polyline class="cls-22" points="50.03 111.7 108.85 192.66 184.12 41.49"/>
                </svg>
            @endif
            <div style="
            @if ($collection['col_status_id'] <= 3)
            @else
                box-shadow: 0 0 10px 1px {{$part_all_good}}85;
            @endif" class="container">
                <div style="border-bottom: 1px
                @if ($collection['col_status_id'] <= 3)
                {{$part_not_available}}
                @else
                {{$part_all_good}}
                @endif solid" class=hero>
                    <h2 style="color:
                    @if ($collection['col_status_id'] === 1)
                    {{$part_not_available}}
                    @elseif ($collection['col_status_id'] === 2 && !isset($voted_to['user_id']))
                    {{$part_action_needed}}
                    @else
                    {{$part_all_good}}
                    @endif;">Голосование в конкурсе</h2>
                </div>
                @if ($collection['col_status_id'] >= 2 && $participation['paid_at'] === null)
                    <div class="no-access">
                        <span>Сейчас идет голосование на лучшего автора, но из-за отствия оплаты Вы не были включены в список участников.
                        </span>
                    </div>
                @elseif ($collection['col_status_id'] >= 3 && $participation['paid_at'] <> null)
                    <div class="no-access">
                        {{App::setLocale('ru')}}
                        <p>Голосование окончено.
                            Результаты будут опубликованы в нашей <a href="https://vk.com/yourfirstbook" target="_blank"
                                                                     class="link">группе
                                ВК</a> {{ Date::parse($collection['col_date3'])->addDays(3)->format('j F Y') }}
                        </p>
                    </div>
                @else

                    @livewire('vote-block', ['collection_id' => $collection->id])
                @endif
            </div>
        </div>

        {{-- // БЛОК ГОЛОСОВАНИЯ --}}

        {{-- БЛОК ОТСЛЕЖИВАНИЯ ПОСЫЛКИ --}}
        <div class="part"

             style="padding-bottom: 25px;
             @if ($collection['col_status_id'] < 4)
                 border-bottom: 2px {{$part_not_available}} solid;
                 border-left: 2px {{$part_not_available}} solid;
                 border-right: 2px {{$part_not_available}} solid;
             @elseif($collection['col_status_id'] === 9 and $participation->printorder['paid_at'] ?? null)
                 border-bottom: 2px {{$part_action_needed}} solid;
                 border-left: 2px {{$part_action_needed}} solid;
                 border-right: 2px {{$part_action_needed}} solid;
             @elseif($collection['col_status_id'] === 9 and !($participation->printorder['paid_at'] ?? null))
                 border-bottom: 2px {{$part_all_good}} solid;
                 border-left: 2px {{$part_all_good}} solid;
                 border-right: 2px {{$part_all_good}} solid;
             @endif
                 border-radius: 0 0 10px 10px;
                 " id="print_part">
            <div style=" background:
            @if ($collection['col_status_id'] < 4)
            {{$part_not_available}};
            @elseif($collection['col_status_id'] === 9 and $participation->printorder['paid_at'] ?? null)
            {{$part_action_needed}} ;

            @elseif($collection['col_status_id'] === 9 and !($participation->printorder['paid_at'] ?? null))
            {{$part_all_good}};

            @endif" class="line"></div>

            @if ($collection['col_status_id'] === '9')
                <svg id="Слой_1" class="circle_status" style="fill:
                @if ($collection['col_status_id'] < 4){{$part_not_available}};
                @else
                {{$part_action_needed}} ;
                @endif
                    " data-name="Слой 1"
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
            @if ($collection['col_status_id'] <= 3)
            @elseif ($participation->printorder['paid_at'] ?? null)
                box-shadow: 0 0 10px 1px {{$part_action_needed}}85;
            @elseif (!($participation->printorder['paid_at'] ?? null))
                box-shadow: 0 0 10px 1px {{$part_all_good}}85;
            @endif" class="container">
                <div style="border-bottom: 1px
                @if ($collection['col_status_id'] < 4)
                {{$part_not_available}}
                @elseif($collection['col_status_id'] === 9 and $participation->printorder['paid_at'] ?? null)
                {{$part_action_needed}}

                @elseif($collection['col_status_id'] === 9 and !($participation->printorder['paid_at'] ?? null))
                {{$part_all_good}}
                @endif solid;" class=hero>
                    <h2 style="color:
                    @if ($collection['col_status_id'] < 4)
                    {{$part_not_available}};
                    @elseif($collection['col_status_id'] === 9 and $participation->printorder['paid_at'] ?? null)
                    {{$part_action_needed}} ;

                    @elseif($collection['col_status_id'] === 9 and !($participation->printorder['paid_at'] ?? null))
                    {{$part_all_good}};
                    @endif;">Отслеживание сборника</h2>
                </div>

                @if($collection['col_status_id'] <= 3)
                    <div class="no-access">
                        <span>
                             {{App::setLocale('ru')}}
                            Отслеживание станет доступно после отправки печатных экземпляров авторам:
                            {{ Date::parse($collection['col_date4'])->format('j F') }}
                        </span>
                        @if ($collection['col_status_id'] < 3 && $participation['paid_at'] <> null)
                            <br>
                            @if($participation['print_price'] > 0)
                                <a style="box-shadow: none" name="create_form" id="create_form"
                                   class="show-hide button">Редактировать заказ</a>
                                <div style="display: none" id="block_create_form" class="create_form">
                                    @livewire('collection-printorder-form', ['participation' => $participation,
                                    'form_type' => 'edit'])
                                </div>
                            @elseif($participation['print_price'] === 0)
                                <a style="box-shadow: none" name="create_form" id="create_form"
                                   class="show-hide button">Создать заказ</a>
                                <div style="display: none" id="block_create_form" class="create_form">
                                    @livewire('collection-printorder-form', ['participation' => $participation,
                                    'form_type'
                                    => 'create'])
                                </div>
                            @endif
                        @endif
                    </div>
                @elseif ($participation['printorder_id'] <> 0 && $participation->printorder['paid_at'] ?? null == null)
                    <div class="no-access">
                        <p>Сборник успешно отправлен всем авторам! Для того, чтобы получить посылку нужно произвести
                            оплату за отправление.
                            По нашим правилам оплата происходит именно в этот момент, так как стоимость мы точно
                            фиксируем только после окончания печати.
                            <br><b>Если оплата будет произведена
                                позднее {{ Date::parse($collection['col_date4'])->addDays(3)->format('j F') }} нам
                                придется заблокировать возможность получения!</b>
                            @if ($participation->printorder['send_price'])
                                <br> Стоимость именно вашего отправления: {{$participation->printorder['send_price'] ?? 0}}
                                руб.
                        </p>
                        <form style="display:inline-block"
                              action="{{ route('payment.create_send_payment', [$participation->printorder['id'] ?? null, $participation->printorder['send_price'] ?? 0])}}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <input value="{{$participation['id']}}" type="text" name="pat_id"
                                   style="display:none" class="form-control"
                                   id="pat_id">

                            <button id="btn-submit" type="submit" style="height: fit-content; max-width:250px;"
                                    class="pay-button button">
                                Оплатить пересылку
                            </button>
                        </form>
                        @else
                            Стоимость не найдена! <a href="{{route('chat_create', 'У меня проблема с пересылкой')}}"
                                                     class="link">У меня проблема с пересылкой</a>
                        @endif
                    </div>
                @elseif ($participation['printorder_id'] ?? 0 <> 0 && $participation->printorder['paid_at'] ?? null <> null)
                    <div class="no-access">
                        <p>Сборник успешно отправлен всем авторам! Вы оплатили пересылку, поэтому можете отследить ее по
                            номеру: {{$participation->printorder['track_number'] ?? "ссылка не найдена"}}.</p>
                        <a target="_blank"
                           href="https://www.pochta.ru/tracking#{{$participation->printorder['track_number'] ?? null ?? "ссылка не найдена"}}"
                           class="@if ($participation->printorder['track_number'] ?? 0 <> 0) @else amazon_link_error @endif button">Отследить</a>
                        <br><a href="{{route('chat_create', 'У меня проблема с пересылкой')}}" class="link">У меня
                            проблема с пересылкой</a>
                    </div>
                @else
                    <div class="no-access">
                        Вы не создавали заказ печатных экземпляров.
                    </div>
                @endif
            </div>
        </div>
        {{-- // БЛОК ОТСЛЕЖИВАНИЯ ПОСЫЛКИ --}}
    </div>
    </div>

    <script>
        $('#book_chat').show();
        document.getElementById('messages').scrollTop = 9999999;
        $('#book_chat').hide();

        $('#chat_button').click(function () {

            $('#book_chat').slideToggle(function () {
                if ($('#book_chat').is(":visible")) {
                    $('#chat_button').html('Свернуть чат');
                } else {
                    $('#chat_button').html('Чат по моему изданию');
                }
            });
        });
    </script>

    <script>
        $('.amazon_link_error').on('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Упс, ссылка указана неверно.',
                icon: 'error',
                html: '<p>Пожалуйста, напишите нам в чате (наверху этой страницы), и мы быстро решим проблему!</p>',
                showConfirmButton: false,
            })
        })
    </script>

@endsection
