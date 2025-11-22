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
            gap: 20px;
            align-items: center;
            text-align: center;
            margin: auto;
        }

        .account-content {
            padding: 0;
        }

        .error-content ul {
            font-size: 20px;
            list-style-type: none;
            padding: 0 10%;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 25px !important;
            }

            .error-content {
                padding-top: 80px;
                padding-bottom: 50px;
            }
        }

        /*.navbar, .footer {*/
        /*    display: none;*/
        /*}*/
    </style>

@endsection



@section('page-title')
    419 ошибка
@endsection



@section('content')
    <div class="error-content">
        <h2 style="font-size: 45px; padding: 0 10%;">Что-то пошло не так!</h2>
        <h2 style="font-size: 35px; padding: 0 10%;">Мы сожалеем, что Вы столкнулись с ошибкой.<br>Возможные пути
            решения:</h2>
        <ul>
            <li>
                1) Перезагрузите страницу и попробуйте совершить то же действие снова.
            </li>
            <li>
                2) Создайте вопрос с подробные описанием проблемы. Мы постараемся помочь как можно скорее. Нам очень
                поможет, если вы укажите код и ID ошибки.
                <ul><i>
                        <li><b>Код:</b> 419</li>
                        <li><b>ID:</b> {{ $error_id }}</li>
                    </i>
                </ul>
            </li>
        </ul>
        <a style="margin-top: 20px; box-shadow: none;" href="{{route('chat_create',"Ошибка в системе ({$error_id})")}}"
           class="button fast-load">Создать вопрос</a>
    </div>

@endsection

@push('page-js')
    <script>
        document.getElementsByClassName('preloader_wrap')[0].style.visibility = 'hidden';

        // Отправляем `error_id` в Яндекс.Метрику
        if (typeof ym !== 'undefined') {
            ym(86096774, 'reachGoal', 'error', {
                error_id: '{{ $error_id }}',
                url: window.location.href
            });
        }
    </script>
@endpush
