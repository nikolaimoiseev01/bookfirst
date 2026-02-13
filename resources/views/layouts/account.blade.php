<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ПК | @yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="/fixed/fonts/fonts.css">

    <meta name="user-logged-in" content="{{ auth()->check() ? 'true' : 'false' }}">

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

    <link rel="icon" type="image/png" href="/fixed/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/fixed/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/fixed/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/fixed/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="MyWebSite" />
    <link rel="manifest" href="/fixed/favicon/site.webmanifest" />

    <script src="/plugins/swal/sweetalert2.all.min.js"></script>
    <link href="/plugins/swal/sweetalert2.min.css" rel="stylesheet">
    <script src="https://api-maps.yandex.ru/v3/?apikey=ad88f427-6fde-4dbd-984f-d65b8e659fd3&lang=ru_RU"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@cdek-it/widget@3" charset="utf-8"></script>

    <link rel="stylesheet" type="text/css" href="/vendor/livewire-filepond/filepond.css?v=1.5.0">
    <script type="module" src="/vendor/livewire-filepond/filepond.js?v=1.5.0" data-navigate-once defer data-navigate-track></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased flex flex-col min-h-screen"  x-data x-init="
        document.documentElement.style.setProperty(
            '--scrollbar-color', '#47af98'
        )
    ">
<x-header-portal/>
<main class="flex flex-1 gap-4 !py-0 min-h-0">
    <x-account-menu/>
    <section class="p-4 max-w-8xl flex-1 sm:!pl-4">
        <h1 class="text-dark-500 text-4xl font-semibold mb-6 w-fit mx-0 sm:mx-auto">@yield('title')</h1>
        <div class="relative z-[99]">
            {{ $slot }}
        </div>
    </section>
</main>
@stack('scripts')
@if(session('swal'))
    <script type="module">
        let params = @json(session('swal') ?? []);
        showSwal(params.type, params.title, params.text)
    </script>
@endif
<x-footer/>
</body>
</html>
