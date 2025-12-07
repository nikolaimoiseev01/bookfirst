@extends('layouts.error')

@section('content')
    <main class="flex flex-1 flex-col gap-8 content">
        <div class="flex gap-8 md:flex-col">
            <div class="w-1/2 md:w-full flex flex-col gap-8">
                <h1 class="text-3xl font-medium text-center">На нашей стороне произошла ошибка,
                    простите :(</h1>
                <p class="mx-auto"><b>Код ошибки:</b> {{$error_id}}</p>
                <p class="mx-auto">Варианты решения проблемы:</p>
                <ul class="list-disc pl-6">
                    <li>Повторите действие еще раз</li>
                    <li>Создайте обращение в поддержку (нужно быть авторизованным в системе)</li>
                    <li>Попробуйте зайти на сайт позже</li>
                </ul>
                <div class="flex gap-16 mx-auto">
                    <x-ui.link :navigate="false" href="/" target="_blank">На главную</x-ui.link>
                    <x-ui.link :navigate="false"
                               href="{{route('account.chat_create', ['title' => 'Ошибка на сайте ('.$error_id.')'])}}">
                        Создать обращение
                    </x-ui.link>
                </div>
            </div>
            <img class="w-1/2 md:w-[90%] max-w-3xl mx-auto" src="/fixed/mascots/error.svg" alt="">
        </div>
    </main>
@endsection
