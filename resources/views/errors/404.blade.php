@extends('layouts.error')

@section('content')
<main class="flex flex-1 flex-col gap-8">
    <img class="w-[90%] max-w-3xl mx-auto" src="/fixed/mascots/error-404.svg" alt="">
    <h1 class="text-3xl font-medium text-center">Такой страницы нет на нашем сайте :(</h1>
    <div class="flex gap-16 mx-auto">
        <x-ui.link>На главную</x-ui.link>
    </div>
</main>
@endsection
