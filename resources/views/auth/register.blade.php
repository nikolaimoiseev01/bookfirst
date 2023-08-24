@extends('layouts.portal_layout')

@section('content')
    <div class="log_reg_page_wrap page_content_wrap">
        <div class="header_wrap">
            <a href="/login">
                <h2>Вход</h2>
            </a>
            <h2>Регистрация</h2>
        </div>

        <form method="POST" action="{{ route('register') }}">
            <input style="display: none" type="text" id="utm_source" name="utm_source">
            <input style="display: none" type="text" id="utm_medium" name="utm_medium">

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
            <div class="input-group">
                <p>Имя</p>
                <input
                    @if($errors->has('name'))
                    style="border: 1px red solid"
                    @else
                    style="border: 1px #6dc4b1 solid;"
                    @endif
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    autofocus>
            </div>


            <div class="input-group">
                <p>Фамилия</p>
                <input @if($errors->has('surname'))
                       style="border: 1px red solid"
                       @else
                       style="border: 1px #6dc4b1 solid;"
                       @endif
                       id="surname" type="text"
                       name="surname"
                       value="{{ old('surname') }}"
                       autocomplete="surname"
                       autofocus>
            </div>

            <div class="input-group">
                <div class="name_wrap">
                    <p>Псевдоним</p>
                    <p class="desc">
                        Необязательно. Можно будет указать позже
                    </p>
                </div>
                <input @if($errors->has('nickname'))
                       style="border: 1px red solid"
                       @else
                       style="border: 1px #6dc4b1 solid;"
                       @endif
                       type="text"
                       name="nickname" id="nickname">
            </div>

            <div class="input-group">
                <div class="name_wrap">
                    <p>Email</p>
                    <p class="desc">
                        На него придет подтверждение регистрации
                    </p>
                </div>
                <input @if($errors->has('email'))
                       style="border: 1px red solid"
                       @else
                       style="border: 1px #6dc4b1 solid;"
                       @endif
                       id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       autocomplete="email">
            </div>

            <div class="password-group input-group">
                <div class="name_wrap">
                    <p>Пароль</p>
                    <p class="desc">
                        Не менее 8-ми символов
                    </p>
                </div>
                <div>
                    <input @if($errors->has('password'))
                           style="border: 1px red solid"
                           @else
                           style="border: 1px #6dc4b1 solid;"
                           @endif
                           placeholder="Пароль"
                           id="password"
                           type="password"
                           name="password"
                           autocomplete="new-password"
                    >
                    <i id="togglePassword" class="far fa-eye-slash"></i>
                </div>
            </div>


            <div class="password-group input-group">
                <p>Подтвердите пароль</p>


                <div>
                    <input @if($errors->has('password'))
                           style="border: 1px red solid"
                           @else
                           style="border: 1px #6dc4b1 solid;"
                           @endif
                           placeholder="Пароль"
                           id="password-confirm"
                           type="password"
                           name="password_confirmation"
                           autocomplete="new-password"
                    >
                    <i id="togglePassword-confirm" class="far fa-eye-slash"></i>
                </div>

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

            {!! NoCaptcha::renderJs('', false) !!}
            <style>

            </style>
            <div class="captcha-wrap">
                {!! NoCaptcha::display() !!}
            </div>

            <button id="form_register" class="register_button show_preloader_on_click button">
                Зарегистрироваться
            </button>


        </form>
    </div>
    </div>

@endsection

@push('page-js')

    <script>
        function getCook(cookiename) {
            // Get name followed by anything except a semicolon
            var cookiestring = RegExp(cookiename + "=[^;]+").exec(document.cookie);
            // Return everything after the equal sign, or an empty string if the cookie name not found
            return decodeURIComponent(!!cookiestring ? cookiestring.toString().replace(/^[^=]+./, "") : "");
        }
        utm_source_cookie = getCook('utm_source');
        utm_medium_cookie = getCook('utm_medium');

        console.log(utm_source_cookie)

        $('#utm_source').val(utm_source_cookie);
        $('#utm_medium').val(utm_medium_cookie);
    </script>

    <script>
        $('.password-group').on("input", function () {
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

        const togglePassword_confirm = document.querySelector('#togglePassword-confirm');
        const password_confirm = document.querySelector('#password-confirm');

        togglePassword_confirm.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password_confirm.getAttribute('type') === 'password' ? 'text' : 'password';
            password_confirm.setAttribute('type', type);
            // toggle the eye / eye slash icon
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    </script>


@endpush
