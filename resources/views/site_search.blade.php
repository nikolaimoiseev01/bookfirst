@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/social.css">
    <link rel="stylesheet" href="/css/books-example.css">
    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection


@section('content')

    <style>
        .user_found_block {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .user_found_block .container {
            align-items: center;
            width: fit-content;
            padding: 15px 10px;
            margin: 10px 30px 10px 0;
            height: fit-content;
        }


        .right-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .right-wrap .info {
            display: flex;
            margin-bottom: 0;
            margin-top: 0;
            flex-direction: column;
        }

        .content {
            margin-bottom: 50px;
        }

    </style>

    <img style="z-index: -1;" class="back-vector-right" src="/img/social/welcome_vector_right.svg">

    <div class="content">

        <h3 style="font-size: 38px; margin-top: 30px;"> По запросу «<span
                style="color: var(--social_blue)">{{$search_input}}</span>» мы нашли:</h3>

        @if($own_books && count($own_books) > 0)
            <div style="margin-top: 40px; margin-bottom:20px;">
                <div style="" class="user_works_header">
                    <h3 style="margin-right: 20px; font-size: 34px;"> Собственные книги ({{count($own_books)}})</h3>
                    <a data-block="search_own_books" class="search_show_hide link_social">Свернуть</a>
                </div>
                <div id="search_own_books">


                    <div class="user_found_block">
                        @foreach($own_books as $own_book)
                            {{--                <a href="/own_books/#own_book_{{$own_book['id']}}">--}}
                            <div style="max-width: 300px; display: flex; flex-direction: column;" class="container">
                                <div class="image-wraper">
                                    <img style="    width: 120px;" src="/{{$own_book['cover_3d']}}" alt="">
                                </div>
                                <h3 style="text-align: center; font-size: 30px;">{{$own_book['author']}}</h3>
                                <p style="text-align: center;">{{$own_book['title']}}</p>
                                <a href="/own_books/#own_book_{{$own_book['id']}}" target="_blank"
                                   style="margin-top: 10px;"
                                   class="button_social">Подробнее</a>
                            </div>
                            {{--                </a>--}}
                        @endforeach

                    </div>
                    <div>
                        {{$own_books->links()}}
                    </div>
                </div>
            </div>
        @endif

        @if($collections && count($collections) > 0)
            <div style="margin-top: 40px; margin-bottom:20px;">
                <div style="" class="user_works_header">
                    <h3 style="margin-right: 20px; font-size: 34px;">Сборники ({{count($collections)}})</h3>
                    <a data-block="search_own_books" class="search_show_hide link_social">Свернуть</a>
                </div>
                <div id="search_own_books">
                    <div class="user_found_block">
                        @foreach($collections as $collection)
                            <div style="max-width: 300px; display: flex; flex-direction: column;" class="container">
                                <div class="image-wraper">
                                    <img style="    width: 120px;" src="/{{$collection['cover_3d']}}" alt="">
                                </div>
                                <h3 style="text-align: center; font-size: 23px;">{{$collection['title']}}</h3>
                                <a href="{{route('collection_page', $collection)}}" target="_blank"
                                   style="margin-top: 10px;" class="button_social">Подробнее</a>
                            </div>
                            {{--                </a>--}}
                        @endforeach

                    </div>
                    <div>
                        {{$collections->links()}}
                    </div>
                </div>
            </div>
        @endif


        @if($users && count($users) > 0)
            <div style="margin-top: 40px; margin-bottom:20px;">
                <div style="margin-top: 40px;" class="user_works_header">
                    <h3 style="margin-right: 20px; font-size: 34px;"> Пользователи ({{count($users)}})</h3>
                    <a data-block="search_users"
                       class="search_show_hide link_social">Свернуть</a>
                </div>
                <div id="search_users">
                <div class="user_found_block">
                    @foreach($users as $user)
                        <div class="container">
                            <div style="    display: flex; align-items: center;">
                                <img data-for-modal="modal_user_avatar_{{$user['id']}}"
                                     style="margin-right: 10px; width:30px;" class="show_modal user_avatar"
                                     src="{{($user['avatar'] ?? '/img/avatars/default_avatar.svg')}}" alt="user_avatar">
                            </div>

                            <div style="display: none;" id="modal_user_avatar_{{$user['id']}}"
                                 class="cus-modal-container">
                                <img style="    width: 100%;"
                                     src="{{$user['avatar_cropped']  ?? '/img/avatars/default_avatar.svg'}}">
                            </div>

                            <a href="{{route('social.user_page', ($user['id']))}}" target="_blank"
                               style="display: flex; margin-right: 10px;" class="link_social">
                                <h3 style="font-size: 30px; margin: 0;">
                                    {{Str::limit(Str::ucfirst(Str::lower(($user['nickname']) ? $user['nickname'] : $user['name'] . ' ' . $user['surname'])), 21, '...')}}
                                </h3>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div>
                    {{$users->links()}}
                </div>
                </div>
            </div>
        @endif



        @if($works && count($works) > 0)
            <div style="margin-top: 40px; margin-bottom:20px; display:flex;">
                <div style="" class="user_works_block">
                    <div class="user_works_header">
                        <h3 style="margin-right: 20px; font-size: 34px;"> Произведения ({{count($works)}})</h3>
                        <a data-block="search_works" class="search_show_hide link_social">Свернуть</a>
                    </div>

                    <div id="search_works" class="user_works">
                        @livewire('work-feed',[
                        'works' => $works,
                        'user_page_flag' => true
                        ])
                    </div>
                </div>


            </div>
        @endif
    </div>

    <script>
        $('.search_show_hide').click(function () {
            block = $('#' + $(this).attr('data-block'))
            button = $(this)

            if (block.is(":visible")) {
                block.slideUp()
                button.text('Показать')
            } else {
                block.slideDown()
                button.text('Свернуть')
            }
        })
    </script>
@endsection
