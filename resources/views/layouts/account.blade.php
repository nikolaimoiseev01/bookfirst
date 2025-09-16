<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ПК | @yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="/fixed/fonts/fonts.css">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/textify.js@1.1.1/dist/index.min.js"></script>

    <script src="/plugins/swal/sweetalert2.all.min.js"></script>
    <link href="/plugins/swal/sweetalert2.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased flex flex-col min-h-screen">
<x-header-portal/>
<main class="flex flex-1 gap-4 !py-0">
    <x-account-menu/>
    <section class="p-4 max-w-8xl flex-1">
        <h1 class="text-dark-500 text-4xl font-semibold mb-6 w-fit mx-0">@yield('title')</h1>
        {{ $slot }}
    </section>
</main>
@stack('scripts')

@if(session('swal'))
    <script type="module">
        function showSwal(param) {
            console.log(param)
            Swal.fire({
                icon: param.icon,
                title: param.title,
                html: '<p>' + param.text + '</p>',
                showConfirmButton: false,
            });
        }
        showSwal(@json(session('swal')))
    </script>
@endif
<x-footer/>
</body>
</html>
