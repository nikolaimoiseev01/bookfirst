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
            margin: auto;
        }

        .account-content {
            padding: 0;
        }


        .error-content ul {
            font-size: 20px;
            list-style-type: none;
        }

        @media (max-width: 768px) {
            .error-content {
                padding-top: 80px;
            }
        }

    </style>

@endsection



@section('page-title') 404 ошибка @endsection





@section('content')
    <div class="account-content">

        <div class="error-content">
            <h2 style="font-size: 30px !important; margin: auto; padding: 0 10%;">УПС! На нашем сайте нет такой страницы. Проверьте, пожалуйста, адрес или вернитесь назад.</h2>
        </div>

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
