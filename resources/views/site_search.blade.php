@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

{{--@section('page-style')--}}
{{--    <link rel="stylesheet" href="/css/home.css">--}}
{{--    <link rel="stylesheet" href="/css/social.css">--}}
{{--    <link rel="stylesheet" href="/css/books-example.css">--}}
{{--    <link rel="stylesheet" href="/plugins/slick/slick.css">--}}
{{--@endsection--}}


@section('content')

    <div class="social_search_page_wrap page_content_wrap">


        <img class="back-vector-right" src="/img/social/welcome_vector_right.svg">


        <h3 class="page_title"> По запросу "{{$search_input}}" мы нашли:</h3>

        @if($own_books && count($own_books) > 0)
            <div class="found_block_wrap">
                <div class="header_wrap">
                    <h4> Собственные книги ({{$own_books->total()}})</h4>
                    <a data-block="search_own_books" class="search_show_hide link social">Свернуть</a>
                </div>
                <div class="founds_wrap own_books_wrap" id="search_own_books">
                    @foreach($own_books as $own_book)
                        <div class="own_book_wrap container">
                            <img src="/{{$own_book['cover_3d']}}" alt="">
                            <div class="info_wrap">
                                <p class="title">{{$own_book['author']}}</p>
                                <p class="name">{{$own_book['title']}}</p>
                                <a href="/own_books?search_input={{$own_book['title']}}"
                                   target="_blank"
                                   class="button social">Подробнее</a>
                            </div>
                        </div>
                    @endforeach
                    <div>
                        {{$own_books->links()}}
                    </div>
                </div>
            </div>
        @endif


        @if($collections && count($collections) > 0)
            <div class="found_block_wrap">
                <div class="header_wrap">
                    <h4>Сборники ({{$collections->total()}})</h4>
                    <a data-block="search_collections" class="search_show_hide link social">Свернуть</a>
                </div>
                <div class="founds_wrap collections_wrap" id="search_collections">
                    @foreach($collections as $collection)
                        <div class="collection_wrap container">
                            <img src="/{{$collection['cover_3d']}}" alt="">
                            <div class="info_wrap">
                                <h4>{{$collection['title']}}</h4>
                                <a href="{{route('collection_page', $collection)}}" target="_blank"
                                   class="button social">Подробнее</a>
                            </div>

                        </div>
                    @endforeach
                </div>
                {{$collections->links()}}
            </div>
        @endif


        @if($users && count($users) > 0)
            <div class="found_block_wrap">
                <div class="header_wrap">
                    <h4> Пользователи ({{$users->total()}})</h4>
                    <a data-block="search_users"
                       class="search_show_hide link social">Свернуть</a>
                </div>
                <div class="users_wrap" id="search_users">
                    @foreach($users as $user)
                        <x-social.user-card :user="$user"/>
                    @endforeach
                </div>
                {{$users->links()}}
            </div>
        @endif
    </div>



    {{--            @if($works && count($works) > 0)--}}
    {{--                <div style="margin-top: 40px; margin-bottom:20px; display:flex;">--}}
    {{--                    <div style="" class="user_works_block">--}}
    {{--                        <div class="user_works_header">--}}
    {{--                            <h3 style="margin-right: 20px; font-size: 34px;"> Произведения ({{count($works)}})</h3>--}}
    {{--                            <a data-block="search_works" class="search_show_hide link_social">Свернуть</a>--}}
    {{--                        </div>--}}

    {{--                        <div id="search_works" class="user_works">--}}
    {{--                            @livewire('work-feed',[--}}
    {{--                            'works' => $works,--}}
    {{--                            'user_page_flag' => true--}}
    {{--                            ])--}}
    {{--                        </div>--}}
    {{--                    </div>--}}


    {{--                </div>--}}
    {{--            @endif--}}



@endsection

@push('page-js')
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
@endpush
