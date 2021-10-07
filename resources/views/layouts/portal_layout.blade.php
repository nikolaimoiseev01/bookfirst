<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('page-style')
    <link rel="stylesheet" href="{{ asset('css/portal-media.css') }}">
    <script src="/js/jquery.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="https://kit.fontawesome.com/f0b80ff062.js" crossorigin="anonymous"></script>
    <title>Первая Книга: @yield('page-title')</title>
</head>

<body>
<div id="modal_login" class="modal">
    <div class="modal-wrap">
        <div class="modal-content">
        </div>
    </div>
</div>

<div id="no_amazon_modal" class="modal">
    <div class="modal-wrap">
        <div class="modal-container">
            <p>На данный момент идет процесс добавления данного сборника на сайт Amazon.com. Ссылка станет
            активной в ближайшее время.</p>
        </div>
    </div>
</div>
<!-- preloader -->
<div class="book-preloader-wrap">
    <div class="book-preloader">
        <div class="inner">
            <div class="left"></div>
            <div class="middle"></div>
            <div class="right"></div>
        </div>
        <ul>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</div>
<!-- preloader -->

<div class="navbar">
    <div class="menu">
        <a class="menu-link" id="home" href="/">Главная</a>
        <a class="menu-link" href="{{route('old_collections')}}">Сборники</a>
        <a class="menu-link" href="{{route('own_books_portal')}}">Книги авторов</a>
        <a class="menu-link" href="{{route('about')}}">О нас</a>
        <a class="menu-link" href="/#reviews-block">Отзывы</a>
        <div class="account">
            @guest
                @if (Route::has('register'))
                    <a id="a_modal_login" href="/login" class="menu-link">
                        <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M512,256C512,114.51,397.5,0,256,0S0,114.5,0,256C0,396.23,113.54,512,256,512,397.88,512,512,396.88,512,256ZM256,30c124.62,0,226,101.38,226,226a225,225,0,0,1-38.7,126.52c-101-108.61-273.44-108.81-374.6,0A225,225,0,0,1,30,256C30,131.38,131.38,30,256,30ZM87.41,406.5c89.78-100.7,247.43-100.67,337.17,0C334.51,507.27,177.53,507.3,87.41,406.5Z"/>
                            <path
                                d="M256,271a90.1,90.1,0,0,0,90-90V151a90,90,0,0,0-180,0v30A90.1,90.1,0,0,0,256,271ZM196,151a60,60,0,0,1,120,0v30a60,60,0,0,1-120,0Z"/>
                        </svg>
                        {{ __('Войти') }}
                    </a>
                @endif
            @else
                <a class="menu-link" href="/myaccount/collections">
                    <div class="not-bell">
                        <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M512,256C512,114.51,397.5,0,256,0S0,114.5,0,256C0,396.23,113.54,512,256,512,397.88,512,512,396.88,512,256ZM256,30c124.62,0,226,101.38,226,226a225,225,0,0,1-38.7,126.52c-101-108.61-273.44-108.81-374.6,0A225,225,0,0,1,30,256C30,131.38,131.38,30,256,30ZM87.41,406.5c89.78-100.7,247.43-100.67,337.17,0C334.51,507.27,177.53,507.3,87.41,406.5Z"/>
                            <path
                                d="M256,271a90.1,90.1,0,0,0,90-90V151a90,90,0,0,0-180,0v30A90.1,90.1,0,0,0,256,271ZM196,151a60,60,0,0,1,120,0v30a60,60,0,0,1-120,0Z"/>
                        </svg>
                        @if (count($notifications) > 0)
                            <span>{{count($notifications)}}</span>
                        @endif
                    </div>

                    Мой кабинет
                </a>
            @endguest
        </div>

        <div style="display: none" class="account account-mobile">
            @guest
                @if (Route::has('register'))
                    <a id="a_modal_login" href="/login" class="menu-link">
                        <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M512,256C512,114.51,397.5,0,256,0S0,114.5,0,256C0,396.23,113.54,512,256,512,397.88,512,512,396.88,512,256ZM256,30c124.62,0,226,101.38,226,226a225,225,0,0,1-38.7,126.52c-101-108.61-273.44-108.81-374.6,0A225,225,0,0,1,30,256C30,131.38,131.38,30,256,30ZM87.41,406.5c89.78-100.7,247.43-100.67,337.17,0C334.51,507.27,177.53,507.3,87.41,406.5Z"/>
                            <path
                                d="M256,271a90.1,90.1,0,0,0,90-90V151a90,90,0,0,0-180,0v30A90.1,90.1,0,0,0,256,271ZM196,151a60,60,0,0,1,120,0v30a60,60,0,0,1-120,0Z"/>
                        </svg>
                        {{ __('Войти') }}
                    </a>
                @endif
            @else
                <a id="svg-app-only" class="menu-link" href="/myaccount/collections">
                    <div class="not-bell">
                        <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M512,256C512,114.51,397.5,0,256,0S0,114.5,0,256C0,396.23,113.54,512,256,512,397.88,512,512,396.88,512,256ZM256,30c124.62,0,226,101.38,226,226a225,225,0,0,1-38.7,126.52c-101-108.61-273.44-108.81-374.6,0A225,225,0,0,1,30,256C30,131.38,131.38,30,256,30ZM87.41,406.5c89.78-100.7,247.43-100.67,337.17,0C334.51,507.27,177.53,507.3,87.41,406.5Z"/>
                            <path
                                d="M256,271a90.1,90.1,0,0,0,90-90V151a90,90,0,0,0-180,0v30A90.1,90.1,0,0,0,256,271ZM196,151a60,60,0,0,1,120,0v30a60,60,0,0,1-120,0Z"/>
                        </svg>
                        @if (count($notifications) > 0)
                            <span>{{count($notifications)}}</span>
                        @endif
                    </div>
                </a>
            @endguest
        </div>
    </div>
    <div class="hamburger-menu">
        <input id="menu__toggle" type="checkbox" />
        <label class="menu__btn" for="menu__toggle">
            <span></span>
        </label>

        <ul class="menu__box">
            <li><a class="menu__item" id="home_mobile" href="/">Главная</a></li>
            <li><a class="menu__item" href="{{route('old_collections')}}">Сборники</a></li>
            <li><a class="menu__item" href="{{route('own_books_portal')}}">Книги авторов</a></li>
            <li><a class="menu__item" href="{{route('about')}}">О нас</a></li>
            <li><a class="menu__item" href="/#reviews-block">Отзывы</a></li>

        </ul>
    </div>
