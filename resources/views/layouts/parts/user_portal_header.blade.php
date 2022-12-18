@section('user_header_block_scroll')
    <div style="width: 90%; display: none;" id="user_header_block_scroll">
        <div class="user_header_block_scroll">
            <div class="user_header_block_scroll_left_wrap" style="display: flex; justify-content: center; align-items: center;">
                <img style="width:50px;" class="user_avatar"
                     src="{{($last_work->user['avatar'] ?? '/img/avatars/default_avatar.svg')}}" alt="user_avatar">

                <div id="mobile_header_scroll_name_wrap">
{{--                    <a style="color: #363636" href="{{route('social.user_page', $user['id'])}}">--}}
{{--                        <h2>--}}
{{--                            {{($user['nickname']) ? $user['nickname'] : $user['name'] . ' ' . $user['surname']}}--}}
{{--                        </h2>--}}
{{--                    </a>--}}
                    <style>
                        #mobile_header_scroll_name_buttons span {
                            display: block;
                        }
                    </style>
                    <div style="display: none" id="mobile_header_scroll_name_buttons" class="user_header_buttons">
                        @livewire('subscribe-button', ['user_to_subscribe' => $user->id])
                        <a target="_blank"
                           class="write_span log_check @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user['id']) self_mes @endif "
                           @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) != $user->id)
                           href="{{route('new_chat', $user->id)}}"
                            @endif
                        >
                            <img src="/img/social/pen_icon.svg" alt="">
                            <p>Написать</p>
                        </a>
                        <a onclick="trigger_modal()" class="send_donate @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id) self_donate @endif
                        @if(Auth::check() && !((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id)) show_modal @endif
                            log_check"
                           data-for-modal="modal_user_donate">
                            <img src="/img/social/donate_icon.svg" alt="">
                            <p> Отправить донат</p>
                        </a>
                    </div>
                </div>


                @if(Cache::has('is_online' . $user->id))
                    <span style="border: 1px var(--green) solid; color: var(--green);"
                          class="user_now">В сети</span>
                @else
                    <span style="border: 1px #969393 solid; color: #969393;" class="user_now"> Не в сети</span>
                @endif
            </div>

            <div id="pc_header_scroll_name_buttons"  class="user_header_buttons">
                @livewire('subscribe-button', ['user_to_subscribe' => $user->id])
                <a target="_blank"
                   class="write_span log_check @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id) self_mes @endif "
                   @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) != $user->id)
                   href="{{route('new_chat', $user->id)}}"
                    @endif
                >
                    <img src="/img/social/pen_icon.svg" alt="">
                    <p class="write_span">Написать</p>
                </a>
                <a onclick="trigger_modal()" class="donate_span send_donate @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id) self_donate @endif
                @if(Auth::check() && !((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id)) show_modal @endif
                    log_check"
                   data-for-modal="modal_user_donate">
                    <img src="/img/social/donate_icon.svg" alt="">
                    <p> Отправить донат</p>
                </a>
                <script>

                    $('.show_modal').click(function test(e) {
                        e.preventDefault();
                        modal_object_id = $(this).attr('data-for-modal');
                        modal_object = $('#' + modal_object_id);
                        $('.cus-modal-wrap').append(modal_object);

                        $('#' + modal_object_id).show();
                        $('.cus-modal').fadeIn();
                    })


                    $('.cus-modal').on('click', function (event) {
                        if ($(event.target).has('.cus-modal-container').length === 1) {
                            $('.cus-modal-container').hide();
                            $('.cus-modal').fadeOut();
                        }
                    });




                    $(".log_check").click(function (event) {
                        @if(Auth::user()->id ?? 0 > 0)
                        @else
                        event.preventDefault();
                        Swal.fire({
                            html: '<p style="margin-bottom: 20px;" >Для выполнения действия необходимо быть авторизированным в системе. Для этого необходимо произвести вход или зарегистрироваться, если у Вас еще нет аккаунта.</p><div style="display: flex; justify-content: center;"><a style="margin-right: 10px;"  class="button" href="/login">Войти</a> <a style="margin-left: 10px;"  class="button" href="{{route('register')}}">Регистрация</a></div>',
                            // icon: 'info',
                            showConfirmButton: false,
                        })
                        @endif
                    });

                    $('.self_donate').click(function (event) {
                        event.preventDefault();
                        Swal.fire({
                            title: 'Что-то пошло не так',
                            icon: 'error',
                            html: "<p>Нельзя сделать донат самому себе :)</p>",
                            showConfirmButton: false,
                        })
                    })

                    $('.self_mes').click(function (event) {
                        event.preventDefault();
                        Swal.fire({
                            title: 'Что-то пошло не так',
                            icon: 'error',
                            html: "<p>Нельзя написать сообщение самому себе :)</p>",
                            showConfirmButton: false,
                        })
                    })



                </script>
            </div>
        </div>
    </div>
@endsection

<div class="user_header_block">

    <div class="user_header_left_block"
         style="display: flex;
         @if ($awards->count() > 0)
             flex-direction: column;
         @else
             flex-direction: row;
             align-items: center;
             width:100%;
             flex-wrap: wrap;
             justify-content: center;
         @endif
             ">
        <div style="
            margin-top:30px;
        @if ($awards->count() > 0)
        @else

        @endif

            "
             class="container user_header_main_block">

            <div>
                <img data-for-modal="modal_user_avatar" style="width:85px;" class="show_modal user_avatar"
                     src="{{($last_work->user['avatar'] ?? '/img/avatars/default_avatar.svg')}}" alt="user_avatar">
            </div>

            <div style="display: none;" id="modal_user_avatar"  class="cus-modal-container">
                <img style="    width: 100%;" src="{{$last_work->user['avatar_cropped']  ?? '/img/avatars/default_avatar.svg'}}">
            </div>

            <div style="width: 100%; position:relative;">
                <img id="more_user_actions" data-for-modal="modal_user_actions" class="show_modal"
                     src="/img/social/more_user_actions.svg" alt="">
                <div style="POSITION: RELATIVE; width: 100%; margin-bottom: 10px; display: flex; align-items: center;">

                    <div STYLE=" display: flex;">
                        <img style="width:85px;" class="user_avatar user_avatar_mobile"
                             src="{{($last_work->user['avatar'] ?? '/img/avatars/default_avatar.svg')}}"
                             alt="user_avatar">

                        <div style="display: flex;    flex-direction: column;    justify-content: space-evenly;">
                            <div class="user_status_mobile">
                                @if(Cache::has('is_online' . $user->id))
                                    <span style="color: var(--green);"
                                          class="user_now">В сети</span>
                                @else
                                    <span style="color: #969393;" class="user_now"> Не в сети</span>
                                @endif
                            </div>
                            <a style="color: #363636" href="{{route('social.user_page', $user['id'])}}">
                                <h2>
                                    {{($user['nickname']) ? $user['nickname'] : $user['name'] . ' ' . $user['surname']}}
                                </h2>
                            </a>
                        </div>
                    </div>

                    <div style="margin-right: 45px;" class="user_status">
                        @if(Cache::has('is_online' . $user->id))
                            <span style="border: 1px var(--green) solid; color: var(--green);"
                                  class="user_now">В сети</span>
                        @else
                            <span style="border: 1px #969393 solid; color: #969393;" class="user_now"> Не в сети</span>
                        @endif
                    </div>


                    <div style="display: none;" id="modal_user_actions" class="cus-modal-container">
                        <h3>Дополнительные действия</h3>
                        <div class="modal_user_more_actions">
                            <a href="{{route('chat_create',($user['nickname']) ? 'Жалоба на пользователя ' . $user['nickname'] : 'Жалоба на пользователя ' . $user['name'] . ' ' . $user['surname'])}}">
                                <img src="/img/danger-alert.svg" alt="">
                                <p style="color: var(--red)">Пожаловаться</p>
                            </a>

{{--                            <a href="">--}}
{{--                                <img src="/img/block_icon.svg" alt="">--}}
{{--                                <p style="color: var(--red)">Блокировать</p>--}}
{{--                            </a>--}}
                        </div>
                    </div>
                </div>

                <div class="user_header_buttons">
                    @livewire('subscribe-button', ['user_to_subscribe' => $user->id])
                    <a class="write_span @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id) self_mes @endif log_check"
                       href="{{route('new_chat', $user->id)}}">
                        <img src="/img/social/pen_icon.svg" alt="">
                        <p>Написать</p>
                    </a>

                    <script>
                        $('.self_mes').click(function (event) {
                            event.preventDefault();
                            Swal.fire({
                                title: 'Что-то пошло не так',
                                icon: 'error',
                                html: "<p>Нельзя написать сообщение самому себе :)</p>",
                                showConfirmButton: false,
                            })
                        })
                    </script>

                    <a class="donate_span send_donate @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id) self_donate @endif
                    @if(Auth::check() && !((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id)) show_modal @endif
                            log_check
                            "
                       id="send_donate" data-for-modal="modal_user_donate">
                        <img src="/img/social/donate_icon.svg" alt="">
                        <p> Отправить донат</p>
                    </a>

                    <script>
                        $('.self_donate').click(function (event) {
                            event.preventDefault();
                            Swal.fire({
                                title: 'Что-то пошло не так',
                                icon: 'error',
                                html: "<p>Нельзя сделать донат самому себе :)</p>",
                                showConfirmButton: false,
                            })
                        })
                    </script>

                    @if(Auth::check())
                        <div style="display: none;" id="modal_user_donate" class="cus-modal-container">
                            <h3 style="margin-bottom: 10px;">
                                Отправить донат автору: <br>
                                <span
                                    style="color: var(--social_blue)">{{($user['nickname']) ? $user['nickname'] : $user['name'] . ' ' . $user['surname']}}</span>
                            </h3>
                            <div style="flex-wrap: wrap; display:flex;">
                                @livewire('make-donate-form', ['user_to' => $user])
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <style>
            .user_header_stats_block div {
                @if ($awards->count() > 0)
                      min-height: 103px;
                @else
                      min-height: 114px;
                margin: 0 10px;
            @endif






            }
        </style>

        <div style="

        @if ($awards->count() > 0)
            margin-top:20px;
            justify-content: space-between;
        @else
            margin-top:30px;
            justify-content: space-evenly;
        @endif
            "
             class="user_header_stats_block">
            <div class="container">
                <img src="/img/social/user_stats_subscribers_icon.svg" alt="">
                <h2>{{count($user_stat_readers)}}</h2>
                <p>Читателей</p>
            </div>

            <div class="container">
                <img src="/img/social/user_stats_subscribed_icon.svg" alt="">
                <h2>{{count($user_stat_reads)}}</h2>
                <p>Читает</p>
            </div>

            <div class="container">
                <img src="/img/social/user_stats_work_icon.svg" alt="">
                <h2>{{count($works)}}</h2>
                <p>Работ</p>
            </div>

            <div class="container">
                <img src="/img/social/user_stats_star_icon.svg" alt="">
                <h2>{{count($awards)}}</h2>
                <p>Наград</p>
            </div>
        </div>
    </div>
    @if ($awards->count() > 0)
        <div class="container user_header_awards_block">
            <div class="user_header_awards_block_header">
                <h2>Награды</h2>
                @if($awards->count() > 3)
                    <a data-for-modal="modal_user_awards_all" class="show_modal link_social">
                        Все награды ({{$awards->count()}})</a>
                @endif
                <div style="display: none;" id="modal_user_awards_all" class="cus-modal-container">
                    <div class="user_header_awards_block_header_items">
                        @foreach($awards as $award)
                            <div class="award_block">
                                <img src="{{$award->award_type['picture']}}" alt="">
                                <p>{{$award->award_type['name']}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="user_header_awards_block_header_items">
                @foreach($awards->take(3) as $award)
                    <div class="award_block">
                        <img src="{{$award->award_type['picture']}}" alt="">
                        <p>{{$award->award_type['name']}}</p>
                    </div>
                @endforeach
            </div>

        </div>
    @endif


    <script>
        var scrollTop = $(window).scrollTop(),
            elementOffset = $('.user_header_block').offset().top,
            nav_height = (elementOffset - scrollTop),
            user_header_block_height = $('.user_header_block').outerHeight(),
            show_user_header_height = user_header_block_height + nav_height;


        $(document).ready(function () {
            check_user_header_on_scroll();
            width = $(window).width()
            if (width <= 1000) {
                $('.send_donate p').text('Донат')
            } else {
                $('.send_donate p').text('Отправить донат')
            }
        });

        function check_user_header_on_scroll() {
            var y = $(this).scrollTop();
            scrollTop = $(window).scrollTop();

            // console.log('                ')
            // console.log('---- Start ----')
            // console.log('y: ' + y);
            // console.log('scrollTop: ' + scrollTop);
            // console.log('show_user_header_height: ' + show_user_header_height);
            // console.log('scrollTop: ' + scrollTop);
            // console.log('---- End ----')
            // console.log('                ')

            if (y > show_user_header_height || scrollTop > show_user_header_height) {
                $('#user_header_block_scroll').slideDown(300);
            } else {
                $('#user_header_block_scroll').slideUp(300);
            }
        }

        check_user_header_on_scroll();

        $(document).scroll(function () {
            check_user_header_on_scroll();
        });


        $(window).resize(function (e) {
                width = $(window).width()
                if (width <= 1000) {
                    $('.send_donate p').text('Донат')
                } else {
                    $('.send_donate p').text('Отправить донат')
                }


        });



    </script>

</div>
