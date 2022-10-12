@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/home.css">
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

        <div class="hero-wrap">
            <img class="back-vector-left" src="/img/Back vector left.svg">

            <div class="hero">
                <p id="hero_tablet_text" style="display: none;">Независимое Издательство</p>
                <p id="hero_name_mobile_text" style="display: none;">"Первая Книга"</p>
                <h1>Ваш шаг в мир литературы</h1>
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
        <div class="hero-woman-wrap">
            <img class="woman" src="/img/woman.svg">
            <img class="back-vector-right" src="/img/Back vector right.svg">
        </div>
    </div>


    <div class="actual-title">
        <img src="/img/Ellipse 96.svg" alt="">
        <h2>Последние работы</h2>
    </div>


    <div class="last_works_block container">
        @foreach($last_works as $last_work)
{{--            <a style="color: #4C4B46" href="{{route('social.work_page', $last_work['id'])}}">--}}
                <div onclick="window.location='{{route('social.work_page', $last_work['id'])}}'" class="work_prev_sm_block">
                    <img style="width:40px;" src="{{($last_work->user['avatar'] ?? '/img/avatars/default_avatar.png')}}"
                         alt="user_avatar">
                    <div style="margin-left: 20px;">
                        <div>
                            <h2>{{Str::limit(Str::ucfirst(Str::lower($last_work['title'])), 20, '...')}}</h2>
                        </div>

                        <div>
                            <a href="{{route('social.user_page', $last_work->user['id'])}}" class="link">
                                {{($last_work->user['nickname']) ? $last_work->user['nickname'] : $last_work->user['name'] . ' ' . $last_work->user['surname']}}
                            </a>
                        </div>
                    </div>

                    <div class="work_prev_sm_block_cl">
                        @livewire('like-icon')
                        <div style="margin-top: 5px; text-align: end;">
                            5
                            <i class="fa-regular fa-comment"></i>
                        </div>
                    </div>
                </div>
{{--            </a>--}}
        @endforeach
        <a style="justify-content: center; font-size: 30px; border: none; text-align: center"
           class="link work_prev_sm_block">
            Смотреть все
        </a>
    </div>

@endsection
