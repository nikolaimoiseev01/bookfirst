@extends('layouts.admin_layout')

@section('title', 'Главная')
@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div style="align-items: flex-start;" class="row mb-2">
                <div class="d-flex">
                    <h1 class="m-0">Собственные книги</h1>
                </div><!-- /.col -->
                <div style="width: 25%;" class="ml-3 input-group mb-3">
                    <!-- /btn-group -->
                    <input onkeyup="collection_filter()" id="collection_input" type="text" placeholder="Искать..."
                           class="form-control">
                </div>

                <a href="{{route('closed_own_books')}}" class="ml-3 btn btn-outline-info">Закрытые книги</a>

            </div><!-- /.row -->
            <div>
                <div style="{{--   isplay: inline-flex; padding: 10px; border: 1px #dadada solid;--}}" class="row">

                    <div class="mr-3 button-group">
                        <button type="button" style="font-weight: 100; font-size: 20px"
                                class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            Общий статус <span id="own_book_statuses_button_text">(7/7)</span>
                        </button>
                        <ul style="padding:10px;" class="dropdown-menu">
                            <span id="check_none_own_book" class="hover_pointer">Убрать все</span>
                            @foreach($own_book_statuses as $own_book_status)
                                <li class="check_own_book_status">
                                    <input type="checkbox" class="own_book_statuses"
                                           id="own_book_status_{{$own_book_status['id']}}" checked="">
                                    <label style="font-size: 18px; font-weight: 100;"
                                           for="own_book_status_{{$own_book_status['id']}}">
                                        {{Str::ucfirst($own_book_status['status_title'])}}
                                    </label>
                                </li>
                            @endforeach
                        </ul>

                    </div>

                    <div class="mr-3 button-group">
                        <button type="button" style="font-weight: 100; font-size: 20px"
                                class=" btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            Статус макета <span id="own_book_inside_statuses_button_text">(6/6)</span>
                        </button>
                        <ul style="padding:10px;" class="dropdown-menu">
                            <span id="check_none_own_book_inside" class="hover_pointer">Убрать все</span>
                            @foreach($own_book_inside_statuses as $own_book_inside_status)
                                <li class="check_own_inside_book_status">
                                    <input type="checkbox" class="own_book_inside_statuses"
                                           id="own_book_inside_status_{{$own_book_inside_status['id']}}" checked="">
                                    <label style="font-size: 18px; font-weight: 100;"
                                           for="own_book_inside_status_{{$own_book_inside_status['id']}}">
                                        {{Str::ucfirst($own_book_inside_status['status_title'])}}
                                    </label>
                                </li>
                            @endforeach
                        </ul>

                    </div>

                    <div class="mr-3 button-group">
                        <button type="button" style="font-weight: 100; font-size: 20px"
                                class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            Статус обложки <span id="own_book_cover_statuses_button_text">(6/6)</span>
                        </button>
                        <ul style="padding:10px;" class="dropdown-menu">
                            <span id="check_none_own_book_cover" class="hover_pointer">Убрать все</span>
                            @foreach($own_book_cover_statuses as $own_book_cover_status)
                                <li class="check_own_cover_book_status">
                                    <input type="checkbox" class="own_book_cover_statuses"
                                           id="own_book_cover_status_{{$own_book_cover_status['id']}}" checked="">
                                    <label style="font-size: 18px; font-weight: 100;"
                                           for="own_book_cover_status_{{$own_book_cover_status['id']}}">
                                        {{Str::ucfirst($own_book_cover_status['status_title'])}}
                                    </label>
                                </li>
                            @endforeach
                        </ul>

                    </div>

                    <button type="button" class="action_needed_inside_filter_button mr-3 btn btn-outline-danger btn-sm"><i
                            class="fa fa-book"></i> Макеты в работе
                    </button>

                    <button type="button" class="action_needed_cover_filter_button mr-3 btn btn-outline-danger btn-sm"><i
                            class="fa fa-book"></i> Обложки в работе
                    </button>


                    <span class="col-info-block d-flex align-items-center clear_filters">
                        <i class="mr-2 fa fa-times"></i> Очистить фильтры
                    </span>

                    <style>
                        .clear_filters {

                            transition: 0.1s;
                        }

                        .hover_pointer {
                            cursor: pointer;
                        }

                        .clear_filters:hover {
                            color: BLUE;
                            cursor: pointer;
                            transition: 0.1s;
                        }
                    </style>

                    {{-- Чтобы не закрывалось меню--}}
                    <script>
                        $(document).on('click', '.dropdown-menu', function (e) {
                            e.stopPropagation();
                        });
                    </script>

                </div>
            </div>
        </div><!-- /.container-fluid -->

    </div>
    <!-- /.content-header -->



    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div style="padding-bottom: 30px;">
                <style>
                    .page-link, .page-item {
                        display: flex;
                        height: 38px;
                    }
                </style>
                {{ $own_books->links() }}
            </div>
            <!-- Small boxes (Stat box) -->
            <div style="align-items: center;" class="row" id="collections_id">
                <style>
                    @media screen and (max-width: 800px) {

                        .media-card {
                            width: inherit !important;
                        }

                    }
                </style>
                {{App::setLocale('ru')}}
                @foreach($own_books as $own_book)
                    <div
                        class="own_book_block"
                        data-own_book_status_id="{{$own_book->own_book_status_id}}"
                        data-own_book_inside_status_id="{{$own_book->own_book_inside_status_id}}"
                        data-own_book_cover_status_id="{{$own_book->own_book_cover_status_id}}"
                    >
                        <!-- Widget: user widget style 2 -->
                        <div style="width:450px;" class="media-card card mb-5 mr-5 card-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div
                                class="widget-user-header bg-gradient-lightblue">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="widget-user-image">
                                            <img style="width: 100px !important; margin-right: 20px;"
                                                 src="@if($own_book->cover_3d != '') /{{$own_book->cover_3d}}@else /img/no_cover.png @endif"
                                                 alt="User Avatar">
                                        </div>
                                    </div>
                                    <div class="pl-4 col-sm-9">
                                        <h3 style="font-size: 30px;" class="ml-0 widget-user-username">
                                            <b>{{$own_book->author}}</b>: </h3>
                                        <h3 style="font-weight: 100;">{{$own_book->title}}</h3>
                                    </div>

                                </div>

                                <!-- /.widget-user-image -->

                            </div>


                            <div class="p-0 card-footer border-bottom">
                                <div style="font-size: 18px;" class="
                                @if ($own_book->own_book_status_id === 1 || $own_book->own_book_status_id === 5)
                                    bg-gradient-danger
                                @elseif ($own_book->own_book_status_id === 6)
                                    bg-gradient-indigo
                                @elseif ($own_book->own_book_status_id < 7)
                                    bg-gradient-warning
                                @else
                                    bg-gradient-success
                                @endif
                                    p-2 border-bottom
