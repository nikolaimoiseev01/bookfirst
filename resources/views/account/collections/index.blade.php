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
        <a href="{{route('actual_collections')}}" class="button">
            Актуальные сборники для участия
        </a>
    </div>
    <div class="my_collections_wrap">
        @if(count($participations) == 0)
            <h1 class="no-access">На данный момент у Вас нет сборников, в которых Вы участвуете.</h1>
        @endif
        @foreach($participations as $participation)

            <div class="collection_wrap container">
                    <img src="/{{$participation->collection['cover_2d']}}"
                         alt="">
                <div class="right_wrap">
                    <h3>{{$participation->collection['title']}}</h3>
                    <div class="info">
                        <p><b>Статус участия:</b> {{$participation->pat_status['pat_status_title']}}</p>
                        <p><b>Статус сборника:</b> {{$participation->collection->col_status['col_status']}}</p>
                    </div>
                    <a href="{{route('participation_index',['participation_id'=>$participation['id'],'collection_id'=>$participation['collection_id']])}}"
                       class="button">Страница моего участия</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
