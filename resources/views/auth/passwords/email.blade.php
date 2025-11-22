@extends('layouts.portal_layout')

@section('content')
    <div class="log_reg_page_wrap page_content_wrap">
        <div class="header_wrap">
            <h2>Восстановление пароля</h2>
        </div>


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
                    <div class="input-group">
                        <p>Email</p>
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

                    <div class="login_buttons_wrap">
                        <button id="form_login" class="show_preloader_on_click preloader_button button" type="submit">
                            <span class="button__text">Напомнить пароль</span>
                        </button>

                        <a class="link" href="/login">Войти</a>
                    </div>

                </form>
    </div>

@endsection
