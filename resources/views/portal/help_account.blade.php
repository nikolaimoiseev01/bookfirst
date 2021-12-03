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
                    <a href="#registration" class="current">Личный кабинет</a>
                    <a href="{{route('help_collection')}}">Участие в сборнках</a>
                    <a href="{{route('help_own_book')}}">Издание собственной книги</a>
                </div>
                <div style="transition: .3s ease-in-out" class="list-wrap">
                    <div id="registration" class="registration">
                        <h2 style="margin-bottom: 0; margin-top: 0 !important; padding-top: 0 !important; text-align: inherit"
                            class="mini-title">Содержание</h2>
                        <div class="content-menu">
                            <a href="#registration" class="link">1. Регистрация</a>
                            <a href="#login" class="link">2. Вход</a>
                            <a href="#account_1" class="link">3. Разделы "Мои сборники" и "Собственные книги"</a>
                            <a href="#account_2" class="link">4. Загрузка работ</a>
                            <a href="#account_3" class="link">5. Управление покупками</a>
                            <a href="#account_4" class="link">6. Создание чатов</a>
                        </div>
                        <div class="faq-block" id="registration">
                            <h2 class="mini-title">1. Регистрация</h2>
                            <p>Для того, чтобы пользоваться любыми услугами независимого издательства, необходимо
                                произвести
                                регистрацию на портале.
                                Для этого необходимо нажать на пункт "Войти" в верхнем меню и далее выбрать блок
                                "Регистрация.
                                Затем нужно заполнить все достоверные данные (на указанный email придет письмо с
                                подтверждением регистрации).
                                Как только форма будет заполнена, вам придет Email сообщение.
                                <span style="color: #1aa083;">Чтобы полностью завершить регистрацию, необходимо нажать на "Подтвердить Email" в сообщении.</span>
                            </p>
                            <div style="width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/registration.gif" alt="">
                            </div>
                        </div>

                        <div class="faq-block" id="login">
                            <h2 class="mini-title">2. Вход</h2>
                            <p>
                                Как только вы успешно прошли регистрацию, вы сможете входить в свой аккаунт с любого устройства.
                                Для этого перейдите в раздел "Войти". Далее введите вашу электронную почту и пароль, указанные при регистрации, и нажмите "Войти".
                                Если вы забыли свой пароль, вы можете воспользоваться <a target="_blank" href="{{ route('password.request') }}"
                                                                                                class="link">формой восстановления пароля</a>
                            </p>
                        </div>

                        <div class="faq-block" id="account_1">
                            <h2 class="mini-title">3. Разделы "Мои сборники" и "Собственные книги"</h2>
                            <p>Как только вы заполните заявку на участие в сборниках современных авторов, это сразу отобразится в разделе "Мои сборники".
                                С этой страницы можно перейти на подробную индивидуальную информацию о вашем участии.
                            </p>
                            <div style="width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/my_collections_page.png" alt="">
                            </div>

                            <p>Как только вы начнете процесс издания собственной книги, в разделе "Собственные книги" сразу появятся блок с информацией о процессе.
                                Как в случае со сборниками, кликнув на "Страница издания" вы попадете на страницу с подробнейшей информацией процесса издания.
                                На этой странице вы сможете следить за предварительными материалами, оплачивать любую услугу, отслеживать печатные экземпляры и т.д.
                            </p>
                            <div style="width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/my_books_page.png" alt="">
                            </div>
                        </div>

                        <div class="faq-block" id="account_2">
                            <h2 class="mini-title">4. Загрузка работ</h2>
                            <p>Чтобы участвовать в сборниках, а также быстро загружать произведения при создании собственной книги,
                                вы должны сперва их добавить в нашу систему систему. Сделать это можно двумя способами:
                            </p>
                            <h2 style="margin-bottom: 0; font-size: 25px;">Вручную</h2>
                            <p>Так можно загружать произведение по одному, вручную указывая название и непосредственно тело произведения.</p>
                            <div style="display: flex; flex-wrap: wrap; width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/add_work_manual.png" alt="">
                                <img class="gif" src="/img/add_work_doc.png" alt="">
                            </div>
                            <h2 style="margin-bottom: 0; font-size: 25px;">Из файла DocX</h2>
                            <p>
                                Так можно загружать большое количество произведений разом. Для этого файл должен быть отформатирован по правилам, указанным на странице загрузки.
                                Когда система проанализирует файл, у вас будет возможность отредактировать автоматический анализ: поменять названия, текст или удалить какие-то неверно распознанные произведения.
                            </p>
                            <div style="width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/work_from_doc.gif" alt="">
                            </div>
                        </div>

                        <div class="faq-block" id="account_3">
                            <h2 class="mini-title">5. Управление покупками</h2>
                            <p>На нашем портале у вас есть возможно покупать электронные верси сборников современных поэтов, а также книг наших авторов.
                                Все ваши покупки будут расположены в этом разделе. Здесь вы всегда сможете скачать PDF версии купленных книг.
                            </p>
                        </div>

                        <div class="faq-block" id="account_4">
                            <h2 class="mini-title">6. Создание чатов</h2>
                            <p>В любой момент вы можете создать чат с поддержкой на любую тему.
                                <b>Важно: </b> пожалуйста, не создавайте чаты по вашему участию или изданию книги, если процесс уже начался.
                                То есть, если вы уже принимаете участие в сборнике или идет процесс издания вашей книги,
                                то для связи с нами по конкретно вашему процессу всегда можно использовать специальный чат, расположенный на страницах издания/участия.
                            </p>
                        </div>

                    </div>

                    <div class="hide" id="own_books">
                        <h2 style="margin-bottom: 0; margin-top: 0 !important; padding-top: 0 !important; text-align: inherit"
                            class="mini-title">Содержание</h2>
                        <div class="content-menu">
                            <a href="#pre_text" class="link">1. Общая информация</a>
                            <a href="#application_create" class="link">2. Заполнение заявки</a>
                            <a href="#application_edit" class="link">3. Редактирование заявки</a>
                            <a href="#application_pay" class="link">4. Оплата издания</a>
                            <a href="#application_preview" class="link">5. Предварительная проверка макетов</a>
                            <a href="#application_track" class="link">7. Отслеживание печати</a>
                        </div>

                        <div class="faq-block" id="pre_text">
                            <h2 class="mini-title">1. Общая информация</h2>
                            <p>
                                Всю подробную информацию о процессе и стоимости издания и печати собственной книги вы можете узнать на
                                <a target="_blank" href="{{route('own_book_page')}}" class="link">странице собственно книги</a>.
                                Для того, чтобы начать процесс, необходимо отправить заявку на издание. Для этого нужно нажать на кнопку "Подать заявку".
                            </p>


                            <div style="width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/path_to_own_book.gif" alt="">
                            </div>
                        </div>

                        <div class="faq-block" id="application_create">
                            <h2 id="application_create" class="mini-title">2. Заполнение заявки</h2>
                            <p>
                                Заявка состоит из нескольких блоков:

                            <h2 style="margin-bottom: 0; font-size: 25px;">Автор и название книги</h2>
                            <p>
                                В поле автор можно указывать в том числе псевдоним. Информация из этих полей будет использоваться на всем протяжении издания.
                            </p>

                            <h2 style="margin-bottom: 0; font-size: 25px;">Внутренни блок</h2>
                            <p>
                                Для начала издания у вас должны быть какие-то наработки внутреннего блока. Вы можете загрузить его файлами (формат: DocX, Doc, PDF)
                                или выбрать из нашей системы. Инструкция того, как произведения могут быть загружены в нашу систему указаны в блоке
                                <a href="" class="link">"Личный кабинет -> Загрузка работ"</a>. <b>Важно понимать степень готовности внутреннего блока.</b>
                                Он может считаться готовым только если соблюдены все правила профессиональной печати (поля от края страницы минимум 1.5 см., произведена дизайнерская работа со шрифтами и стилями текста и т.д.)
                            </p>

                            <h2 style="margin-bottom: 0; font-size: 25px;">Обложка</h2>
                            <p>Если у вас нет полностью готовой обложки (Изображение формата PDF размера А5 с плоностью не менее 300 ppi), вы можете указать комментарий по тому,
                                как вы видите будущую обложку, а также прикрепить любые файлы, которые могут помочь с созданием обложки.
                            </p>

                            <h2 style="margin-bottom: 0; font-size: 25px;">Печатные экземпляры</h2>
                            <p> В заявке вы можете сразу указать, сколько печатных экземпляров необходимо. <b>Важно: </b> эта стоимость является близкой, но не идеально точной.
                                Так как количество и цветность страниц может измениться в процессе работы над макетами, окончательная стоимость печати будет известна только после
                                двустороннего утверждения макетов (обложки и внутреннего блока).
                            </p>

                            <h2 style="margin-bottom: 0; font-size: 25px;">Продвижение</h2>
                            <p> Мы предоставляем два вида услуг продвижения. В первом варианте изданная книга только появляется в нашем разделе "книги наших авторов".
                            Во втором варианте мы размещаем информацию о книге в наших соцсетях, на главной странице сайта, а также рекламируем ее у наших партнеров по возможности.
                            </p>

                            <div style="width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/application.gif" alt="">
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
                            <h2 id="application_create" class="mini-title">4. Оплата издания</h2>
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
                            <h2 id="application_create" class="mini-title">5. Предварительная проверка макетов</h2>
                            <p>
                                Каждый наш сборник проходит предварительную проверку авторов. В указанную дату на
                                странице
                                вашего участия станет доступен блок "Предварительная проверка".
                                В нем вы сможете скачать PDF файл сборника и исправить любые неточности вашего блока.
                                Для
                                этого необходимо указать страницу ошибки и ее описание в форме в правой части блока.
                            </p>

                            <div style="width:100%; margin-top:20px; text-align: center">
                                <img class="gif" src="/img/make_collection_comment.gif" alt="">
                            </div>
                        </div>

                        <div style="border-bottom: none !important;" class="faq-block" id="application_track">
                            <h2 id="application_create" class="mini-title">7. Отслеживание печати</h2>
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

@endsection

