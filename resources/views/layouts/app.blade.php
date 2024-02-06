<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    {{--    <script>--}}
    {{--        $(window).on('load', function() {--}}
    {{--            $('.preloader-wrap').addClass('completed')--}}
    {{--        })--}}
    {{--    </script>--}}
{{--    <link rel="stylesheet" href="{{ asset('css/style.css') }}">--}}
    <link rel="stylesheet" href="/css/books-index.css">
    <link rel="stylesheet" href="/css/participation-index.css">
    <link rel="stylesheet" href="/css/create-participation.css">
    <link rel="stylesheet" href="/plugins/filepond/filepond.css">
    {{--    <link rel="stylesheet" href="{{ asset('css/portal-media.css') }}">--}}
    {{--    <link rel="stylesheet" href="{{ asset('css/app-media.css') }}">--}}
    @yield('page-style')
    <title>@yield('page-tab-title')</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
{{--    <link rel="manifest" href="/img/favicon/site.webmanifest">--}}
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

    <link rel="stylesheet" href="/fonts/fonts.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])


</head>

<body>


<div class="cus-modal">
    <div class="cus-modal-wrap">
    </div>
</div>


<x-preloader mode="portal"/>
<x-header-portal/>

<x-account.app-menu/>

<div class="account-content">

    @yield('page-title')

    @yield('content')

</div>

<x-footer manvisible="true" mode="portal"/>

<script src="/js/jquery.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    function preloader_hide() {
        $('.preloader_wrap').addClass('preloaded_hiding');
        window.setTimeout(function () {
            $('.preloader_wrap').addClass('preloaded_loaded');
            $('.preloader_wrap').removeClass('preloaded_hiding');
        }, 500);
    }

    window.onload = function () {
        preloader_hide()
    }

    setTimeout(function () { // хардкорно выключаем долгий прелоадер
        preloader_hide()
    }, 1500);
</script>

@livewireScripts
<script src="/js/js.js"></script>
<script src="/js/sweetalert2.js"></script>


<script src="/plugins/filepond/filepond.js"></script>
<!-- include FilePond jQuery adapter -->
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
<!-- include FilePond file-validate-size adapter -->
<script src="/plugins/filepond/filepond-plugin-file-validate-size.min.js"></script>
<!-- include FilePond file-validate-type adapter -->
<script src="/plugins/filepond/filepond-plugin-file-validate-type.min.js"></script>

<script src="https://kit.fontawesome.com/e1202d4768.js" crossorigin="anonymous"></script>

<script src="//unpkg.com/alpinejs" defer></script>


<script src="/plugins/autolinker/autolinker.min.js"></script>

<script>
    FilePond.registerPlugin(FilePondPluginFileValidateSize);
</script>

<script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>

<script>
    //region -- Новогодние снежинки
    var count_snows = 20
    if(window.innerWidth > 768) {
        count_snows = 20
    } else {
        count_snows = 10
    }
    new Snowflakes({
        color: '#5ECDEF', // Default: "#5ECDEF"
        container: document.body, // Default: document.body
        count: count_snows, // 100 snowflakes. Default: 50
        minOpacity: 0.4, // From 0 to 1. Default: 0.6
        maxOpacity: 0.8, // From 0 to 1. Default: 1
        minSize: 10, // Default: 10
        maxSize: 20, // Default: 25
        rotation: true, // Default: true
        speed: 1, // The property affects the speed of falling. Default: 1
        wind: true, // Without wind. Default: true
        zIndex: 9997 // Default: 9999
    });
    //endregion
</script>

<script>

    // Общее обознаение вызова SWAL (LIVEWIRE)
    window.addEventListener('toast_fire', event => {
        Toast.fire({
            icon: event.detail.type,
            title: event.detail.title
        })
    })

    // Общее обознаение вызова SWAL (LIVEWIRE)

    window.addEventListener('swal:modal', event => {
        Swal.fire({
            title: event.detail.title,
            icon: event.detail.type,
            html: "<p>" + event.detail.text + "</p>",
            showConfirmButton: false,
        })
        if (event.detail.type === 'success') {

            $('#go-to-part-page').attr('href', event.detail.link);
            $('#go-to-part-page').trigger('click');
            $('#back').trigger('click');
        }
    })

    window.addEventListener('swal:confirm', event => {
        console.log(event.detail.onconfirm)
        Swal.fire({
            title: event.detail.title,
            // icon: 'warning',
            html: event.detail.html,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `Все верно`,
            denyButtonText: `Отменить`,
        }).then((result) => {
            if (result.isConfirmed) {
                if(event.detail.id) {
                    window.livewire.emit(event.detail.onconfirm, event.detail.id)
                } else {
                    window.livewire.emit(event.detail.onconfirm)
                }

            }
        })
    })

    window.addEventListener('swal:min', event => {
        Swal.fire({
            position: 'top-end',
            title: event.detail.title,
            icon: event.detail.type,
            html: event.detail.text,
            showConfirmButton: false,
            timer: 3000
        })
    })

    $(document).ready(function () {
        if (window.location.pathname.startsWith('/email/verify/')) {
            Swal.fire({
                title: 'Отлично, Ваш Email подтвержден!',
                icon: 'success',
                showConfirmButton: false,
            })
        }
    })

    @if (session('show_modal') == 'yes')
    Swal.fire({
        type: '{{session('alert_type')}}',
        title: '{{session('alert_title')}}',
        icon: '{{session('alert_type')}}',
        html: '<p>{{session('alert_text')}}</p>',
        showConfirmButton: false,
    })
    @endif

    // -- SWAL при полном обновлении страницы.
    @if (session('success'))
    Swal.fire({
        title: '{{session('alert_title')}}',
        icon: 'success',
        html: '<p>{{session('alert_text')}}</p>',
        showConfirmButton: false,
    })
    @endif

</script>

@yield('page-js')

@stack('page-js')


</body>
</html>


