@extends('layouts.portal_layout')


@section('page-title') Помощь @endsection

@section('content')
    <div class="help_pages_wrap page_content_wrap">
        <div class="header_wrap">
            <h2>Инструкции</h2>
        </div>

        <div class="container">
            <div class="nav">
                <a href="#registration" class="current">Личный кабинет</a>
                <a href="{{route('help_collection')}}">Участие в сборниках</a>
                <a href="{{route('help_own_book')}}">Издание собственной книги</a>
                <a href="{{route('help_ext_promotion')}}">Продвижение</a>
                <a href="{{route('chat_create','Вопрос по работе с платформой')}}"
                   style="color: #2ec7a6 !important; float:right; font-size: 23px !important;"
                   class="log_check link"><i>Другой вопрос</i></a>
            </div>
            <div class="list-wrap">
                <h4>Содержание</h4>
                <div class="content-menu">
                    <a href="#registration" class="link">1. Регистрация</a>
                    <a href="#login" class="link">2. Вход</a>
                    <a href="#account_1" class="link">3. Разделы "Мои сборники" и "Собственные книги"</a>
                    <a href="#account_2" class="link">4. Загрузка работ</a>
                    <a href="#account_3" class="link">5. Управление покупками</a>
                    <a href="#account_4" class="link">6. Создание чатов</a>
                </div>

                <div id="registration" class="registration">

                    <div class="faq-block" id="registration">
                        <h4 class="mini-title">1. Регистрация</h4>
                        <p>Для того, чтобы пользоваться любыми услугами независимого издательства, необходимо
                            произвести
                            регистрацию на портале.
                            Для этого необходимо нажать на пункт "Войти" в верхнем меню и далее выбрать блок
                            "Регистрация".
                            Затем нужно заполнить все достоверные данные (на указанный email придет письмо с
                            подтверждением регистрации).
                            Как только форма будет заполнена, вам придет Email сообщение.
                            <span class="green">Чтобы полностью завершить регистрацию, необходимо нажать на "Подтвердить Email" в сообщении.</span>
                        </p>
                        <div>
                            <a class="link open_gif">Смотреть видео-пример</a>
                            <img src="/img/registration" class="lazyload gif" alt="">
                        </div>
                    </div>

                    <div class="faq-block" id="login">
                        <h4 class="mini-title">2. Вход</h4>
                        <p>
                            Как только вы успешно прошли регистрацию, вы сможете входить в свой аккаунт с любого
                            устройства.
                            Для этого перейдите в раздел "Войти". Далее введите вашу электронную почту и пароль,
                            указанные при регистрации, и нажмите "Войти".
                            Если вы забыли свой пароль, вы можете воспользоваться <a target="_blank"
                                                                                     href="{{ route('password.request') }}"
                                                                                     class="link">формой восстановления
                                пароля</a>
                        </p>
                    </div>

                    <div class="faq-block" id="account_1">
                        <h4 class="mini-title">3. Разделы "Мои сборники" и "Собственные книги"</h4>
                        <p>Как только вы заполните заявку на участие в сборниках современных авторов, это сразу
                            отобразится в разделе "Мои сборники".
                            С этой страницы можно перейти на подробную индивидуальную информацию о вашем участии.
                        </p>
                        <div style="width:100%; margin-top:20px; text-align: center">
                            <img style="max-width: fit-content !important; display: inherit !important;" class="gif"
                                 src="/img/my_collections_page.png" alt="">
                        </div>

                        <p>Как только вы начнете процесс издания собственной книги, в разделе "Собственные книги" сразу
                            появятся блок с информацией о процессе.
                            Как в случае со сборниками, кликнув на "Страница издания" вы попадете на страницу с
                            подробнейшей информацией процесса издания.
                            На этой странице вы сможете следить за предварительными материалами, оплачивать любую
                            услугу, отслеживать печатные экземпляры и т.д.
                        </p>
                        <div style="width:100%; margin-top:20px; text-align: center">
                            <img style="max-width: fit-content !important; display: inherit !important;" class="gif"
                                 src="/img/my_books_page.png" alt="">
                        </div>
                    </div>

                    <div class="faq-block" id="account_2">
                        <h4 class="mini-title">4. Загрузка работ</h4>
                        <p>Чтобы участвовать в сборниках, а также быстро загружать произведения при создании собственной
                            книги,
                            вы должны сперва их добавить в нашу систему. Сделать это можно двумя способами:
                        </p>
                        <h2 style="margin-bottom: 0; font-size: 25px;">Вручную</h2>
                        <p>Так можно загружать произведение по одному, вручную указывая название и непосредственно тело
                            произведения.</p>
                        <div style="display: flex; flex-wrap: wrap; width:100%; margin-top:20px; text-align: center">
                            <img style="max-width: fit-content !important; display: inherit !important;" class="gif"
                                 src="/img/add_work_manual.png" alt="">
                            <img style="max-width: fit-content !important; display: inherit !important;" class="gif"
                                 src="/img/add_work_doc.png" alt="">
                        </div>
                        <h2 style="margin-bottom: 0; font-size: 25px;">Из файла DocX</h2>
                        <p>
                            Так можно загружать большое количество произведений разом. Для этого файл должен быть
                            отформатирован по правилам, указанным на странице загрузки.
                            Когда система проанализирует файл, у вас будет возможность отредактировать автоматический
                            анализ: поменять названия, текст или удалить какие-то неверно распознанные произведения.
                        </p>
                        <div>
                            <a class="link open_gif">Смотреть видео-пример</a>
                            <img src="/img/work_from_doc" class="lazyload gif" alt="">
                        </div>
                    </div>

                    <div class="faq-block" id="account_3">
                        <h4 class="mini-title">5. Управление покупками</h4>
                        <p>На нашем портале у вас есть возможно покупать электронные верси сборников современных поэтов,
                            а также книг наших авторов.
                            Все ваши покупки будут расположены в этом разделе. Здесь вы всегда сможете скачать PDF
                            версии купленных книг.
                        </p>
                    </div>

                    <div class="faq-block" id="account_4">
                        <h4 class="mini-title">6. Создание чатов</h4>
                        <p>В любой момент вы можете создать чат с поддержкой на любую тему.
                            <b>Важно: </b> пожалуйста, не создавайте чаты по вашему участию или изданию книги, если
                            процесс уже начался.
                            То есть, если вы уже принимаете участие в сборнике или идет процесс издания вашей книги,
                            то для связи с нами по конкретно вашему процессу всегда можно использовать специальный чат,
                            расположенный на страницах издания/участия.
                        </p>
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

