@extends('layouts.portal_layout')


@section('page-style')
    <style>
        body {
            background: white;
        }
        .error-content {
            margin-top: 85px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }


        .error-content ul {
            font-size: 20px;
        }

        /*.navbar, .footer {*/
        /*    display: none;*/
        /*}*/
    </style>

@endsection



@section('page-title') 500 ошибка @endsection



@section('content')
    <div class="error-content">
        <h2 style="font-size: 45px;">Что-то пошло не так! Ошибка: 500.</h2>
        <h2 style="font-size: 35px;">Мы сожалеем, что Вы столкнулись с ошибкой. <br>Возможные пути решения:</h2>
        <ul>
            <li>
                1) Перезагрузите страницу и попробуйте совершить то же действие снова.
            </li>
            <li>
                2) Создайте вопрос с подробные описанием проблемы. Мы постараемся помочь как можно скорее.
            </li>
        </ul>
        <a style="margin-top: 20px; box-shadow: none;" href="{{route('chat_create','Ошибка в системе')}}" class="button fast-load">Создать вопрос</a>
    </div>

@endsection

@section('page-js')

@endsection
