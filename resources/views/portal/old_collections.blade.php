@extends('layouts.portal_layout')

@section('page-style')
    <link rel="stylesheet" href="/plugins/slick/slick.css">
    <link rel="stylesheet" href="css/our_examples.css">
    <link rel="stylesheet" href="/css/books-index.css">
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

        .right-wrap {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }


        @media screen and (max-width: 664px) {
            .collections_titles a {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }

            .collections_titles {
                display: flex;
                flex-direction: column;
            }
        }

    </style>
@endsection

@section('page-title') Выпущенные сборники @endsection

@section('content')
    <div style="max-width: 1600px;" class="content">
        <div class="collections_titles" style="display: flex; justify-content: center;">
            <a style="color: #E0E0E0; margin-right: 50px;" href="{{route('actual_collections')}}"><h2 class="page-title">Актуальные</h2></a>
            <h2 style="color: #47AF98 !important;" class="page-title">Выпущенные</h2>
        </div>

        <div style="text-align: center">
            <div style="display: inline-block;" class="search-bar-wrap">
                <input required placeholder="Поиск..."
                       @if ($collection_input_search <> 'no_search') value="{{$collection_input_search}}" @else value=""
                       @endif id="collection_input_search" name="collection_input_search" type="text">

                <a id="collection_input_search_link" href="">
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
                        $("#collection_input_search").on('change', function (e) {
                            $("#collection_input_search_link").attr("href", "/our_collections/" + $(this).val());
                        });
                    });
                </script>
            </div>
            @if ($collection_input_search <> 'no_search')
                <a style="margin-left: 20px; color: #ff6868;" href="/our_collections" class="link">
                    Очистить поиск
                </a>
            @endif
        </div>
        <div style="margin-top: 30px; justify-content: center;" class="my-collections">
            @if ($collection_input_search <> 'no_search' & count($collections) == 0)
                <p>По запросу <i>"{{$collection_input_search}}"</i> сборников не найдено</p>
            @endif

            @foreach($collections as $collection)

                <div style="margin: 0 35px 35px 0; width: 450px;" class="container">
                    <div style="width: 65%" class="img-wrap">
                        <a data-effect="mfp-zoom-in" href="/{{$collection['cover_3d']}}" class="image-link-zoom">
                            <img style="max-height: 245px; width: auto; border-radius: 9px;" width="200px"
                                 src="/{{$collection['cover_3d']}}"
                                 alt="">
                        </a>
                    </div>
                    <div class="right-wrap">
                        <h3>{{$collection['title']}}</h3>
                        <div style="height:100%; margin-bottom: 0; margin-top: 0;" class="info">
                            <a @if ($collection['amazon_link']) target="_blank" href="{{$collection['amazon_link']}}"
                               @endif
                               style="font-size: 18px; box-shadow: none; text-align: center"
                               class="@if (!$collection['amazon_link']) no_amazon @endif button">Купить
                                на Amazon</a>
                            <form style="display:inline-block"
                                  action="{{ route('payment.create_buying_collection', $collection['id'])}}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <button href="{{route('my_digital_sales')}}" id="btn-submit" type="submit" style="font-size: 18px; box-shadow: none; text-align: center"
                                        class="log_check pay-button button">
                                    Электронная версия (100 руб.)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        {{$collections->links()}}
    </div>

@endsection

@section('page-js')

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
