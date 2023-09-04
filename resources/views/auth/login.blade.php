@extends('layouts.portal_layout')

@section('content')
    <div class="log_reg_page_wrap page_content_wrap">
        <div class="header_wrap">
            <h2>Вход</h2>
            <a href="/register">
                <h2>Регистрация</h2>
            </a>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if ($errors->count() > 0)
                <div class="error_wrap">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>
                                <p>{{ $error }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <x-input.input
                id="email"
                type="text"
                name="email"
                autocomplete="email"
                autofocus
            >
                Email
            </x-input.input>

            <div class="input-group password-group">
                <p>Пароль</p>
                <div>
                    <input id="password"
                           type="password"
                           name="password"
                    >
                    <i id="togglePassword" class="far fa-eye-slash"></i>
                </div>

            </div>

            <div class="checkbox-group">
                <label for="remember"><p>Запомнить меня</p></label>
                <input {{ old('remember') ? 'checked' : '' }} name="remember" id="remember" type="checkbox">
            </div>

            <div class="login_buttons_wrap">
                <button id="form_login" class="show_preloader_on_click preloader_button button" type="submit">
                    <span class="button__text">Войти</span>
                </button>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="float:right;" class="link" href="">Восстановить
                        пароль</a>
                @endif
            </div>

            <div class="other_logins_wrap">
                <p>Войти через соц.сети: </p>

                <div class="buttons_wrap">
                    <a href="{{route('sign_vk')}}" class="button">
                        <img src="/img/VK Logo.svg" alt="">
                    </a>
                    {{--                <a href="{{route('sign_ok')}}" class="button">Войти через OK</a>--}}
                    <a href="{{route('sign_google')}}" class="button">
                        <img src="/img/Google Logo.svg" alt="">
                    </a>
                    <a href="{{route('sign_facebook')}}" class="button">
                        <img src="/img/Facebook Logo.svg" alt="">
                    </a>
                </div>


            </div>

        </form>
    </div>
@endsection

@section('page-js')
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye / eye slash icon
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    </script>
@endsection


