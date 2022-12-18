@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/books-example.css">
    <link rel="stylesheet" href="/css/social.css">

    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection


@section('content')

    <style>
        .hero {
            padding-top: 0;
            margin-left: 50px;
            margin-bottom: 30px;
        }
    </style>

    <div id="modal_video_hero" class="modal">
        <div class="modal-wrap">
            <iframe id="video_hero_iframe" width="740" height="420" src="https://www.youtube.com/embed/q9YOJS_6FMg"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    </div>
    <div class="welcome-block">

        <div class="hero-woman-wrap">
            <img class="woman" src="/img/social/women.svg">
            <img class="back-vector-left" src="/img/social/welcome_vector_left.svg">
        </div>


        <div class="hero-wrap">
            <img class="back-vector-right" src="/img/social/welcome_vector_right.svg">

            <div class="hero">
                <p id="hero_tablet_text" style="display: none;">Независимое Издательство</p>
                <p id="hero_name_mobile_text" style="display: none;">"Первая Книга"</p>
                <h1>Еще и просто социальная сеть</h1>
                {{--                <i>--}}
                {{--                    <div style="height: 70px;" class="typed_hero" id="typed_hero">--}}
                {{--                        <span class="cursor_blinking"></span>--}}
                {{--                    </div>--}}
                {{--                </i>--}}

                <div class="social_hero_word_block">
                    <h2># Создавай</h2>
                    <h2># Публикуй</h2>
                    <h2># Вдохновляй</h2>
                    <h2># Общайся</h2>
                </div>

                <div class="call-buttons">

                    {{--                    <div class="cta-container">--}}
                    {{--                        <a href="/#actual-block" class="cta-btn">Опубликовать</a>--}}
                    {{--                    </div>--}}
                    <a href="{{route('work.index')}}" class="log_check social_cta_button">Опубликовать</a>
                    {{--                    <a data-modal="modal_video_hero" class="modal-from how-it-works"><i style="margin-right: 8px;"--}}
                    {{--                                                                                        class="far fa-play-circle"></i>Как--}}
                    {{--                        это--}}
                    {{--                        работает</a>--}}

                </div>
            </div>
        </div>

    </div>

    <div class="last_works_block">

        <div style="margin-right: auto;" class="last_works_block__left">

            <div class="main_work">
                <div class="read_main_hovered">
                    <a target="_blank" data-id="{{$last_work_first['id']}}"
                       href="{{route('social.work_page', $last_work_first['id'])}}">Читать</a>
                </div>

                <div style="left: 30px; right: auto" class="other_work_icon_block">
                    <div class="other_work_icon_background">
                        <span>@if($last_work_first->work_like) {{ $last_work_first->work_like->count('id') ?? 0}} @else
                                0 @endif</span>
                        <i class="fa-regular fa-heart"> </i>
                    </div>
                    <div class="other_work_icon_background">
                        <span>@if($last_work_first->work_comment) {{ $last_work_first->work_comment->count('id') ?? 0}} @else
                                0 @endif</span>
                        <i class="fa-regular fa-comment"></i>
                    </div>
                </div>

                <img src="{{$last_work_first['picture_cropped'] ?? '/img/social/default_work_pic_cropped.png'}}" alt="">
                <div class="main_img_background"></div>
                <div class="main_work_info">

                    <p><b>{{$last_work_first['title']}}</b></p>
                    <a target="_blank" href="" class="link">{{($last_work_first->user['nickname'])
                                ? $last_work_first->user['nickname']
                                :$last_work_first->user['name'] . ' ' . $last_work_first->user['surname']}}</a>
                </div>
                <div class="main_work_info_background">
                </div>

            </div>
        </div>

        <div id="other_works_block" class="other_works_block">

            <div style="left: 0;" class="other_works_block_in">
                @foreach($last_works as $last_work)
                    <div id="other_work_{{$loop->index}}" data-id="{{$last_work['id']}}" class="other_work">
                        <div class="other_work_image_wrap" style="position: relative; height: 140px;">
                            <div class="read_main_hovered">
                                <a style="padding: 3px 20px;" target="_blank"
                                   href="{{route('social.work_page', $last_work['id'])}}">Читать</a>
                            </div>
                            <img src="{{$last_work['picture_cropped'] ?? '/img/social/default_work_pic_cropped.png'}}" alt="">
                        </div>

                        <div class="other_work_icon_block">
                            <div class="other_work_icon_background">
                                <span>@if($last_work->work_like) {{ $last_work->work_like->count('id') ?? 0}} @else
                                        0 @endif</span>
                                <i class="fa-regular  fa-heart"> </i>
                            </div>
                            <div class="other_work_icon_background">
                                <span>@if($last_work->work_comment) {{ $last_work->work_comment->count('id') ?? 0}} @else
                                        0 @endif</span>
                                <i class="fa-regular fa-comment"></i>
                            </div>
                        </div>
                        <div class="other_work_info">
                            <a href="{{route('social.user_page', $last_work['user_id'])}}" target="_blank"
                               class="link_social">
                                {{($last_work->user['nickname'])
                                    ? $last_work->user['nickname']
                                    :$last_work->user['name'] . ' ' . $last_work->user['surname']}}
                            </a>
                            <p>{{Str::limit(Str::ucfirst(Str::lower($last_work['title'])), 20, '...')}}</p>
                        </div>
                    </div>
                @endforeach

                <a href="{{route('social.all_works_feed')}}" style="border: 1px var(--grey_font) solid;" target="_blank" class="other_work more_works">
                    {{--                        <img src="/img/add-button.svg"></img>--}}
                    <p>Все произведения</p>
                </a>

            </div>
        </div>

        <div class="last_works_block__right">
            <div class="last_works_block__right_header_block"
                style="">
                <h2>Последние произведения</h2>
                <a href="{{route('social.all_works_feed')}}" style="    font-size: 25px;" target="_blank"
                   class="link_social">Лента</a>
            </div>


            <div class="change_ex_buttons">
                <a class="change_ex_buttons__inactive change_ex" onclick="move_work('left')" id="soc_ex_prev"><img
                        src="/img/prev.svg" alt=""></a>
                <div class="line-in">
                    <div class="line-out"></div>
                </div>
                <a class="change_ex" onclick="move_work('right')" id="soc_ex_next"> <img src="/img/next.svg" alt=""></a>
            </div>
        </div>

    </div>

    <div class="actual-title">
        <img src="/img/Ellipse 96.svg" alt="">
        <h2>Наши авторы</h2>
    </div>


    @if($users && count($users) > 0)
        <div style=" margin-bottom:20px;">
            <div id="search_users">
                <div class="last_users_block">
                    @foreach($users as $user)
                        <div class="container">
                            <div style="    display: flex; align-items: center;">
                                <img data-for-modal="modal_user_avatar_{{$user->id}}"
                                     style="width:60px;" class="show_modal user_avatar"
                                     src="{{($user->avatar ?? '/img/avatars/default_avatar.svg')}}" alt="user_avatar">
                            </div>

                            <div style="display: none;" id="modal_user_avatar_{{$user->id}}"
                                 class="cus-modal-container">
                                <img style="    width: 100%;"
                                     src="{{$user->avatar_cropped  ?? '/img/avatars/default_avatar.svg'}}">
                            </div>

                            <a href="{{route('social.user_page', ($user->id))}}" target="_blank"
                               style="display: flex;" class="link_social">
                                <h2 style="margin: 10px 0 0 0; font-size: 30px;">
                                    {{Str::limit(Str::ucfirst(Str::lower(($user->nickname) ? $user->nickname : $user->name . ' ' . $user->surname)), 17, '...')}}
                                </h2>
                            </a>

                            {{--                            <div class="last_users_stat_over_block">--}}
                            {{--                                <a href="{{route('social.user_page', ($user->id))}}" target="_blank" class="link_social">Читать</a>--}}
                            {{--                            </div>--}}


                            <div style="display: flex;" class="last_users_stat_block">
                                <div>
                                    <span class="tooltip" title="Подписчиков">
                                        <i style="color: var(--green)" class="fa-regular fa-user"></i>
                                        <p>{{$user->cnt_user_subs}}</p>
                                    </span>
                                </div>

                                <div>
                                    <span class="tooltip" title="Работ">
                                    <img style="height: 16px;" src="/img/small_book.svg" alt="">
                                    <p>{{$user->cnt_user_works}}</p>
                                                                       </span>
                                </div>
                                <div>
                                    <span class="tooltip" title="Лайков">
                                        <i class="fa-regular fa-heart"> </i>
                                        <p>{{$user->cnt_user_likes}}</p>
                                    </span>
                                </div>
                                <div>
                                    <span class="tooltip" title="Комментариев">
                                        <i class="fa-regular fa-comment"></i>
                                        <p>{{$user->cnt_user_comments}}</p>
                                   </span>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif



    {{--    <div class="last_works_block container">--}}
    {{--        @foreach($last_works as $last_work)--}}
    {{--            <a style="color: #4C4B46" href="{{route('social.work_page', $last_work['id'])}}">--}}
    {{--                <div onclick="window.location='{{route('social.work_page', $last_work['id'])}}'" class="work_prev_sm_block">--}}
    {{--                    <img style="width:40px;" src="{{($last_work->user['avatar'] ?? '/img/avatars/default_avatar.png')}}"--}}
    {{--                         alt="user_avatar">--}}
    {{--                    <div style="margin-left: 20px;">--}}
    {{--                        <div>--}}
    {{--                            <h2>{{Str::limit(Str::ucfirst(Str::lower($last_work['title'])), 20, '...')}}</h2>--}}
    {{--                        </div>--}}

    {{--                        <div>--}}
    {{--                            <a href="{{route('social.user_page', $last_work->user['id'])}}" class="link">--}}
    {{--                                {{($last_work->user['nickname']) ? $last_work->user['nickname'] : $last_work->user['name'] . ' ' . $last_work->user['surname']}}--}}
    {{--                            </a>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <img src="{{$last_work->picture}}" alt="">--}}

    {{--                    <div class="work_prev_sm_block_cl">--}}

    {{--                        <div style="margin-top: 5px; text-align: end;">--}}
    {{--                            5--}}
    {{--                            <i class="fa-regular fa-comment"></i>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </a>--}}
    {{--        @endforeach--}}
    {{--        <a style="justify-content: center; font-size: 30px; border: none; text-align: center"--}}
    {{--           class="link work_prev_sm_block">--}}
    {{--            Смотреть все--}}
    {{--        </a>--}}
    {{--    </div>--}}

@endsection


@section('page-js')
    <script src="/js/social-home_page-slider.js"></script>
@endsection
