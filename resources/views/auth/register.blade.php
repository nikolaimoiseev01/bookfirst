@extends('layouts.portal_layout')

@section('page-style')
    <style>
        .captcha-wrap {
            margin-bottom: 20px;
        }

        @media (max-width: 1000px) {
            .captcha-wrap {
                display: flex;
                align-items: center;
                justify-content: center;
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
                <a style="margin: 0;" href="/login"><h2>Вход</h2></a>
                <a style="margin: 0 0 0 30px;" class="current" href="/register"><h2>Регистрация</h2></a>
            </div>

            <div class="login_register_list">
                <form method="POST" action="{{ route('register') }}" id="register" class="hide">
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
                        <p>Имя</p><br>
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
                            required
                            autocomplete="name"
                            autofocus
                            placeholder="Имя">
                    </div>
                    <div style="padding-bottom:20px;" class="input">
                        <p>Фамилия</p></br>
                        <input @if($errors->has('surname'))
                               style="border: 1px red solid"
                               @else
                               style="border: 1px #6dc4b1 solid;"
                               @endif
                               id="surname" type="text"
                               name="surname"
                               value="{{ old('surname') }}"
                               required
                               autocomplete="surname"
                               autofocus
                               placeholder="Фамилия">
                    </div>
                    <div style="padding-bottom:20px;" class="input">
                        <p>Псевдоним</p>
                        <p style="font-size: 21px; color: #999999; margin-left: 5px;">(Необязательно. Можно будет
                            указать позже)</p></br>
                        <input @if($errors->has('nickname'))
                               style="border: 1px red solid"
                               @else
                               style="border: 1px #6dc4b1 solid;"
                               @endif
                               type="text"
                               placeholder="Псевдоним"
                               name="nickname" id="nickname">
                    </div>

                    <div style="padding-bottom:20px;" class="input">
                        <p>Email</p>
                        <p style="font-size: 21px; color: #999999; margin-left: 5px;">(на него придет подтверждение
                            регистрации)</p></br>
                        <input @if($errors->has('email'))
                               style="border: 1px red solid"
                               @else
                               style="border: 1px #6dc4b1 solid;"
                               @endif
                               placeholder="Email"
                               id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email">
                    </div>

                    <div style="padding-bottom:20px;" class="password input">
                        <p>Пароль</p>
                        <p style="font-size: 21px; color: #999999; margin-left: 5px;">(не менее 8-ми символов)</p></br>
                        <div style="position:relative; align-items: center; display:flex;">
                            <input @if($errors->has('password'))
                                   style="border: 1px red solid"
                                   @else
                                   style="border: 1px #6dc4b1 solid;"
                                   @endif
                                   placeholder="Пароль"
                                   id="password"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="new-password"
                            >
                            <i id="togglePassword" style="position: absolute; right: 15px; margin-left: 10px;"
                               class="far fa-eye-slash"></i>
                        </div>
                    </div>


                    <div style="flex-direction: column; display: flex; padding-bottom:20px;" class="password input">
                        <div>
                            <p>Подтвердите пароль</p>
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

                        <div style="position:relative; align-items: center; display:flex;">
                            <input @if($errors->has('password'))
                                   style="border: 1px red solid"
                                   @else
                                   style="border: 1px #6dc4b1 solid;"
                                   @endif
                                   placeholder="Пароль"
                                   id="password-confirm"
                                   type="password"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password"
                            >
                            <i id="togglePassword-confirm" style="position: absolute; right: 15px; margin-left: 10px;"
                               class="far fa-eye-slash"></i>
                        </div>
                    </div>

                    {!! NoCaptcha::renderJs('', false) !!}
                    <style>
                        #rc-anchor-container {
                            border: 1px solid red !important;
                        }
                    </style>
                    <div class="captcha-wrap">
                        {!! NoCaptcha::display() !!}
                    </div>

                    <button id="form_register" class="preloader_button button">
                        <span class="button__text">Зарегистрироваться</span>
                    </button>
                </form>


            </div>
        </div>
    </div>

@endsection

@section('page-js')



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


@endsection
