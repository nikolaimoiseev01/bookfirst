@extends('layouts.app')

@section('page-style')
    <link rel="stylesheet" href="/css/participation-index.css">

@endsection

@section('page-tab-title')
    Продвижение аккаунта
@endsection


@section('page-title')

@endsection

@section('content')
    <div class="account-header">
        <h1>Мои активные продвижения</h1>
        <div class="buttons_wrap">
            <a href="{{route('make_ext_promotion')}}" class="button">
                Подать заявку
            </a>
            <a href="{{route('ext_promotion')}}" class="button">
                Подробнее
            </a>
        </div>

    </div>
    <div class="my_collections_wrap">
        @if(count($ext_promotions) == 0)
            <h1 class="no-access">На данный момент у Вас нет активных заявок на продвижение.</h1>
        @endif
        @foreach($ext_promotions as $ext_promotion)

            <div class="collection_wrap container">
                <div class="right_wrap">
                    <h3>Продвижение на сайте {{$ext_promotion['site']}}</h3>
                    <div class="info">
                        <p><b>Создано:</b> {{Date::parse($ext_promotion['paid_at'])->format('j F H:i')}}</p>
                        <p><b>Статус:</b> {{$ext_promotion->ext_promotion_status['title']}}</p>
                    </div>
                    <a href="{{route('index_ext_promotion', $ext_promotion['id'])}}"
                       class="button">Страница продвижения</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
