@extends('layouts.app')

@section('page-style')

@endsection


@section('page-tab-title')
    Мои книги
@endsection

@section('content')

    <div class="account-header">
        <h1>Собственные книги</h1>
        <div class="buttons_wrap">
            <a class="button" href="{{route('own_book_create')}}">Издать новую книгу</a>
            <a class="link" href="{{route('own_book_page')}}">
                Рассчитать стоимость издания и печати
            </a>
        </div>

    </div>

    <div class="my_ownbooks_wrap my_collections_wrap">

        @if(count($own_books) == 0)
            <h1 class="no-access">На данный момент у Вас нет собственных книг в системе.</h1>
        @endif


        @foreach($own_books as $own_book)

            <div class="collection_wrap own_book_wrap container">

                <div class="img_info_wrap">
                    @if($own_book->cover_2d != '')
                        <img src="/{{$own_book->cover_2d}}"
                             alt="">
                    @else
                        <img src="/img/no_cover_2d.png"
                             alt="">
                    @endif
                    <div class="right_wrap">
                        <h3>{{Str::limit($own_book['title'], 18, '...')}}</h3>
                        <div class="info">
                            <p><b>Общий статус:</b> {{$own_book->own_book_status['status_title']}}
                            </p>
                            <p><b>Статус обложки:</b> {{$own_book->own_book_cover_status['status_title']}}</p>
                            <p><b>Статус ВБ:</b> {{$own_book->own_book_inside_status['status_title']}}</p>
                        </div>
                    </div>
                </div>
                <a href="{{route('book_page', $own_book['id'])}}"
                   class=" button">Страница издания</a>

            </div>
        @endforeach
    </div>

@endsection
