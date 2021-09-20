@extends('layouts.app')

@section('page-style')
    <link rel="stylesheet" href="/css/participation-index.css">

@endsection

@section('page-tab-title')
    Мои сборники
@endsection


@section('page-title')

@endsection

@section('content')
    <div class="account-header">
        <h1>Участие в сборниках</h1>

    </div>
    <div class="my-collections">
        @if(count($participations) == 0)
            <div class="no-books-yet">
                <h1>На данный момент у Вас нет сборников, в которых Вы учавствуете.</h1>
                <a style="box-shadow: none;" href="{{route('actual_collections')}}" class="button">Актуальные сборники для участия</a>
            </div>
        @endif
        @foreach($participations as $participation)

            <div class="container">
                <div class="img-wrap">
                    <img style="border-radius: 9px;" width="200px" src="/{{$participation->collection['cover_2d']}}"
                         alt="">
                </div>
                <div class="right-wrap">
                    <h3>{{$participation->collection['title']}}</h3>
                    <div class="info">
                        <p style="margin-bottom:0;"><b>Статус участия:</b> {{$participation->pat_status['pat_status_title']}}</p>
                        <p><b>Статус сборника:</b> {{$participation->collection->col_status['col_status']}}</p>
                        <div class="book-links">
                            <a style="text-align: center; width: 100%;" href="{{route('participation_index',['participation_id'=>$participation['id'],'collection_id'=>$participation['collection_id']])}}"
                               class="fast-load button">Страница моего участия</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
