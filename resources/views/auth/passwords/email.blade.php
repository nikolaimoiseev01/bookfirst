@extends('layouts.portal_layout')

@section('content')
    <div style="
    display: flex;
    justify-content: center;"
         class="content">
        <div class="log_reg_wrap">
            <div class="nav go-to">
                <a href="/login" class="current"><h2>Вход</h2></a>
                <a href="/register"><h2>Регистрация</h2></a>
            </div>

            <div class="login_register_list">
                <form method="POST" id="login" action="{{ route('password.email') }}" id="login" class="test">
                    @csrf


                    @error('email')
                    <div class="error-wrap">
                        <ul style="margin: 5px;">
                            <li>
                                <p style="display:block !important; font-size: 22px;">{{ $message }}</p>
                            </li>
                        </ul>
                    </div>
                    @enderror


                    @if (session('status'))
                        <div class="error-wrap">
                            <ul style="margin: 5px;">
                                <li>
                                    <p style="display:block !important; font-size: 22px;">{{ session('status') }}</p>
                                </li>
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
                            value="{{ old('email') }}"
                            autofocus
                            required
                        >
                    </div>

                    <button id="form_login" class="preloader_button button" type="submit">
                        <span class="button__text">Напомнить пароль</span>
                    </button>

                    <a style="float:right;" class="link" href="/login">Войти</a>

                </form>
            </div>
        </div>
    </div>

@endsection
