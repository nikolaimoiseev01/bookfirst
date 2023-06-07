@extends('layouts.app')

@section('page-style')

@endsection


@section('page-tab-title')
    Мои книги
@endsection

@section('content')

    <div class="account-header">
        <h1>Собственные книги</h1>
        <a style="box-shadow: none;" class="fast-load button" href="{{route('own_book_create')}}">Издать новую книгу</a>
        <a style="box-shadow: none;" class="link" href="{{route('own_book_page')}}">Расчитать стоимость издания и
            печати</a>
    </div>

    <div class="my-own-books">

        @if(count($own_books) == 0)
            <div class="no-books-yet">
                <h1>На данный момент у Вас нет собственных книг в системе</h1>
            </div>
        @endif

        @foreach($own_books as $own_book)

            <div style="flex-wrap: wrap;" class="container">
                <div class="img-wrap">
                    @if($own_book->cover_2d != '')
                        <img style="border: 1px #bebebe solid; border-radius: 9px; width: 100px !important; margin-right: 20px;"
                             src="/{{$own_book->cover_2d}}"
                             alt="">
                    @else
                        <img style="border:1px #bebebe solid; border-radius: 9px; width: 100px !important; margin-right: 20px;"
                             src="/img/no_cover_2d.png"
                             alt="">
                    @endif


                </div>
                <div class="right-wrap">
                    <h3>{{Str::limit($own_book['title'], 18, '...')}}</h3>
                    <div style="align-items: flex-start;" class="info">
                        <p style="margin-bottom:0;"><b>Общий статус:</b> {{$own_book->own_book_status['status_title']}}
                        </p>
                        <p style="margin-bottom:0;"><b>Статус
                                обложки:</b> {{$own_book->own_book_cover_status['status_title']}}</p>
                        <p style="margin-bottom:5px;"><b>Статус
                                ВБ:</b> {{$own_book->own_book_inside_status['status_title']}}</p>
                    </div>
                </div>
                <div style="flex-basis: 100%; width: 1px;" class="book-links">
                    <a href="{{route('book_page', $own_book['id'])}}"
                       style="width: 100%; text-align: center; margin-left: 0;" href=""
                       class="fast-load button">Страница издания</a>
                </div>
            </div>
        @endforeach
    </div>

@endsection
