@extends('layouts.portal_layout')

@section('page-style')
    <link rel="stylesheet" href="/plugins/slick/slick.css">
    <link rel="stylesheet" href="css/our_examples.css">
    <link rel="stylesheet" href="/css/books-index.css">
    <link rel="stylesheet" href="/css/home.css">
    <!-- Magnific Popup core CSS file -->
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">
    <style>
        ul {
            list-style-type: none;
        }

        .page-item {
            font-size: 28px;
        }

        .pagination {
            padding: 0;
            margin: 0;
            margin-bottom: 40px;
            display: flex;
            justify-content: center;
        }

        .page-link img {
            width: 15px !important;
        }

        #buttons-wrap {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        @media screen and (max-width: 900px) {
            .my-collections .container {
                flex-direction: column;
                align-items: center;
            }

            #buttons-wrap {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            #buttons-wrap a:first-child {
                margin-bottom: 20px;
                margin-right: 0 !important;
            }
        }

    </style>
@endsection

@section('page-title') Книги наших авторов @endsection

@section('content')
    <div style="max-width: 1600px;" class="content">
        <h2 class="page-title">Книги наших авторов</h2>
        <div style="text-align: center">
            <div style="display: inline-block;" class="search-bar-wrap">
                <input required placeholder="Поиск..."
                       @if ($own_book_input_search <> 'no_search') value="{{$own_book_input_search}}" @else value=""
                       @endif id="own_book_input_search" name="own_book_input_search" type="text">

                <a id="own_book_input_search_link" href="">
                    <svg width="15px" viewBox="0 0 612 612.01">
                        <g id="_4" data-name="4">
                            <path
                                d="M606.21,578.71l-158-155.48c41.38-45,66.8-104.41,66.8-169.84C515,113.44,399.7,0,257.49,0S0,113.44,0,253.39s115.27,253.4,257.48,253.4A259,259,0,0,0,419.56,450.2L578.18,606.3a20,20,0,0,0,28,0A19.29,19.29,0,0,0,606.21,578.71ZM257.49,467.8c-120.32,0-217.87-96-217.87-214.41S137.17,39,257.49,39s217.87,96,217.87,214.4S377.82,467.8,257.49,467.8Z"
                                transform="translate(-0.01 0)"/>
                        </g>
                    </svg>
                </a>
                <script>
                    $(function () {
                        $("#own_book_input_search").on('change', function (e) {
                            $("#own_book_input_search_link").attr("href", "/own_books/" + $(this).val());
                        });
                    });
                </script>
            </div>
            @if ($own_book_input_search <> 'no_search')
                <a style="margin-left: 20px; color: #ff6868;" href="/own_books" class="link">
                    Очистить поиск
                </a>
            @endif
        </div>
        <div style="margin-top: 30px; justify-content: center;" class="my-collections">
            @if ($own_book_input_search <> 'no_search' & count($own_books) == 0)
                <p>По запросу <i>"{{$own_book_input_search}}"</i> сборников не найдено</p>
            @endif

            @foreach($own_books as $own_book)

                <div id="own_book_{{$own_book['id']}}" style="margin-top: -85px; padding-top: 85px;">
                    <div style="margin-bottom: 60px; width: inherit; max-width: 1200px;" class="container">
                        <div style="    display: flex; align-items: center; justify-content: center;"
                             class="cover-wrap">
                            <img style="left: 0; position: inherit;" src="/{{$own_book['cover_3d']}}" alt="">
                        </div>
                        <div style="width: inherit; padding-right: 30px; padding-left: 30px; " class="info-wrap">
                            <h3> {{$own_book['author']}}: "{{$own_book['title']}}"</h3><br>

                            <p style="margin-top: 15px; margin-bottom: 20px;"> {{$own_book['own_book_desc']}} </p>
                            <div id="buttons-wrap">
                                <a @if ($own_book['amazon_link']) target="_blank" href="{{$own_book['amazon_link']}}" @endif
                                style="margin-right: 20px; box-shadow: none; text-align: center"
                                   class="@if (!$own_book['amazon_link']) no_amazon @endif button">Купить на Amazon.com</a>
                                <a style="box-shadow: none; text-align: center" href="/" class="log_check button">Электронная
                                    версия (100 руб.)</a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>
        {{$own_books->links()}}
    </div>

@endsection

@section('page-js')

    <script src="/js/anime.min.js"></script>
    <script src="/plugins/slick/slick.min.js"></script>


    <!-- jQuery 1.7.2+ or Zepto.js 1.0+ -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!-- Magnific Popup core JS file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.image-link-zoom').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                closeBtnInside: false,
                removalDelay: 500, //delay removal by X to allow out-animation
                // mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                },
                image: {
                    verticalFit: true
                },
                zoom: {
                    enabled: true,
                    duration: 300 // don't foget to change the duration also in CSS
                }
            });
        });
    </script>

    <script>
        $(".no_amazon").click(function (event) {
            event.preventDefault();
            Swal.fire({
                html: '<p  >На данный момент идет процесс добавления данного сборника на сайт Amazon.com. Ссылка станет активной в ближайшее время.</p>' +
                '<p style="margin-top: 10px; margin-bottom: 20px;">\n Мы предлагаем настроить оповещение Google для ISBN, чтобы получать уведомления о появлении книги в интернет-магазинах.</p>' +
                '<a target="_blank" href="https://support.google.com/websearch/answer/4815696?visit_id=637674760899190323-326960395&hl=ru&rd=3" class="button">Инструкция</a>',
                icon: 'info',
                showConfirmButton: false,
            })
        });
    </script>

@endsection
