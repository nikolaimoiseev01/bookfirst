@extends('layouts.portal_layout')


@section('page-title') Помощь @endsection



@section('content')
    <div class="help_pages_wrap page_content_wrap">
        <div class="header_wrap">
            <h2>Инструкции</h2>
        </div>


        <div class="container">
            <div class="nav">
                <a href="{{route('help_account')}}">Личный кабинет</a>
                <a href="#collections">Участие в сборниках</a>
                <a href="{{route('help_own_book')}}">Издание собственной книги</a>
                <a href="{{route('help_ext_promotion')}}" class="current">Продвижение</a>
                <a href="{{route('chat_create','Вопрос по работе с платформой')}}"
                   style="color: #2ec7a6 !important; float:right; font-size: 23px !important;"
                   class="log_check link"><i>Другой вопрос</i></a>
            </div>
            <div class="list-wrap">

                <h4>Содержание</h4>
                <div class="content-menu">
                    <a href="#pre_text" class="link">1. Общая информация</a>
                    <a href="#application_create" class="link">2. Заполнение заявки</a>
                    <a href="#application_pay" class="link">3. Оплата продвижения</a>
                    <a href="#stat" class="link">4. Анализ статистики продвижения</a>
                </div>

                <div class="collections" id="collections">

                    <div class="faq-block" id="pre_text">
                        <h4>1. Общая информация</h4>
                        <p>
                            Подробный процесс, порядок, стоимость и условия продвижения указаны на
                            <a target="_blank" href="{{route('ext_promotion')}}" class="link">данной
                                странице</a>.
                            Чтобы принять участие в сборнике, необходимо нажать кнопку "Подать заявку!".
                        </p>


                        <div style="width:100%; margin-top:20px; text-align: center">
                            <img style="max-width: 900px !important; display: inherit !important;" class="png gif"
                                 src="/img/help_ext_promotion_portal.png" alt="">
                        </div>
                    </div>

                    <div class="faq-block" id="application_create">
                        <h4 id="application_create" class="mini-title">2. Заполнение заявки</h4>
                        <p>
                            В заявке необходимо:
                        </p>
                        <ul>
                            <li>Выбрать сайт, на котором планируется продвижение</li>
                            <li>Указать свое согласие с правилами. Их можно прочитать, нажав на ссылку в чекбоксе</li>
                            <li>Указать логин от сайта, на котором планируется продвижение</li>
                            <li>Указать пароль от сайта, на котором планируется продвижение</li>
                            <li>Выбрать количество дней, на которое планируется продвижение</li>
                        </ul>
                        <div style="width:100%; margin-top:20px; text-align: center">
                            <img style="max-width: 900px !important; display: inherit !important;" class="png gif"
                                 src="/img/help_ext_promotion_app.png" alt="">
                        </div>
                    </div>

                    <div class="faq-block" id="application_pay">
                        <h4 id="application_create" class="mini-title">3. Оплата Продвижения</h4>
                        <p>
                            После того, как заявка будет отправлена, мы начнем ее проверку. Обычно проверка занимает
                            несколько часов.
                            После прохождения проверки вам поступит Email оповещение о том, что заявку необходимо
                            оплатить, чтобы уже начать процесс.
                            <br> В этот момент в блоке "Оплата" на странице участия будет доступна кнока "Оплатить XXX руб.". По ней вы перейдете на защищенную форму оплаты нашего партнера "Яндекс", в которой
                            сможете
                            выбрать наиболее удобный способ оплаты.
                        </p>

                        <div style="width:100%; margin-top:20px; text-align: center">
                            <img style="max-width: 900px !important; display: inherit !important;" class="png gif"
                                 src="/img/help_ext_promotion_pay.png" alt="">
                        </div>
                    </div>

                    <div class="faq-block" id="application_preview">
                        <h4 id="stat" class="mini-title">4. Анализ статистики продвижения</h4>
                        <p>Когда продвижение начнется, в блоке внизу можно отслеживать рост читателей на сайте. При необходимо статистику можно обновить вручную, нажав на ссылку "Обновить данные".</p>

                        <div style="width:100%; margin-top:20px; text-align: center">
                            <img style="max-width: 900px !important; display: inherit !important;" class="png gif"
                                 src="/img/help_ext_promotion_stat.png" alt="">
                        </div>
                    </div>



                </div>
            </div>
        </div>

    </div>

@endsection

@push('page-js')
    <script>
        $('.open_gif').on('click', function () {
            cur_href = $(this).siblings().attr('src');
            $(this).hide();
            $(this).siblings().show();
            $(this).siblings().attr('src', cur_href + '.gif');

        })
    </script>
@endpush

