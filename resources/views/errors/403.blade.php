@extends('layouts.portal_layout')


@section('page-style')
    <style>
        body {
            background: white;
        }
        .error-content {
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
            h2 {
                font-size: 30px !important;
            }
        }
    </style>

@endsection



@section('page-title') 403 ошибка @endsection





@section('content')
    <div class="account-content">

        <h2 style="font-size: 40px !important; margin: auto; padding: 0 10%; text-align: center">Войдите в аккаунт админа, чтобы просматривать эту страницу!</h2>

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
