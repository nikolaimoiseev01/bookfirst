@extends('layouts.portal_layout')

@section('page-style')
    <style>
        .login-buttons-wrap {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        @media screen and (max-width: 1000px) {
            .login-buttons-wrap button {
                margin: inherit;
            }

            @media screen and (max-width: 500px) {
                .login-buttons-wrap {
                    flex-direction: column;
                    align-items: center;
                }

                .login-buttons-wrap button {
                    margin-bottom: 10px;
                }
            }
        }
    </style>
@endsection

@section('content')
    <div style="
    display: flex;
    justify-content: center;"
         class="content">
        <div class="log_reg_wrap">
            <div class="nav go-to">
                <a style="margin: 0;" href="/login" class="current"><h2>Вход</h2></a>
                <a style="margin: 0 0 0 30px;" href="/register"><h2>Регистрация</h2></a>
            </div>

            <div class="login_register_list">
                <form method="POST" id="login" action="{{ route('login') }}" id="login" class="test">
                    @csrf
                    @if ($errors->count() > 0)
                        <div class="error-wrap">
                            <ul style="margin: 5px;">
                                @foreach ($errors->all() as $error)
                                    <li>
                                        <p style="display:block !important; font-size: 22px;">{{ $error }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div style="padding-bottom:20px;" class="input">
                        <p>Email</p><br>
                        <input
                            id="email"
                            type="email"
                            placeholder="Email"
                            name="email"
                            autocomplete="email"
                            autofocus
                            required
                        >
                    </div>
                    <div style="padding-bottom:20px;" class="input">
                        <p>Пароль</p></br>
                        <input id="password"
                               type="password"
                               name="password"
                               placeholder="Пароль"
                        >
                    </div>

                    <div class="check-block">
                        <label for="remember"><p>Запомнить меня</p></label>
                        <input {{ old('remember') ? 'checked' : '' }} name="remember" id="remember" type="checkbox">
                    </div>

                    <div class="login-buttons-wrap">
                        <button id="form_login" class="preloader_button button" type="submit">
                            <span class="button__text">Войти</span>
                        </button>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="float:right;" class="link" href="">Восстановить
                                пароль</a>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection


