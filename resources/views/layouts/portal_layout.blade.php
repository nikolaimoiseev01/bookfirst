<!doctype html>
<html lang="ru">
<head>
    <script src="https://kit.fontawesome.com/e1202d4768.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


    @yield('page-style')
    <link rel="stylesheet" href="{{ asset('css/portal-media.css') }}">
    <script src="/js/jquery.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <title>Первая Книга: @yield('page-title')</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    @if($subdomain == 'social')
        <link rel="stylesheet" href="/css/social-home.css">
    @endif


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


@extends(($subdomain == 'social') ? 'layouts.parts.header_social' : 'layouts.parts.header_portal')



@yield('content')


@extends(($subdomain == 'social') ? 'layouts.parts.footer_social' : 'layouts.parts.footer_portal')

<a href=""></a>
@livewireScripts

<script src="/js/js.js"></script>
<script src="/js/sweetalert2.js"></script>
{{Session(['js_main_loaded' => 0])}}

<script src="https://kit.fontawesome.com/e1202d4768.js" crossorigin="anonymous"></script>
</script>


@if($subdomain == 'social')
    <script src="/js/social-js.js"></script>
@endif

{{--@stack('scripts')--}}


@if($subdomain != 'social')
<script>
    if (!sessionStorage.getItem('network_present_session')) {
        setTimeout(() => {
            // Презентация соц. сети!
            Swal.fire({
                title: '<h2 style="font-size: 40px; margin: 20px 0 0 0;"> Мы расширяем платформу!</h2>',
                html: '<p style="margin-bottom: 20px;" >Отличные новости! Мы создали современную <b style="color: var(--social_blue)">социальную сеть</b> для авторов. ' +
                    'На ней можно неограниченно бесплатно публиковаться, общаться и <b style="color: var(--social_blue)">зарабатывать деньги!</b> </p>' +
                    '<img src="/img/network_presentation.png" style="margin-bottom: 20px; max-width:90%;" alt="">' +
                    '<div style="display: flex; justify-content: center;"><a  class="button_social" href="/social">Смотреть</a></div>' +
                    '<a style="margin-top: 10px;" id="hide_social_present" class="link_social">Не напоминать больше</a>',
                // icon: 'info',
                showConfirmButton: false,
            })
            document.getElementById("hide_social_present").addEventListener ("click", hide_social_present, false);

            function hide_social_present() {
                Swal.close();
                sessionStorage.setItem("network_present_session", true)
            }
        }, 1000)
    }
</script>
@endif

<script>
    function make_log_check() {
        $(".log_check").on('click', function (event) {
            @if(Auth::user()->id ?? 0 > 0)
            @else
            event.preventDefault();
            Swal.fire({
                html: '<p style="margin-bottom: 20px;" >Для выполнения действия необходимо быть авторизированным в системе. Для этого необходимо произвести вход или зарегистрироваться, если у Вас еще нет аккаунта.</p><div style="display: flex; justify-content: center;"><a style="margin-right: 10px;"  class="button" href="/login">Войти</a> <a style="margin-left: 10px;"  class="button" href="{{route('register')}}">Регистрация</a></div>',
                // icon: 'info',
                showConfirmButton: false,
            })
            @endif
        });
    }

    make_log_check();

</script>


<script>
    window.addEventListener('swal:modal', event => {
        Swal.fire({
            title: event.detail.title,
            icon: event.detail.type,
            html: "<p>" + event.detail.text + "</p>",
            showConfirmButton: false,
        })
    })

</script>


<script>
    $('.preloader_button').on('click', function () {
        $(this).attr("disabled", true);
        this.classList.toggle('button--loading')
        $('#' + $(this).attr('id').split('_')[1]).submit();
    });
</script>


<script>

    var utm_source_cookie;
    var utm_medium_cookie;

    function getCook(cookiename) {
        // Get name followed by anything except a semicolon
        var cookiestring = RegExp(cookiename + "=[^;]+").exec(document.cookie);
        // Return everything after the equal sign, or an empty string if the cookie name not found
        return decodeURIComponent(!!cookiestring ? cookiestring.toString().replace(/^[^=]+./, "") : "");
    }

    function getParameters() {
        let urlString = window.location.toString();
        let paramString = urlString.split('?')[1];
        let queryString = new URLSearchParams(paramString);
        for (let pair of queryString.entries()) {
            if (pair[0] == 'utm_source') {
                utm_source = pair[1]
                document.cookie = "utm_source=" + utm_source;
            } else if (pair[0] == 'utm_medium') {
                utm_medium = pair[1]
                document.cookie = "utm_medium=" + utm_medium;
            }
        }
    }


    utm_source_cookie = getCook('utm_source');
    utm_medium_cookie = getCook('utm_medium');

    if (utm_source_cookie === '') {
        getParameters();
        utm_source_cookie = getCook('utm_source');
        utm_medium_cookie = getCook('utm_medium');
    }


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

{{--Новогодние снежинки--}}

<script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>
<script>
    new Snowflakes({
        color: '#5ECDEF', // Default: "#5ECDEF"
        container: document.body, // Default: document.body
        count: 20, // 100 snowflakes. Default: 50
        minOpacity: 0.4, // From 0 to 1. Default: 0.6
        maxOpacity: 0.8, // From 0 to 1. Default: 1
        minSize: 10, // Default: 10
        maxSize: 20, // Default: 25
        rotation: true, // Default: true
        speed: 1, // The property affects the speed of falling. Default: 1
        wind: true, // Without wind. Default: true
        zIndex: 9997 // Default: 9999
    });
</script>
{{-------------------------------------------------------------------------}}


@yield('page-js')
</body>
</html>
