@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('page-style')
    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection


@section('content')

    <div class="social_index_page_wrap">

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
                    <div class="social_hero_word_block">
                        <h4># Создавай</h4>
                        <h4># Публикуй</h4>
                        <h4># Вдохновляй</h4>
                        <h4># Общайся</h4>
                    </div>

                    <div class="call-buttons">
                        <a href="{{route('work.index')}}" class="log_check social_cta_button">Опубликовать</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="last_works_block">

            <div class="left_wrap">
                <x-social.work-card
                    :work="$last_work_first"
                    flgbigwork="true"
                />
            </div>

            <div class="right_wrap">
                <div class="title_wrap">
                    <h2>Последние произведения</h2>
                    <a href="{{route('social.all_works_feed')}}" class="link social">Лента</a>
                </div>

                <div class="works_wrap">
                    @foreach($last_works as $last_work)
                        <x-social.work-card
                            :work="$last_work"
                            flgbigwork="false"
                        />
                    @endforeach
                    <a href="{{route('social.all_works_feed')}}" target="_blank" class="other_work more_works">
                        <p>Все произведения</p>
                    </a>
                </div>

                @push('page-js')
                    <script>
                        $('.works_wrap').slick({
                            infinite: false,
                            slidesToShow: 5,
                            slidesToScroll: 1,
                            variableWidth: true,
                            prevArrow: "#prev_slide",
                            nextArrow: "#nex_slide",
                            responsive: [
                                {
                                    breakpoint: 1200,
                                    settings: {
                                        slidesToShow: 3
                                    }
                                },
                                {
                                    breakpoint: 480,
                                    settings: {
                                        slidesToShow: 1
                                    }
                                }
                            ]
                        })
                    </script>
                @endpush

                <div class="navigation_wrap">
                    <a id="prev_slide"><img src="/img/prev.svg" alt=""></a>
                    <div class="line_wrap">
                        <div class="pointer"></div>
                    </div>
                    <a id="nex_slide"><img src="/img/next.svg" alt=""></a>
                </div>

            </div>

        </div>

        <div class="random_users_wrap">
            <div class="main_title_wrap">
                <img src="/img/Ellipse 96.svg" alt="">
                <h2>Наши авторы</h2>
            </div>


            <div class="users_wrap">
                @foreach($users as $user)
                    <x-social.user-card :user="$user"/>
                @endforeach
            </div>
            <a href="{{route('social.all_works_feed')}}" class="to_all_works link social">Все авторы</a>
        </div>
    </div>
@endsection


@section('page-js')
    <script src="/js/social-home_page-slider.js"></script>
@endsection