">
                                    Общий статус:&nbsp;<b>{{$own_book->status_title}}</b>
                                </div>
                                <div style="font-size: 18px;" class="
                                @if ($own_book->own_book_status_id < 3)
                                    bg-gradient-gray
                                @elseif ($own_book->own_book_inside_status_id === 1 || $own_book->own_book_inside_status_id === 3)
                                    bg-gradient-danger
                                @else
                                    bg-gradient-success
                                @endif
                                    p-2 border-bottom
">
                                    Макет:&nbsp;<b>{{$own_book->inside_status_title ?? 'Нет информации'}}</b>
                                    @if (($own_book->own_book_status_id <> 1) & $own_book->own_book_inside_status_id ?? 'Нет информации' === 1)
                                        <i style="margin-left: 15px; float: right;">(до: {{ Date::parse($own_book->inside_deadline)->addHours(3)->format('j F') }}
                                            )</i>
                                    @endif
                                </div>
                                <div style="font-size: 18px;" class="p-2
                                @if ($own_book->own_book_status_id < 3)
                                    bg-gradient-gray
                                @elseif ($own_book->own_book_cover_status_id === 1 || $own_book->own_book_cover_status_id === 3)
                                    bg-gradient-danger
                                @else
                                    bg-gradient-success
                                @endif
                                    border-bottom">
                                    Обложка:&nbsp;<b>{{$own_book->cover_status_title ?? 'Нет информации'}}</b>
                                    @if (($own_book->own_book_status_id <> 1) & $own_book->own_book_cover_status_id === 1)
                                        <i style="margin-left: 15px; float: right;">(до: {{ Date::parse($own_book->cover_deadline)->addHours(3)->format('j F') }}
                                            )</i>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                @if ($own_book->chat_status_id == 1)
                                    <span style="right: 5px; top:11px;" class="position-absolute right badge badge-danger">
                                        Вопрос в чате
                                    </span>
                                @endif
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <a style="display: inline-block;"
                                               href="{{route('collection.edit', $own_book->id)}}">Печать:</a><br>
                                            <span style="font-size:20px;"
                                                  class="badge">
                                                @if ($own_book->print_price > 0) Да
                                                @else Нет
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div
                                        class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <a style="display: inline-block;"
                                               href="{{route('collection.edit', $own_book->id)}}">Продвижение:</a> <br>
                                            <span style="font-size:20px;"
                                                  class="badge">
                                                @if ($own_book->promo_price > 0) Да
                                                @else Нет
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <a style="display: inline-block;"
                                               href="{{route('collection.edit', $own_book->id)}}">Профит</a> <br>
                                            <span style="font-size:20px;"
                                                  class="badge">{{number_format($own_book->total_price, 0, ',', ' ')}} руб.</span>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>

                                <div style="border-top: 1px solid #dee2e6!important"
                                     class="mt-2 pt-2 w-100 btn-group">
                                    <a style="    font-size: 18px;"
                                       href="{{route('own_books_page', $own_book->id)}}" type="button"
                                       class="btn btn-sm btn-outline-primary">Подробнее</a>
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                        <!-- /.widget-user -->
                    </div>
                @endforeach

                <script>
                    function collection_filter() {
                        var input, filter, cards, cardContainer, h5, title, i;
                        input = document.getElementById("collection_input");
                        filter = input.value.toUpperCase();
                        cardContainer = document.getElementById("collections_id");
                        cards = cardContainer.getElementsByClassName("card");
                        for (i = 0; i < cards.length; i++) {
                            title = cards[i].querySelector(".widget-user-username");
                            if (title.innerText.toUpperCase().indexOf(filter) > -1) {
                                cards[i].style.display = "";
                            } else {
                                cards[i].style.display = "none";
                            }
                        }
                    }
                </script>

                <script>

                    $('#check_none_own_book').on('click', function (event) {
                        $('.own_book_statuses:checkbox').prop('checked', false);
                        $('.own_book_block').hide();

                        // Скрываем макет
                        own_book_inside_statuses_hide = $(".own_book_inside_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_inside_statuses_hide, function (index, value) {
                            own_book_inside_statuses_hide_id = value.substring(23, 100);
                            $('[data-own_book_inside_status_id=' + own_book_inside_statuses_hide_id + ']').hide();
                        });

                        // Скрываем обложку
                        own_book_cover_statuses_hide = $(".own_book_cover_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_cover_statuses_hide, function (index, value) {
                            own_book_cover_statuses_hide_id = value.substring(22, 100);
                            $('[data-own_book_cover_status_id=' + own_book_cover_statuses_hide_id + ']').hide();
                        });

                    })


                    $('#check_none_own_book_inside').on('click', function (event) {
                        $('.own_book_inside_statuses:checkbox').prop('checked', false);
                        $('.own_book_block').hide();

                        // Скрываем общий статус
                        own_book_statuses_hide = $(".own_book_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_statuses_hide, function (index, value) {
                            own_book_statuses_hide_id = value.substring(16, 100);
                            $('[data-own_book_status_id=' + own_book_statuses_hide_id + ']').hide();
                        });

                        // Скрываем обложку
                        own_book_cover_statuses_hide = $(".own_book_cover_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_cover_statuses_hide, function (index, value) {
                            own_book_cover_statuses_hide_id = value.substring(22, 100);
                            $('[data-own_book_cover_status_id=' + own_book_cover_statuses_hide_id + ']').hide();
                        });

                    })


                    $('#check_none_own_book_cover').on('click', function (event) {
                        $('.own_book_cover_statuses:checkbox').prop('checked', false);
                        $('.own_book_block').hide();

                        // Скрываем общий статус
                        own_book_statuses_hide = $(".own_book_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_statuses_hide, function (index, value) {
                            own_book_statuses_hide_id = value.substring(16, 100);
                            $('[data-own_book_status_id=' + own_book_statuses_hide_id + ']').hide();
                        });

                        // Скрываем макет
                        own_book_inside_statuses_hide = $(".own_book_inside_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_inside_statuses_hide, function (index, value) {
                            own_book_inside_statuses_hide_id = value.substring(23, 100);

                            $('[data-own_book_inside_status_id=' + own_book_inside_statuses_hide_id + ']').hide();
                        });

                    })


                    $('.dropdown-menu .check_own_book_status').on('click', function (event) {

                        // Скрываем/показываем именно общий
                        own_book_statuses_hide = $(".own_book_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        own_book_statuses_show = $(".own_book_statuses:checkbox:checked").map(function (_, x) {
                            return x.id;
                        }).get();

                        own_book_statuses_checked = own_book_statuses_show.length;
                        $('#own_book_statuses_button_text').text('(' + own_book_statuses_checked + '/7)')

                        $.each(own_book_statuses_hide, function (index, value) {
                            own_book_statuses_hide_id = value.substring(16, 100);
                            $('[data-own_book_status_id=' + own_book_statuses_hide_id + ']').hide();
                        });
                        $.each(own_book_statuses_show, function (index, value) {
                            own_book_statuses_show_id = value.substring(16, 100);
                            $('[data-own_book_status_id=' + own_book_statuses_show_id + ']').show();
                        });
                        /////////////////////////////////////////////////////////////////


                        // Скрываем макет
                        own_book_inside_statuses_hide = $(".own_book_inside_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_inside_statuses_hide, function (index, value) {
                            own_book_inside_statuses_hide_id = value.substring(23, 100);
                            $('[data-own_book_inside_status_id=' + own_book_inside_statuses_hide_id + ']').hide();
                        });

                        // Скрываем обложку
                        own_book_cover_statuses_hide = $(".own_book_cover_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_cover_statuses_hide, function (index, value) {
                            own_book_cover_statuses_hide_id = value.substring(22, 100);
                            $('[data-own_book_cover_status_id=' + own_book_cover_statuses_hide_id + ']').hide();
                        });


                    });

                    $('.dropdown-menu .check_own_inside_book_status').on('click', function (event) {

                        // Скрываем/показываем именно макет
                        own_book_inside_statuses_hide = $(".own_book_inside_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        own_book_inside_statuses_show = $(".own_book_inside_statuses:checkbox:checked").map(function (_, x) {
                            return x.id;
                        }).get();

                        own_book_inside_statuses_checked = own_book_inside_statuses_show.length;
                        $('#own_book_inside_statuses_button_text').text('(' + own_book_inside_statuses_checked + '/6)')


                        $.each(own_book_inside_statuses_hide, function (index, value) {
                            own_book_inside_statuses_hide_id = value.substring(23, 100);
                            $('[data-own_book_inside_status_id=' + own_book_inside_statuses_hide_id + ']').hide();
                        });
                        $.each(own_book_inside_statuses_show, function (index, value) {
                            own_book_inside_statuses_show_id = value.substring(23, 100);
                            $('[data-own_book_inside_status_id=' + own_book_inside_statuses_show_id + ']').show();
                        });
                        /////////////////////////////////////////////////////////////////////////////////

                        // Скрываем общий статус
                        own_book_statuses_hide = $(".own_book_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_statuses_hide, function (index, value) {
                            own_book_statuses_hide_id = value.substring(16, 100);
                            $('[data-own_book_status_id=' + own_book_statuses_hide_id + ']').hide();
                        });

                        // Скрываем обложку
                        own_book_cover_statuses_hide = $(".own_book_cover_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_cover_statuses_hide, function (index, value) {
                            own_book_cover_statuses_hide_id = value.substring(22, 100);
                            $('[data-own_book_cover_status_id=' + own_book_cover_statuses_hide_id + ']').hide();
                        });


                    });


                    $('.dropdown-menu .check_own_cover_book_status').on('click', function (event) {

                        // Скрываем/показываем именно обложку
                        own_book_cover_statuses_hide = $(".own_book_cover_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        own_book_cover_statuses_show = $(".own_book_cover_statuses:checkbox:checked").map(function (_, x) {
                            return x.id;
                        }).get();

                        own_book_cover_statuses_checked = own_book_cover_statuses_show.length;
                        $('#own_book_cover_statuses_button_text').text('(' + own_book_cover_statuses_checked + '/6)')


                        $.each(own_book_cover_statuses_hide, function (index, value) {
                            own_book_cover_statuses_hide_id = value.substring(22, 100);
                            $('[data-own_book_cover_status_id=' + own_book_cover_statuses_hide_id + ']').hide();
                        });
                        $.each(own_book_cover_statuses_show, function (index, value) {
                            own_book_cover_statuses_show_id = value.substring(22, 100);
                            $('[data-own_book_cover_status_id=' + own_book_cover_statuses_show_id + ']').show();
                        });

                        // Скрываем общий статус
                        own_book_statuses_hide = $(".own_book_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_statuses_hide, function (index, value) {
                            own_book_statuses_hide_id = value.substring(16, 100);
                            $('[data-own_book_status_id=' + own_book_statuses_hide_id + ']').hide();
                        });

                        // Скрываем макет
                        own_book_inside_statuses_hide = $(".own_book_inside_statuses:checkbox:not(:checked)").map(function (_, x) {
                            return x.id;
                        }).get();
                        $.each(own_book_inside_statuses_hide, function (index, value) {
                            own_book_inside_statuses_hide_id = value.substring(23, 100);

                            $('[data-own_book_inside_status_id=' + own_book_inside_statuses_hide_id + ']').hide();
                        });

                    });
                </script>


                <script>
                    $('#own_book_status_2').trigger('click');
                    $('#own_book_status_99').trigger('click');

                    $('.action_needed_cover_filter_button').on('click', function () {
                        $('.clear_filters').trigger('click');

                        $('#check_none_own_book').trigger('click');
                        $('#own_book_status_3').trigger('click');
                        $('#own_book_status_5').trigger('click');

                        $('#check_none_own_book_cover').trigger('click');
                        $('#own_book_cover_status_1').trigger('click');
                        $('#own_book_cover_status_3').trigger('click');
                    })

                    $('.action_needed_inside_filter_button').on('click', function () {
                        $('.clear_filters').trigger('click');

                        $('#check_none_own_book').trigger('click');
                        $('#own_book_status_3').trigger('click');
                        $('#own_book_status_5').trigger('click');

                        $('#check_none_own_book_inside').trigger('click');
                        $('#own_book_inside_status_1').trigger('click');
                        $('#own_book_inside_status_3').trigger('click');
                    })

                </script>

                <script>
                    $('.clear_filters').on('click', function () {
                        $('.own_book_block').show();
                        $('input:checkbox').prop('checked', true);
                        $('#own_book_statuses_button_text').text('(7/7)')
                        $('#own_book_inside_statuses_button_text').text('(6/6)')
                        $('#own_book_cover_statuses_button_text').text('(6/6)')

                    })
                </script>


            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection
