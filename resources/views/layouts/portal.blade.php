<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ПК | @yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="/fixed/fonts/fonts.css">
    <meta name="user-logged-in" content="{{ auth()->check() ? 'true' : 'false' }}">


    <script src="/plugins/swal/sweetalert2.all.min.js"></script>
    <link href="/plugins/swal/sweetalert2.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased flex flex-col min-h-screen" x-data  x-init="
        document.documentElement.style.setProperty(
            '--scrollbar-color',
            $store.global.social ? '#66a2e5' : '#47af98' // blue-500 / green-500
        )
    ">
<x-header-portal/>
{{ $slot }}
@stack('scripts')
<x-footer/>
@if(session('swal'))
    showSwal(@json(session('swal')))
@endif
</body>
</html>
