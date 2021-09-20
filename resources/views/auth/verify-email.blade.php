@extends('layouts.app')

@section('content')
    <div style="max-width: 800px; margin-top:20px;" class="login_register_list">
        <p>Мы отправили ссылку на почту: <b>{{Auth::user()->email}}</b>
            <br>
            Пожалуйста, перейдите по ней, чтобы подтвердить Ваш Email.
        </p>

        <div style="margin-top: 20px; display: flex; align-items: center;">
            <form id="resend-form" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <p style="line-height: 0px; font-size: 21px;">Не получили письмо?</p>
                <br>
                <a style="font-size: 21px;" onclick="event.preventDefault();
       document.getElementById('resend-form').submit();" class="link">Запросить еще одно письмо</a>
            </form>


            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
                <a style="float: right;" class="button" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">
                    {{ __('Выйти') }}
                </a>
            </form>
        </div>

        @if (session('resent'))
            <p style="margin-top: 15px;"><b>{{ __('Новое письмо было выслано на указанный Email!') }}</b></p>
        @endif

    </div>



@endsection
