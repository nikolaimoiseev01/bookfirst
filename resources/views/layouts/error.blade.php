<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ПК | Ошибка</title>

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


    <script src="/plugins/swal/sweetalert2.all.min.js"></script>
    <link href="/plugins/swal/sweetalert2.min.css" rel="stylesheet">

    <script>
        (function () {
            const params = new URLSearchParams(window.location.search);

            const utmSource = params.get('utm_source');
            const utmMedium = params.get('utm_medium');

            if (utmSource) {
                localStorage.setItem('utm_source', utmSource);
            }
            if (utmMedium) {
                localStorage.setItem('utm_medium', utmMedium);
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased flex flex-col min-h-screen" x-data  x-init="
        document.documentElement.style.setProperty(
            '--scrollbar-color',
            $store.global.social ? '#66a2e5' : '#47af98' // blue-500 / green-500
        )
    ">
<x-header-portal/>
@yield('content')
@livewireScripts
<x-footer/>
</body>
</html>
