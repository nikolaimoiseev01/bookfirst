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
                        <p>
                            {{Str::limit($own_book['own_book_desc'] ?? 'Здесь скоро появится аннотация книги.', 300, '...')}}
                            @if(strlen($own_book['own_book_desc']) > 300)
                                <a data-for-modal="full_annotation_modal" class="link show_modal">Больше</a>
                            @endif
                        </p>
                        @if(strlen($own_book['own_book_desc']) > 300)
                            <div style="display:none;" id="full_annotation_modal" class="cus-modal-container">
                                <div class="search_modal_wrap">
                                    <h3>
                                        Аннотация<br>
                                    </h3>
                                    <p>{{$own_book['own_book_desc']}}</p>
                                </div>
                            </div>
                        @endif
                        <div class="buttons_wrap">
                            <a @if ($own_book['amazon_link'])
                                   target="_blank" href="{{$own_book['amazon_link']}}"
                               @endif
                               class="@if (!$own_book['amazon_link']) no_amazon @endif button">
                                Купить на Amazon
                            </a>

                            <form action="{{ route('payment.create_buying_own_book', $own_book['id'])}}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <button href="{{route('my_digital_sales')}}" id="btn-submit" type="submit"
                                        class="log_check pay-button button">
                                    Электронная версия (100 руб.)
                                </button>
                            </form>

                        </div>
                    </div>
                    <div class="col-card">
                        <div class="container">
                            <div class="row">
                                Кол-во страниц:&nbsp;<span>{{$own_book['pages']}}</span>
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


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-info-block">
            <div class="container">
                <div class="nav">
                    <a href="#reviews" class="cont_nav_item current">Отзывы</a>
                    @if($own_book['inside_file_cut'])
                        <a href="#read_part" id="read_part_link" target="_blank" class="cont_nav_item">Читать фрагмент</a>
                    @endif
                    <a style="float: right;" href="{{route('help_own_book')}}" target="_blank">Издать свою</a>
                </div>
                <div style="" class="list-wrap">

                    <livewire:portal.own-book-reviews :own_book="$own_book"/>

                    @if($own_book['inside_file_cut'])
                        <div id="read_part" class="hide">
                            <iframe src="/{{$own_book['inside_file_cut']}}"
                                    width="100%" height="600px"></iframe>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')

    <script src="/js/col-info-block.js"></script>

    <script>
        if(window.innerWidth < 768) {
            var cut_file_path = "/{{ $own_book->inside_file_cut }}";
            link = $('#read_part_link')
            link.attr('href', cut_file_path)
            link.removeClass('cont_nav_item')
        }
    </script>

@endpush
