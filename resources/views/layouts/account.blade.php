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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/textify.js@1.1.1/dist/index.min.js"></script>

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
<main class="flex flex-1 gap-4 !py-0">
    <x-account-menu/>
    <section class="p-4 max-w-8xl flex-1 pl-[19rem] md:pl-28 sm:!pl-4">
        <h1 class="text-dark-500 text-4xl font-semibold mb-6 w-fit mx-0 sm:mx-auto">@yield('title')</h1>
        {{ $slot }}
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
