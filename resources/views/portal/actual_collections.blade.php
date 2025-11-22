@extends('layouts.portal_layout')

@section('page-title') Актуальные сборники @endsection

@section('content')
    {{App::setLocale('ru')}}

    <div class="page_content_wrap actual_cols_page_wrap">

        <div class="header_wrap">
            <h2>Актуальные</h2></a>
            <a href="{{route('old_collections')}}">
                <h2>Выпущенные</h2>
            </a>
        </div>

        <div class="actual-block">
            <div class="collections_wrap">
                @foreach($collections as $collection)
                    <div class="container collection_wrap" id="collection_{{$collection['id']}}">
                        <div class="label_wrap">
                            <div class="label">
                                <div>Заявки до:</div>
                                <div>{{ Date::parse($collection['col_date1'])->format('j F') }}</div>
                            </div>
                        </div>
                        <div class="cover_wrap">
                            <img src="{{config('app.url') . '/' . $collection['cover_3d']}}" alt="">
                        </div>
                        <div class="info_wrap">
                            <h3> {{$collection['title']}}</h3>
                            <p> {{$collection['col_desc']}}</p>
                        </div>
                        <div class="buttons_wrap">
                            <a href="{{route('collection_page',$collection['id'])}}" class="button">Подробнее</a>
                            <a href="{{route('participation_create',$collection['id'])}}" class="log_check button">Принять
                                участие</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{$collections->links()}}
    </div>

@endsection