</div>
@yield('content')


<div class="footer">
    <div class="footer-content">
        <div class="footer-company-info">
            Независимое издательство<br>
            “Первая Книга”.<br>
            Budapest/Москва<br>
            ©2016-2021
        </div>

        <div class="footer-socials">
            <div class="footer-socials-title">
                Мы в соц. сетях:
            </div>
            <div class="footer-socials-icons">
                <a href="https://vk.com/yourfirstbook" target="_blank">
                    <svg id="regular" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.01 320">
                        <path
                            d="M259.09,416c74.07,0,47.66-46.89,53.38-60.37-.09-10.07-.17-19.76.17-25.65,4.69,1.33,15.77,6.94,38.63,29.17C386.58,394.77,395.61,416,424.13,416h52.5c16.64,0,25.3-6.89,29.63-12.67,4.18-5.59,8.28-15.4,3.8-30.68-11.71-36.78-80-100.29-84.27-107,.64-1.23,1.67-2.88,2.2-3.73h0c13.48-17.81,64.94-94.91,72.51-125.76a.43.43,0,0,0,0-.17c4.1-14.08.34-23.21-3.54-28.37C491.11,99.9,481.81,96,469.25,96h-52.5c-17.58,0-30.92,8.85-37.66,25-11.28,28.7-43,87.7-66.75,108.59-.72-29.59-.23-52.18.15-69,.77-32.75,3.24-64.62-30.74-64.62H199.23c-21.29,0-41.66,23.25-19.6,50.86,19.28,24.19,6.93,37.67,11.09,104.79-16.21-17.39-45.06-64.34-65.45-124.35C119.55,111.06,110.89,96,86.51,96H34c-21.3,0-34,11.61-34,31C0,170.71,96.62,416,259.09,416ZM86.51,128c4.63,0,5.1,0,8.53,9.75,20.89,61.5,67.73,152.51,102,152.51,25.71,0,25.71-26.34,25.71-36.26l0-79c-1.41-26.13-10.93-39.15-17.18-47l74.84.09c0,.36-.43,87.36.21,108.43,0,29.93,23.77,47.09,60.87,9.54,39.15-44.18,66.22-110.23,67.31-112.92,1.6-3.84,3-5.14,8-5.14h52.71c0,.06,0,.13,0,.19-4.8,22.4-52.18,93.78-68,116-.26.34-.49.7-.73,1.07-7,11.39-12.65,24,1,41.68h0c1.24,1.49,4.46,5,9.15,9.86,14.6,15.06,64.64,66.56,69.08,87-2.94.47-6.14.12-55.74.23-10.56,0-18.82-15.79-50.33-47.57-28.33-27.56-46.72-38.83-63.46-38.83-32.52,0-30.15,26.39-29.85,58.31.11,34.6-.11,23.65.13,25.83-1.9.75-7.34,2.24-21.53,2.24C123.73,384,35.58,169.15,32.19,128.09c1.18-.11,17.32-.05,54.32-.07Z"
                            transform="translate(0 -96)"/>
                    </svg>
                </a>
                <a href="https://www.instagram.com/pervayakniga/" target="_blank">
                    <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510.9 511">
                        <path
                            d="M510.5,150.24c-1.2-27.16-5.59-45.82-11.88-62a130.86,130.86,0,0,0-74.77-74.76C407.58,7.19,389,2.8,361.86,1.6,334.51.3,325.83,0,256.45,0S178.39.3,151.13,1.5s-45.81,5.59-62,11.87A124.84,124.84,0,0,0,43.82,42.92,125.81,125.81,0,0,0,14.37,88.15C8.08,104.42,3.69,123,2.5,150.13,1.2,177.49.9,186.17.9,255.55s.3,78.06,1.49,105.31,5.6,45.82,11.89,62a125.86,125.86,0,0,0,29.54,45.32A125.75,125.75,0,0,0,89,497.62c16.28,6.29,34.84,10.68,62,11.88S187,511,256.35,511s78.06-.3,105.31-1.5,45.82-5.59,62-11.88a130.68,130.68,0,0,0,74.77-74.76c6.29-16.28,10.68-34.84,11.88-62s1.5-35.93,1.5-105.31S511.7,177.49,510.5,150.24Zm-46,208.63c-1.1,24.95-5.29,38.43-8.78,47.41a84.78,84.78,0,0,1-48.52,48.52c-9,3.49-22.56,7.69-47.41,8.78-27,1.2-35,1.5-103.22,1.5s-76.37-.3-103.22-1.5c-25-1.09-38.43-5.29-47.42-8.78a78.44,78.44,0,0,1-29.34-19.07A79.32,79.32,0,0,1,57.5,406.39c-3.5-9-7.69-22.57-8.78-47.42-1.21-27-1.5-35-1.5-103.22s.29-76.37,1.5-103.22c1.09-25,5.28-38.43,8.78-47.41A77.56,77.56,0,0,1,76.67,75.76,79.34,79.34,0,0,1,106,56.7c9-3.49,22.57-7.68,47.42-8.78,26.95-1.2,35-1.5,103.22-1.5s76.36.3,103.22,1.5c24.95,1.1,38.43,5.29,47.41,8.78a78.58,78.58,0,0,1,29.35,19.06,79.44,79.44,0,0,1,19.07,29.36c3.49,9,7.68,22.55,8.78,47.41,1.2,26.95,1.5,35,1.5,103.22S465.68,331.92,464.48,358.87Z"
                            transform="translate(-0.9 0)"/>
                        <path
                            d="M256.45,124.28A131.27,131.27,0,1,0,387.72,255.55,131.31,131.31,0,0,0,256.45,124.28Zm0,216.42a85.15,85.15,0,1,1,85.15-85.15A85.16,85.16,0,0,1,256.45,340.7Z"
                            transform="translate(-0.9 0)"/>
                        <path d="M423.56,119.09a30.65,30.65,0,1,1-30.65-30.64A30.66,30.66,0,0,1,423.56,119.09Z"
                              transform="translate(-0.9 0)"/>
                    </svg>
                </a>
                <a href="https://www.facebook.com/pervaya.kniga" target="_blank">
                    <svg id="Bold" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256.02 512">
                        <path
                            d="M341.27,85H388V3.61C380,2.5,352.21,0,319.91,0,252.52,0,206.36,42.39,206.36,120.3V192H132v91h74.37V512h91.18V283H368.9l11.32-91H297.51v-62.7c0-26.3,7.11-44.31,43.76-44.31Z"
                            transform="translate(-131.99 0)"/>
                    </svg>
                </a>
                <a href="https://ok.ru/group/54728360853742" target="_blank">
                    <svg id="Bold" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 319.95 512">
                        <path
                            d="M100.71,274.79c-13.07,25.71,1.78,38,35.65,59,28.8,17.79,68.59,24.3,94.15,26.9C220,370.75,268,324.59,129.79,457.56c-29.31,28.09,17.88,73.17,47.17,45.67l79.3-76.48c30.35,29.21,59.45,57.2,79.29,76.59,29.31,27.6,76.48-17.09,47.49-45.68-2.18-2.07-107.46-103.06-101-96.87,25.87-2.6,65.06-9.49,93.52-26.9l0,0c33.88-21.1,48.72-33.37,35.84-59.08-7.79-14.59-28.78-26.79-56.73-5.69,0,0-37.73,28.91-98.6,28.91s-98.6-28.91-98.6-28.91c-27.93-21.21-49-8.9-56.71,5.69Z"
                            transform="translate(-96)"/>
                        <path
                            d="M256,259c74.2,0,134.78-58,134.78-129.37C390.76,58,330.18,0,256,0S121.17,58,121.17,129.66C121.17,201.05,181.76,259,256,259Zm0-193.34c36.46,0,66.2,28.6,66.2,64,0,35.08-29.74,63.68-66.2,63.68s-66.2-28.6-66.2-63.68c0-35.39,29.72-64,66.2-64Z"
                            transform="translate(-96)"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="footer-questions">
            <div style="margin-bottom: 10px;">Остались вопросы?<br>
                Задайте их прямо сейчас<br></div>
            <a class="button">Вопрос-ответ</a>
        </div>
    </div>
</div>
<a href=""></a>

<script src="/js/js.js"></script>
<script src="/js/sweetalert2.js"></script>
<script>
    {{Session(['js_main_loaded' => 0])}}


</script>

@if(Auth::user()->id ?? 0 > 0)
@else

    <script>


        $(".log_check").click(function (event) {
            event.preventDefault();
            Swal.fire({
                html: '<p style="margin-bottom: 20px;" >Вы переходите в личный кабинет, но еще не авторизованы в системе. Для использования личного кабинета необходимо произвести вход или зарегистрироваться, если у Вас еще нет аккаунта.</p><a style="margin-right: 10px;"  class="button" href="' + $(this).attr('href') + '">Войти</a> <a style="margin-left: 10px;"  class="button" href="{{route('register')}}">Регистрация</a>',
                icon: 'info',
                showConfirmButton: false,
            })
        });
    </script>


@endif

<script>
    $('.preloader_button').on('click', function () {
        $(this).attr("disabled", true);
        this.classList.toggle('button--loading')
        $('#' + $(this).attr('id').split('_')[1]).submit();
    });
</script>


@yield('page-js')
</body>
</html>
