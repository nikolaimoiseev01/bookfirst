@extends('layouts.portal_layout')

@section('content')

    <div class="log_reg_page_wrap page_content_wrap">
        <div class="header_wrap">
            <h2>Восстановление пароля</h2>
        </div>

        <form method="POST" id="login" action="{{ route('password.update') }}" id="login" class="test">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
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
            <div class="input-group">
                <p>Email</p>
                <input
                    id="email"
                    type="email"
                    placeholder="Email"
                    name="email"
                    value="{{ $email ?? old('email') }}"
                    autofocus
                    required
                    {{--                            disabled--}}
                >
            </div>
            <div class="input-group">
                <p>Пароль</p>
                <input
                    placeholder="Пароль"
                    id="password"
                    type="password"
                    name="password"
                    class="password"
                    required
                >
            </div>

            <div class="input-group password-group">
                <p>Подтвердите пароль</p>
                <input id="password-confirm"
                       type="password"
                       name="password_confirmation"
                       placeholder="Пароль"
                       class="password"
                       required
                >

                <div class="pass-conf-text-wrap">
                    <svg style="fill: rgb(209 98 98); display:none;" id="cross_pass" data-name="Слой 1"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M256,512C114.84,512,0,397.16,0,256S114.84,0,256,0,512,114.84,512,256,397.16,512,256,512Zm0-475.43C135,36.57,36.57,135,36.57,256S135,475.43,256,475.43,475.43,377,475.43,256,377,36.57,256,36.57Z"
                            transform="translate(0 0)"/>
                        <path
                            d="M347.43,365.71a18.22,18.22,0,0,1-12.93-5.35L151.64,177.5a18.29,18.29,0,0,1,25.86-25.86L360.36,334.5a18.28,18.28,0,0,1-12.93,31.21Z"
                            transform="translate(0 0)"/>
                        <path
                            d="M164.57,365.71a18.28,18.28,0,0,1-12.93-31.21L334.5,151.64a18.29,18.29,0,0,1,25.86,25.86L177.5,360.36A18.22,18.22,0,0,1,164.57,365.71Z"
                            transform="translate(0 0)"/>
                    </svg>
                    <svg style="fill: #47af98; display:none;" id="success_pass" data-name="Capa 1"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 477.87 477.87">
                        <path
                            d="M238.93,0C107,0,0,107,0,238.93S107,477.87,238.93,477.87s238.94-107,238.94-238.94S370.83.14,238.93,0Zm0,443.73c-113.11,0-204.8-91.69-204.8-204.8s91.69-204.8,204.8-204.8,204.8,91.69,204.8,204.8S352,443.61,238.93,443.73Z"
                            transform="translate(0 0)"/>
                        <path
                            d="M370.05,141.53a17.09,17.09,0,0,0-23.72,0h0l-158.6,158.6-56.2-56.2A17.07,17.07,0,1,0,107,267.65l.42.41,68.27,68.27a17.07,17.07,0,0,0,24.13,0L370.47,165.66A17.07,17.07,0,0,0,370.05,141.53Z"
                            transform="translate(0 0)"/>
                    </svg>
                    <p id="pass-conf-text"></p>

                </div>
            </div>

            <button id="form_login" class="show_preloader_on_click preloader_button button" type="submit">
                <span class="button__text">Сохранить пароль</span>
            </button>

        </form>
    </div>
@endsection

@push('page-js')
    <script>
        $('.password').on("input", function () {
            console.log('1: ' + $('#password').val() + '  2: ' + $('#password-confirm').val());
            if ($('#password').val() === $('#password-confirm').val()) {
                $('#pass-conf-text').text('Пароли совпадают')
                $('#pass-conf-text').css('color', '#47af98')
                $('#success_pass').show();
                $('#cross_pass').hide();
            } else {
                $('#pass-conf-text').text('Пароли не совпадают')
                $('#pass-conf-text').css('color', 'rgb(209, 98, 98)')
                $('#success_pass').hide();
                $('#cross_pass').show();
            }
        })

        $(window).on('load', function () {

        })

    </script>
@endpush
