@extends('layouts.portal_layout')

@section('page-title')
    {{$own_book['title']}}
@endsection

@section('page-style')
    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection

@section('content')
    <div class="page_content_wrap collection_page_wrap own_book_user_page_wrap">
        <div class="content">

            <div class="collection-block">
                <div>
                    <img class="cover" src="/{{$own_book['cover_3d']}}" alt="">
                </div>
                <div class="right-collection-info">
                    <div class="col-text">
                        <h3>{{$own_book['title']}}</h3>
                        <a href="{{route('social.user_page', $own_book['user_id'])}}"
                           class="link">{{$own_book['author']}}</a>
                        <p>{{$own_book['own_book_desc']}}</p>
                    </div>
                    <div class="col-card">
                        <div class="container">
                            <div class="row">
                                Кол-во страниц:&nbsp;<span>>{{$own_book['pages']}}</span>
                            </div>
                            <div class="row">
                                Первоначальный
                                тираж:&nbsp;<span>{{($own_book->printorder['books_needed'] ?? 0) + 10}}</span>
                            </div>
                            <div class="row">
                                Обложка:&nbsp;
                                <span>
                                    {{($own_book->printorder['cover_type'] ?? 'soft') == 'soft' ? 'мягкая' : 'твердая'}}
                                </span>
                            </div>
                            <div class="row">
                                Внутренний блок:
                                <span>@if(($own_book['color_pages'] ?? 0) > 0)
                                        цветной
                                    @else
                                        черно-белый
                                    @endif
                                </span>
                            </div>
                            <div class="row">
                                <a style="    font-size: 25px; padding: 3px 35px;" href="{{route('own_book_create')}}"
                                   class="log_check button">Подать заявку!</a>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-info-block">
            <div class="container">
                <div class="nav">
                    <a href="#reviews" class="cont_nav_item current">Отзывы</a>
                    <a href="#read_part" class="cont_nav_item">Читать фрагмент</a>
                    <a style="float: right;" href="{{route('help_own_book')}}" target="_blank">Издать свою</a>
                </div>
                <div style="" class="list-wrap">

                    <livewire:portal.own-book-reviews :own_book="$own_book"/>

                    <div id="read_part" class="hide">
                        Читать фрагмент
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')

    <script src="/js/col-info-block.js"></script>

@endpush
