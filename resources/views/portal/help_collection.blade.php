@extends('layouts.portal_layout')


@section('page-style')
    <link rel="stylesheet" href="/css/collection-page.css">
    <link rel="stylesheet" href="/css/create-participation.css">
    <style>
        .step p:first-child {
            color: #33b195;
            margin-bottom: 5px;
            font-size: 27px;
        }

        .list-wrap div {
            padding: 5px 10px;
        }
    </style>
@endsection



@section('page-title') Помощь @endsection

<style>
    .gif {
        border: 1px #47af98 solid;
        border-radius: 10px;
        margin: 10px auto !important;
        max-width: 1050px;
    }

    #arrow {
        width: 17px;
        fill: #47af98;
        transition: all .5s;
    }

    .mini-title {
        font-size: 30px;
        text-align: center;
        margin-top: 20px;
    }

    .link {
        font-size: 24px !important;
    }

    .faq-block {
        margin-top: -80px;
        padding-top: 80px !important;
        padding-bottom: 20px !important;
        border-bottom: 1px #E0E0E0 solid;
    }

    .content-menu {
        border-bottom: 1px #E0E0E0 solid;
        flex-direction: column;
        align-items: flex-start;
        display: flex;
        padding-bottom: 15px !important;
    }
</style>

@section('content')
    <div style="max-width: 1600px;" class="content">
        <h2 class="page-title">Инструкции</h2>

        <div style="margin: auto;" class="col-info-block">
            <div class="container">
                <div class="nav">
                    <a href="{{route('help_account')}}">Личный кабинет</a>
                    <a href="#collections" class="current">Участие в сборнках</a>
                    <a href="{{route('help_own_book')}}">Издание собственной книги</a>
                    <a href="{{route('chat_create','Вопрос по работе с платформой')}}" style="color: #2ec7a6 !important; float:right; font-size: 23px !important;" class="log_check link"><i>Другой вопрос</i></a>
                </div>
                <div style="transition: .3s ease-in-out" class="list-wrap">

                    <div class="collections" id="collections">
                        <h2 style="margin-bottom: 0; margin-top: 0 !important; padding-top: 0 !important; text-align: inherit"
                            class="mini-title">Содержание</h2>
                        <div class="content-menu">
                            <a href="#pre_text" class="link">1. Общая информация</a>
                            <a href="#application_create" class="link">2. Заполнение заявки</a>
                            <a href="#application_edit" class="link">3. Редактирование заявки</a>
                            <a href="#application_pay" class="link">4. Оплата участия</a>
                            <a href="#application_preview" class="link">5. Предварительная проверка</a>
                            <a href="#application_vote" class="link">6. Голосование в конкурсе</a>
                            <a href="#application_track" class="link">7. Отслеживание сборника</a>
                        </div>

                        <div class="faq-block" id="pre_text">
                            <h2 class="mini-title">1. Общая информация</h2>
                            <p>
                                Все сборники, в которые на данный момент идёт приём заявок, указаны на
                                <a target="_blank" href="{{route('actual_collections')}}" class="link">данной
                                    странице</a>.
                                Порядок, стоимость и условия участия указаны на странице конкретного сборника (кнопка
                                "Подробнее").
                                Чтобы принять участие в сборнике, необходимо нажать кнопку "Принять участие".
                                Для того, чтобы участвовать в сборниках вы должны загрузить свои работы в нашу систему.
                                Подробнее об этом указано в блоке
                                <a href="" class="link">"Личный кабинет -> Загрузка работ"</a>.
                            </p>


                            <div style="width:100%; margin-top:20px; text-align: center">
                                <a class="link open_gif">Смотреть видео-пример</a>
                                <img class="gif" src="/img/application start" alt="">
                            </div>
                        </div>

                        <div class="faq-block" id="application_create">
                            <h2 id="application_create" class="mini-title">2. Заполнение заявки</h2>
                            <p>
                                Для начала в заявке необходимо указать имя и фамилию автора. Если у вас есть псевдоним,
                                его
                                можно указать отдельно (необязательно).
                                В случае наличия псевдонима, автор всегда будет упоминаться только по псевдониму кроме
                                контактной информации (там необходимо указаывать всегда настоящее имя и фамилию по
                                закону).

                                </br></br>Далее нужно указать, какие именно произведения будут участвовать в
                                сборнике.
                                Для
                                этого необходимо нажать кнопку "Добавить", а затем нажимать "
                                <svg
                                    id="arrow"
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
                                " для
                                каждого произведения, которые вы сочтете нужным.

                                </br></br>В заявке вы сразу можете указать необходимое количество печатных экземпляров
                                (необязательно),
                                а также необходимость пунктуационной и орфографической проверки (необязательно).
                            </p>
                            <div style="width:100%; margin-top:20px; text-align: center">
                                <a class="link open_gif">Смотреть видео-пример</a>
                                <img class="gif" src="/img/application" alt="">
                            </div>
                        </div>

                        <div class="faq-block" id="application_edit">
                            <h2 id="application_create" class="mini-title">3. Редактирование заявки</h2>
                            <p>
                                В любой момент, пока заявка не оплачена, вы можете редактировать все параметры (кол-во
                                печатных экземпляров, произведения, информацию автора и т.д.).
                                Для этого необходимо нажать кнопку
                                <span
                                    style="box-shadow: none; padding: 0 10px; height: 22px; font-size: 16px; margin-left: auto; margin-right: 0;"
                                    class="button">Редактировать</span>
                                в блоке "Моя заявка" на странице вашего участия.
                            </p>

                            <div style="width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/edit_application.png" alt="">
                            </div>
                        </div>

                        <div class="faq-block" id="application_pay">
                            <h2 id="application_create" class="mini-title">4. Оплата участия</h2>
                            <p>
                                После того, как заявка будет отправлена, мы начнем ее проверку. Обычно проверка занимает
                                несколько часов.
                                После прохождения проверки вам поступит Email оповещение о том, что заявку необходимо
                                оплатить, чтобы уже полноценно быть включенным в сборник.
                                <br> В этот момент в блоке "Оплата" на странице участия будет доступна кнока
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
                                <img class="gif" src="/img/pay_application.png" alt="">
                            </div>
                        </div>

                        <div class="faq-block" id="application_preview">
                            <h2 id="application_create" class="mini-title">5. Предварительная проверка</h2>
                            <p>
                                Каждый наш сборник проходит предварительную проверку авторов. В указанную дату на
                                странице
                                вашего участия станет доступен блок "Предварительная проверка".
                                В нем вы сможете скачать PDF файл сборника и исправить любые неточности вашего блока.
                                Для
                                этого необходимо указать страницу ошибки и ее описание в форме в правой части блока.
                            </p>

                            <div style="width:100%; margin-top:20px; text-align: center">
                                <a class="link open_gif">Смотреть видео-пример</a>
                                <img class="gif" src="/img/make_collection_comment" alt="">
                            </div>
                        </div>

                        <div class="faq-block" id="application_vote">
                            <h2 id="application_create" class="mini-title">6. Голосование в конкурсе</h2>
                            <p>
                                В сборниках мы проводим конкурс на лучшего автора. В указанную дату на странице вашего
                                участия станет доступен блок "Голосование в конкурсе".
                                В нем вы сможете проголосовать за любого понравившегося автора (кроме себя). Результаты
                                конкурса будут объявлены в течение нескольких дней после завершения конкурса в нашей
                                <a href="https://www.vk.com" class="link">группе ВК</a>.
                            </p>

                            <div style="width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/vote.png" alt="">
                            </div>
                        </div>

                        <div style="border-bottom: none !important;" class="faq-block" id="application_track">
                            <h2 id="application_create" class="mini-title">7. Отслеживание сборника</h2>
                            <p>
                                Как только сборник пройдет все этапы предварительной проверки, мы отправим
                                заронированные
                                печатные экземпляры авторам.
                                Сразу после отправки в блоке "Отслежвание сборника" на странице участия будет доступна
                                ссылка для отслеживания, а также трек-номер для ручной проверки.
                            </p>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $('.open_gif').on('click', function() {
            cur_href = $(this).siblings().attr('src');
            $(this).hide();
            $(this).siblings().attr('src', cur_href + '.gif');

        })
    </script>

@endsection

