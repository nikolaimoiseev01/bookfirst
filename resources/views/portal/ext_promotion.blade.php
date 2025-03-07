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
                    <img class="cover" src="/img/ext_promotion_icon.png" alt="">
                </div>
                <div class="right-collection-info">
                    <div class="col-text">
                        <h3>Привлечение читателей на других сайтах</h3>
                        <p>Кроме составления различных литературных сборников и издания книг, мы также предлагаем
                            необычную услугу: продвижение вашего творчества на литературных интернет-порталах. Мы в
                            десятки раз увеличиваем количество посетителей ваших авторских страниц. Данная услуга - это
                            не литературное продвижение или раскрутка вашего творчества. Это расширение аудитории ваших
                            произведений, реклама ваших творений. Всё остальное зависит лишь от вас и ваших
                            произведений!
                        </p>
                    </div>
                    <div class="col-card">
                        <div class="container">
                            <div class="row">
                                Сайты для продвижения:&nbsp;<span>stihi.ru, proza.ru</span>
                            </div>
                            <div class="row">
                                "Возраст" аккаунта:&nbsp;<span>от 4-х недель</span>
                            </div>
                            <div class="row">
                                Новых читателей:&nbsp;<span>до <b>400</b> в сутки</span>
                            </div>
                            <div class="row">
                                <a style="    font-size: 25px; padding: 3px 35px;"
                                   href="{{route('make_ext_promotion')}}"
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
                    <a href="#security" class="cont_nav_item">Безопасность</a>
                    <a style="float: right;" href="{{route('help_ext_promotion')}}" target="_blank">Инструкция</a>
                </div>
                <div style="" class="list-wrap">

                    <div id="process" class="process">
                        <div class="process-slider">
                            <div class="step">
                                <p>Шаг 1. Заполнение заявки</p>
                                <p> Нажмите "Подать заявку", чтобы начать процесс продвижения.
                                    При заполнении нужно будет указать конкретный сайт, на котором планируется
                                    продвижение.
                                    <br>
                                    <i style="color: #47AF98">Оплата производится только после подтверждения нашей
                                        готовности продвигать вашу страницу!</i>
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
                                    Срок проверки - не более суток.
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
                                    После оплаты продвижение запускается в течение суток. Как только оно запустится, вы
                                    получите оповещения,
                                    а на странице процесса будет вся информация: статистика, оставшиеся дни и т.д.
                                </p>
                            </div>
                            <div class="step">
                                <p>Шаг 5. Продление услуги</p>
                                <p>
                                    Как только оплаченный период подойдет к концу, продвижение автоматически
                                    остановится.
                                    Его можно будет продлить на странице процесса.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="calculator" class="hide">
                        @livewire('portal.ext-promotion-calc')
                    </div>

                    <div id="security" class="secutity_wrap hide">
                        <p>Учитывая, что для запуска услуги по привлечению читателей на любом сайте нам требуется пароль
                            от авторской страницы, то вполне закономерно, что у наших клиентов возникает вопрос о
                            безопасности передачи нам этого пароля, ведь мы получаем доступ к странице.</p><br><br>
                        <p><b>Ниже приведены аргументы того, почему это безопасно:</b><br>
                        <ul>
                            <li>Мы нигде не храним ваши логины и пароли. Все данные для авторизации (логин или адрес
                                страницы, и
                                пароль) шифруются и потом используются нами в зашифрованном виде. Все письма, сообщения
                                в соц.
                                сетях и т.п., в которых эти данные нам передали, мы удаляем безвозвратно.
                            </li>
                            <li>Получая полный доступ к вашей авторской странице, мы не совершаем никаких действий от
                                вашего
                                имени, кроме тех, которые оговорены услугой и без которых невозможно привлечь читателей
                                на
                                страницу. В частности, мы не пишем сообщения/рецензии/отзывы и др., мы не заказываем
                                баллы/анонсы и т.д., не общаемся с другими пользователями сайтов, не пользуемся никакими
                                дополнительными платными или бесплатными услугами сайтов от вашего имени. Это возможно
                                только
                                если вы сами нас об этом попросите (например, в рамках дополнительной услуги по
                                сопровождению
                                страницы).
                            </li>
                            <li>Мы ни в коем случае не редактируем, не удаляем, не добавляем произведения.</li>
                            <li>Мы категорически ничего не делаем в настройках страницы.</li>

                        </ul>

                        <br><br>
                        <p>
                            Хотим обратить ваше внимание на то, что нам абсолютно невыгодно делать что-то с авторской
                            страницей, рискуя тем, что вы это заметите (а вы это заметите в 95% случаев) и, наверняка,
                            больше не будете с нами сотрудничать. При этом расскажите другим людям.<br>
                            Также мы крайне заинтересованы в том, чтобы ваш пароль ни в коем случае не был украден, так
                            как
                            это также чревато потерей вас как клиента, и неминуемо отразится на нашей репутации.</p>
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
