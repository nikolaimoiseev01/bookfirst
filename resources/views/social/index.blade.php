@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/books-example.css">
    <link rel="stylesheet" href="/css/social.css">

    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection


@section('content')

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
                <i>
                    <div style="height: 70px;" class="typed_hero" id="typed_hero">
                        <span class="cursor_blinking"></span>
                    </div>
                </i>

                <div class="call-buttons">

                    <div class="cta-container">
                        <a href="/#actual-block" class="cta-btn">Опубликовать</a>
                    </div>
                    <a data-modal="modal_video_hero" class="modal-from how-it-works"><i style="margin-right: 8px;"
                                                                                        class="far fa-play-circle"></i>Как
                        это
                        работает</a>

                </div>
            </div>
        </div>

    </div>

    <div class="last_works_block">

        <div class="last_works_block__left">

            <div class="main_work">

                <img src="/img/social/default_work_pic.png" alt="">
                    <div class="main_work_info">
                        <p><b>Стихи о любви</b></p>
                        <a href="" class="link">Анатоли Васерман</a>
                    </div>

            </div>
        </div>

        <div id="other_works_block" class="other_works_block">
            <div style="left: 0;" class="other_works_block_in">
                @foreach($last_works as $last_work)
                <div id="other_work_0" class="other_work">
                    <img src="{{$last_work['picture']}}" alt="">
                    <div class="other_work_icon_block">
                        <div  class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular like_icon fa-heart"> </i>
                        </div>
                        <div class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular fa-comment"></i>
                        </div>
                    </div>
                    <div class="other_work_info">
                        <a href="" class="link">{{($last_work->user['nickname']) ? $last_work->user['nickname'] : $last_work->user['name'] . ' ' . $last_work->user['surname']}}</a>
                        <p>{{$last_work['title']}}</p>
                    </div>
                </div>
                @endforeach
                <div id="other_work_1" class="other_work">
                    <img src="/img/social/default_work_pic.png" alt="">
                    <div class="other_work_icon_block">
                        <div  class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular like_icon fa-heart"> </i>
                        </div>
                        <div class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular fa-comment"></i>
                        </div>
                    </div>
                    <div class="other_work_info">
                        <a href="" class="link">Иванов Алексей</a>
                        <p>Новый мир</p>
                    </div>
                </div>
                <div id="other_work_2" class="other_work">
                    <img src="/img/social/default_work_pic.png" alt="">
                    <div class="other_work_icon_block">
                        <div  class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular like_icon fa-heart"> </i>
                        </div>
                        <div class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular fa-comment"></i>
                        </div>
                    </div>
                    <div class="other_work_info">
                        <a href="" class="link">Александр Ватрушкин</a>
                        <p>Старый мир</p>
                    </div>
                </div>
                <div id="other_work_3" class="other_work">
                    <img src="/img/social/default_work_pic.png" alt="">
                    <div class="other_work_icon_block">
                        <div  class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular like_icon fa-heart"> </i>
                        </div>
                        <div class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular fa-comment"></i>
                        </div>
                    </div>
                    <div class="other_work_info">
                        <a href="" class="link">Некрасов</a>
                        <p>2 произв...</p>
                    </div>
                </div>
                <div id="other_work_4" class="other_work">
                    <img src="/img/social/default_work_pic.png" alt="">
                    <div class="other_work_icon_block">
                        <div  class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular like_icon fa-heart"> </i>
                        </div>
                        <div class="other_work_icon_background">
                            <span>12</span>
                            <i class="fa-regular fa-comment"></i>
                        </div>
                    </div>
                    <div class="other_work_info">
                        <a href="" class="link">Александр Пушкин</a>
                        <p>Название произв...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="last_works_block__right">

            <h2>Последние произведения</h2>



            <div class="change_ex_buttons">
                <a class="change_ex_buttons__inactive change_ex" onclick="move_work('left')" id="soc_ex_prev"><img src="/img/prev.svg" alt=""></a>
                <div class="line-in">
                    <div class="line-out"></div>
                </div>
                <a class="change_ex" onclick="move_work('right')" id="soc_ex_next"> <img src="/img/next.svg" alt=""></a>
            </div>
        </div>

    </div>

    {{--    <div class="actual-title">--}}
    {{--        <img src="/img/Ellipse 96.svg" alt="">--}}
    {{--        <h2>Последние работы</h2>--}}
    {{--    </div>--}}



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
