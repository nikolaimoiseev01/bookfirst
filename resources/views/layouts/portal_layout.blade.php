<!doctype html>
<html lang="ru">
<head>
    <script src="https://kit.fontawesome.com/e1202d4768.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <link rel="stylesheet" href="/fonts/fonts.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @yield('page-style')

    <title>Первая Книга: @yield('page-title')</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="android-chrome-192x192" sizes="192x192" href="/img/favicon/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">

    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">


    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (m, e, t, r, i, k, a) {
            m[i] = m[i] || function () {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(86096774, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true
        });
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/86096774" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->

    @livewireStyles
</head>

<span id="user_id_logged_in" data-user_id='{{$user_id_logged_in}}' style="display: none !important;"></span>

<body>

<div id="modal_login" class="modal">
    <div class="modal-wrap">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="cus-modal">
    <div class="cus-modal-wrap">
    </div>
</div>



@if(str_contains($subdomain, 'social'))
    <style>
        *::-webkit-scrollbar-thumb {
            background: #66a2e5;
        }
    </style>
    <x-preloader mode="social"/>
    <x-header-social/>
    @yield('content')
    <x-footer manvisible="false" mode="social"/>
@else
    <x-preloader manvisible="false" mode="portal"/>
    <x-header-portal/>
    @yield('content')
    <x-footer manvisible="false" mode="portal"/>
@endif


<script src="/js/jquery.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

@livewireScripts

<script src="/plugins/slick/slick.min.js"></script>
<script src="/js/sweetalert2.js"></script>

<script src="//unpkg.com/alpinejs"></script>

<script src="/js/js.js"></script>
<script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>

{{--@if(!str_contains($subdomain, 'social'))--}}
{{--<script>--}}
{{--    if (!sessionStorage.getItem('network_present_session')) {--}}
{{--        setTimeout(() => {--}}
{{--            // Презентация соц. сети!--}}
{{--            Swal.fire({--}}
{{--                title: '<h2 style="font-size: 40px; margin: 20px 0 0 0;"> Мы расширяем платформу!</h2>',--}}
{{--                html: '<p style="margin-bottom: 20px;" >Отличные новости! Мы создали современную <b style="color: var(--social_blue)">социальную сеть</b> для авторов. ' +--}}
{{--                    'На ней можно неограниченно бесплатно публиковаться, общаться и <b style="color: var(--social_blue)">зарабатывать деньги!</b> </p>' +--}}
{{--                    '<img src="/img/network_presentation.png" style="margin-bottom: 20px; max-width:90%;" alt="">' +--}}
{{--                    '<div style="display: flex; justify-content: center;"><a  class="button_social" href="/social">Смотреть</a></div>' +--}}
{{--                    '<a style="margin-top: 10px;" id="hide_social_present" class="link_social">Не напоминать больше</a>',--}}
{{--                // icon: 'info',--}}
{{--                showConfirmButton: false,--}}
{{--            })--}}
{{--            document.getElementById("hide_social_present").addEventListener ("click", hide_social_present, false);--}}

{{--            function hide_social_present() {--}}
{{--                Swal.close();--}}
{{--                sessionStorage.setItem("network_present_session", true)--}}
{{--            }--}}
{{--        }, 5000)--}}
{{--    }--}}
{{--</script>--}}
{{--@endif--}}

<script>
    function make_log_check() {
        $(".log_check").on('click', function (event) {
            @if(!(Auth::user()->id ?? null))
            event.preventDefault();
            Swal.fire({
                html: '<p>Для выполнения действия необходимо быть авторизированным в системе. Для этого необходимо произвести вход или зарегистрироваться, если у Вас еще нет аккаунта.</p><div class="buttons_wrap"><a class="button" href="/login">Войти</a> <a  class="button" href="{{route('register')}}">Регистрация</a></div>',
                // icon: 'info',
                showConfirmButton: false,
            })
            @endif
        });
    }

    make_log_check();

</script>


<script>
    @if (session('show_modal'))
    Swal.fire({
        title: '{{session('alert_title')}}',
        icon: '{{session('alert_type')}}',
        html: '<p>{{session('alert_text')}}</p>',
        showConfirmButton: false,
    })
    @endif
</script>

@yield('page-js')
@stack('page-js')

</body>
</html>
