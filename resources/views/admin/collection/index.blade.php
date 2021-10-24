@extends('layouts.admin_layout')

@section('title', 'Главная')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div style="align-items: flex-start;" class="row mb-2">
                <div class="d-flex">
                    <h1 class="m-0">Наши сборники</h1>
                </div><!-- /.col -->


                <a href="{{ route('collection.create') }}" style="width:200px;"
                   class="ml-3 btn btn-outline-secondary"><i
                        class="mr-2 fa fa-plus"></i> Новый сборник</a>
                <a href="{{route('closed_collections')}}" class="ml-3 btn btn-outline-info">Закрытые сборники</a>
            </div><!-- /.row -->
            <a href="{{ route('new_participants') }}"
               style="display: flex; align-items: center; width: fit-content; font-size: 20px;padding: 2px 30px"
               class="mb-3 mt-4 btn btn-outline-primary btn-block">
                <i class="mr-2 fa fa-bell"></i>Новые заявки: <span
                    style="font-weight: 900; margin-left:10px; font-size: 28px;">{{$new_participants}}</span></a>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (count($collections) > 9)
            <div style="padding-bottom: 30px;">
                <style>
                    .page-link, .page-item {
                        display: flex;
                        height: 38px;
                    }
                </style>
                {{ $collections->links() }}
            </div>
            @endif
            <!-- Small boxes (Stat box) -->
            <div class="row" id="collections_id">
                {{App::setLocale('ru')}}
                @foreach($collections as $collection)
                    <div>
                        <!-- Widget: user widget style 2 -->
                        <div style=" max-width:400px" class="card mb-5 mr-5 card-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div
                                class="widget-user-header
                                    @if ($collection->col_status_id == 1) bg-info
                                    @elseif ($collection->col_status_id == 2) bg-warning
                                    @elseif (($collection->col_status_id == 3)) bg-secondary
                                    @elseif (($collection->col_status_id == 9)) bg-secondary
                                    @endif">
                                <div class="widget-user-image">
                                    <img style="    margin-right: 5px; width:120px !important;" src="/{{$collection->cover_3d}}"
                                         alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 style="margin-right: 25px;"
                                    class="mb-3 widget-user-username">{{$collection->title}}</h3>
                                @if (($collection->col_status_id <> 9))
                                    Ближайший деадлайн:<br>
                                    <span style="font-size: 20px;">
                                    @if ($collection->col_date1 > date('Y-m-d')) {{ Date::parse($collection->col_date1)->format('j F Y') }}
                                        @elseif ($collection->col_date2 > date('Y-m-d')) {{ Date::parse($collection->col_date2)->format('j F Y') }}
                                        @elseif ($collection->col_date3 > date('Y-m-d')) {{ Date::parse($collection->col_date3)->format('j F Y') }}
                                        @elseif ($collection->col_date4 > date('Y-m-d')) {{ Date::parse($collection->col_date4)->format('j F Y') }}
                                        @endif
                                        </span>
                                @endif
                            </div>
                            <div class="ribbon-wrapper ribbon-lg">
                                <div
                                    class="ribbon
                                    @if ($collection->col_status_id == 1) bg-primary
                                    @elseif ($collection->col_status_id == 2) bg-warning
                                    @elseif ($collection->col_status_id == 3) bg-success
                                    @elseif ($collection->col_status_id == 9) bg-success
                                    @endif">
                                    {{$collection->col_status}}
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <a href="{{route('collection.edit', $collection->id)}}">Страниц</a><br>
                                                <span style="font-size:20px;"
                                                      class="badge">{{\App\Models\Participation::where([['collection_id',$collection->id],['pat_status_id',3]])->sum('pages')}}</span>
                                            </div>
                                        </div>
                                <!-- /.col -->
                                    <div
                                        class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <a href="{{route('collection.edit', $collection->id)}}">Участников</a> <br>
                                            <span style="font-size:20px;"
                                                  class="badge">{{$collection->total_participants}}</span>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <a href="{{route('collection.edit', $collection->id)}}">Профит</a> <br>
                                            <span style="font-size:20px;"
                                                  class="badge">{{\App\Models\Participation::where([['collection_id',$collection->id],['pat_status_id',3]])->sum('total_price')}}</span>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>

                                    <div style="border-top: 1px solid #dee2e6!important"
                                         class="mt-2 pt-2 w-100 btn-group">
                                        <a style="    font-size: 18px;"
                                           href="{{route('collection.edit', $collection->id)}}" type="button"
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

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection
