@extends('layouts.portal_layout')

@section('page-style')
    <link rel="stylesheet" href="/plugins/slick/slick.css">
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

        .right-wrap {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .collections_titles {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        #buttons-wrap {
            display: flex;
            align-items: center;
            height: 100%;
        }


        #buttons-wrap a {
            font-size: 22px;
            padding: 3px 40px;
            box-shadow: none;

        }
        .container {
            position:relative;
            margin-bottom: 60px;
            margin: auto;
            width: inherit;
            max-width: 1100px;
        }

        h3 {
            width: fit-content;
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




        @media screen and (max-width: 425px) {
            #buttons-wrap {
                flex-direction: column;
            }

            #buttons-wrap a {
                margin: 0 !important;
            }

            #buttons-wrap a:last-child {
                margin-top: 20px !important;
            }
        }
    </style>
@endsection

@section('page-title') Актуальные сборники @endsection

@section('content')
    <div style="max-width: 1600px;" class="content">
        <div class="collections_titles">
            <h2 style="color: #47AF98;" class="page-title">Актуальные</h2></a>
            <a href="{{route('old_collections')}}" style="color: #E0E0E0 !important; margin-left: 50px;"><h2
                    class="page-title">Выпущенные</h2></a>
        </div>

        <div style="margin-top: 30px; justify-content: center;" class="my-collections">
            @foreach($collections as $collection)

                <div id="collection_{{$collection['id']}}" style="margin-top: -85px; padding-top: 85px;">
                    <div
                        class="container">
                        <div class="label-wrap">
                            <div class="label">
                                <div>Заявки до:</div>
                                <div>{{$collection['col_date1']}}</div>
                            </div>
                        </div>
                        <div style="width:550px; display: flex; align-items: center; justify-content: center;"
                             class="cover-wrap">
                            <a style="text-align: center;" data-effect="mfp-zoom-in" href="/{{$collection['cover_3d']}}"
                               class="image-link-zoom">
                                <img style="width: 90%; left: 0; position: inherit;" src="/{{$collection['cover_3d']}}"
                                     alt="">
                            </a>
                        </div>
                        <div
                            style="display: flex; flex-direction: column; width: inherit; padding: 20px 30px; margin: 0;"
                            class="info-wrap">
                            <h3>{{$collection['title']}}</h3>

                            <p style="margin-top: 20px; margin-bottom: 20px;"> {{$collection['col_desc']}} </p>
                            <div id="buttons-wrap">
                                <a href="{{route('collection_page',$collection['id'])}}" class="button">Подробнее</a>
                                <a style="margin-left: 30px;" href="{{route('participation_create',$collection['id'])}}"
                                   class="log_check button">Принять
                                    участие</a>
                            </div>
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


@endsection
