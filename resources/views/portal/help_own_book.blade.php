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
                <a href="{{route('help_collection')}}">Участие в сборниках</a>
                <a href="#own_books" class="current">Издание собственной книги</a>
                <a href="{{route('chat_create','Вопрос по работе с платформой')}}"
                   style="color: #2ec7a6 !important; float:right; font-size: 23px !important;"
                   class="log_check link"><i>Другой вопрос</i></a>
            </div>
            <div class="list-wrap">
                <div class="own_books" id="own_books">
                    <h4>Содержание</h4>
                    <div class="content-menu">
                        <a href="#pre_text" class="link">1. Общая информация</a>
                        <a href="#application_create" class="link">2. Заполнение заявки</a>
                        <div style="padding-top: 0; padding-left: 20px;">
                            <a style="font-size:22px !important;" href="#app_author" class="link"><i>Автор и название
                                    книги</i></a>
                            <br><a style="font-size:22px !important;" href="#app_inside" class="link"><i>Внутренни
                                    блок</i></a>
                            <br><a style="font-size:22px !important;" href="#app_cover" class="link"><i>Обложка</i></a>
                            <br><a style="font-size:22px !important;" href="#app_print" class="link"><i>Печатные
                                    экземпляры</i></a>
                            <br><a style="font-size:22px !important;" href="#app_promo" class="link"><i>Продвижение</i></a>
                        </div>
                        <a href="#application_pay" class="link">3. Оплата издания</a>
                        <a href="#application_preview" class="link">4. Предварительная проверка макетов</a>
                        <a href="#application_track" class="link">5. Отслеживание печати</a>
                    </div>

                    <div class="faq-block" id="pre_text">
                        <h4 class="mini-title">1. Общая информация</h4>
                        <p>
                            Всю подробную информацию о процессе стоимости издания и печати собственной книги вы можете
                            узнать на
                            <a target="_blank" href="{{route('own_book_page')}}" class="link">странице собственно
                                книги</a>.
                            Для того, чтобы начать процесс, необходимо отправить заявку на издание. Для этого нужно
                            нажать на кнопку "Подать заявку".
                        </p>


                        <div style="width:100%; margin-top:20px; text-align: center">
                            <a class="link open_gif">Смотреть видео-пример</a>
                            <img class="gif" src="/img/path_to_own_book" alt="">
                        </div>
                    </div>

                    <div class="faq-block" id="application_create">
                        <h4 id="application_create" class="mini-title">2. Заполнение заявки</h4>
                        <p>
                            Заявка состоит из нескольких блоков:

                        <h4 class="sub_title" id="app_author">Автор и название книги</h4>
                        <p>
                            В поле автор можно указывать в том числе псевдоним. Информация из этих полей будет
                            использоваться на всем протяжении издания.
                        </p>

                        <h4 id="app_inside" class="sub_title">Внутренни блок</h4>
                        <p>
                            Для начала издания у вас должны быть какие-то наработки внутреннего блока. Вы можете
                            загрузить его файлами (формат: DocX, Doc, PDF)
                            или выбрать из нашей системы. Инструкция того, как произведения могут быть загружены в нашу
                            систему, указаны в блоке
                            <a href="" class="link">"Личный кабинет -> Загрузка работ"</a>. <b>Важно понимать степень
                                готовности внутреннего блока.</b>
                            Он может считаться готовым только если соблюдены все правила профессиональной печати (поля
                            от края страницы минимум 1.5 см., произведена дизайнерская работа со шрифтами и стилями
                            текста и т.д.)
                        </p>

                        <h4 id="app_cover" class="sub_title">Обложка</h4>
                        <p>Если у вас нет полностью готовой обложки (изображение формата PDF размера А5 с плоностью не
                            менее 300 ppi), вы можете указать комментарий по тому,
                            как вы видите будущую обложку, а также прикрепить любые файлы, которые могут помочь с
                            созданием обложки.
                        </p>

                        <h4 id="app_print" class="sub_title">Печатные экземпляры</h4>
                        <p> В заявке вы можете сразу указать, сколько печатных экземпляров необходимо. <b>Важно: </b>
                            эта стоимость является близкой, но не идеально точной.
                            Так как количество и цветность страниц может измениться в процессе работы над макетами,
                            окончательная стоимость печати будет известна только после
                            двустороннего утверждения макетов (обложки и внутреннего блока).
                        </p>

                        <h4 id="app_promo" class="sub_title">Продвижение</h4>
                        <p> Мы предоставляем два вида услуг продвижения. В первом варианте изданная книга только
                            появляется в нашем разделе "книги наших авторов".
                            Во втором варианте мы размещаем информацию о книге в наших соцсетях, на главной странице
                            сайта, а также рекламируем ее у наших партнеров по возможности.
                        </p>


                        <style>
                            iframe {
                                width: 100%;
                                max-width: 700px;
                            }
                        </style>

                        <div style="width:100%; margin-top:20px; text-align: center">
                            <iframe width="50%" height="422" src="https://www.youtube.com/embed/dbKIzU6p0Vk"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>


                    <div class="faq-block" id="application_pay">
                        <h4 id="application_create" class="mini-title">3. Оплата издания</h4>
                        <p>
                            После того, как заявка будет отправлена, мы начнем ее проверку. Обычно проверка занимает
                            несколько часов.
                            После прохождения проверки вам поступит Email оповещение о том, что заявку необходимо
                            оплатить, чтобы непосредственно начать процесс работы.
                            <b>На данном этапе оплата происходит за все услуги кроме печати, так как количество страниц
                                может измениться после всех работ с внутренним блоком.</b>
                            <br> В этот момент в блоке "Оплата" на странице издания будет доступна кнока
                            <button id="btn-submit" type="submit"
                                    style="font-size: 18px; padding: 0 10px; color: #ffa500; background: white; border: 1px #ffa500 solid; box-shadow: none; margin-top: 10px;height: fit-content; max-width:250px;"
                                    class="pay-button button">
                                Оплатить XXX руб.
                            </button>
                            . По ней вы перейдете на защищенную форму оплаты нашего партнера "Яндекс", в которой
                            сможете
                            выбрать наиболее удобный способ оплаты.
                        </p>

                        <div style="width:100%; margin-top:20px; text-align: center">
                            <img style="max-width: fit-content !important; display: inherit !important;" class="gif"
                                 src="/img/pay_own_book.png" alt="">
                        </div>
                    </div>

                    <div class="faq-block" id="application_preview">
                        <h4 id="application_create" class="mini-title">4. Предварительная проверка макетов</h4>

                        <p>
                            Как только мы подготовим первый вариант внутреннего блока, вы получите оповещение об этом по
                            почте.
                            В этот момент станет доступен пункт предварительной проверки. Он разделен на проверку
                            внутреннего блока и обложки. Работа по ним может быть независима и иметь разные статусы.
                            С любым присланным вариантом необходимо сделать одно из двух действий:
                        </p>


                        <h4 id="app_cover"
                            style="margin-top:-80px; padding-top: 80px; margin-bottom: 0; font-size: 25px;">I. Описать
                            изменения</h4>
                        <p>
                            Для этого в форме нужно указать каждое исправление отдельно. В случае со внутренним блоком
                            необходимо указать страницу измениня.
                            Как только все изменения указаны, нужно нажать на "отправить на исправления". Только так мы
                            сможем понять, что все исправления указаны, и мы начнем работу по их редаутированию.
                        </p>

                        <div style="width:100%; margin-top:20px; text-align: center">
                            <a class="link open_gif">Смотреть видео-пример</a>
                            <img class="gif" src="/img/preview_inside" alt="">
                        </div>

                        <h4 id="app_cover"
                            style="margin-top:-80px; padding-top: 80px; margin-bottom: 0; font-size: 25px;">II.
                            Утвердить макет</h4>
                        <p>
                            Если с макетом все в порядке, то его необходимо утвердить к дальнейшей печати (при наличии
                            опции печати).
                            Как только и внутренний блок и макет будут утверждены, мы сможем перейти к следующему этапу
                            издания книги.
                        </p>

                        <div style="width:100%; margin-top:20px; text-align: center">
                            <img style="max-width: fit-content !important; display: inherit !important;" class="gif"
                                 src="/img/cover_preview_done.png" alt="">
                        </div>
                    </div>

                    <div style="border-bottom: none !important;" class="faq-block" id="application_track">
                        <h4 id="application_create" class="mini-title">5. Отслеживание печати</h4>
                        <p>
                            Как только книга пройдет все этапы предварительной проверки, мы отправим
                            заронированные печатные экземпляры. По нашим правилам оплата пересылки происходит отдельно.
                            Мы отправим книгу за свой счет, но получить ее можно будет <b>только</b> после оплаты
                            фактической суммы пересылки.
                            МЫ делаем так, потому что заранее тяжело расчитать точные затраты на пересылку.
                            Ссылка на оплату будет доступна в блоке "Отслеживание пересылки".
                            Сразу после оплаты на странице издания будет доступна
                            ссылка для отслеживания, а также трек-номер для ручной проверки.
                        </p>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('page-js')

    <script>
        $('.open_gif').on('click', function() {
            cur_href = $(this).siblings().attr('src');
            $(this).hide();
            $(this).siblings().show();
            $(this).siblings().attr('src', cur_href + '.gif');

        })
    </script>
@endpush

