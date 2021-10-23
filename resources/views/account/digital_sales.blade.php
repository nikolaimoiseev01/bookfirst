@extends('layouts.app')
@section('page-style')
    <style>
        .digital_sales_wrap .container {
            padding: 10px 20px;
            /*flex-direction: column;*/
            margin: 0 20px 20px 0;
            max-width: 400px;
            text-align: center;
        }

        .digital_sales_wrap .container img {
            border-radius: 15px;
            max-width: 130px;
            margin: auto;
        }

        .digital_sales_wrap .container h2 {
            font-size: 28px;
            margin: 0;
        }

        .digital_sales_wrap .container div {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            margin-left: 10px;
        }

        .digital_sales_wrap .container div a {
            box-shadow: none;
        }

        .digital_sales_wrap {
            display: flex;
            width: 100%;
            flex-wrap: wrap;
        }

        .digital_sales_wrap .no-books-yet a {
            margin: 0 20px 10px 0;
        }


    </style>
@endsection
@section('page-title')
    <div class="account-header">
        <h1>Мои покупки</h1>
    </div>
    <div class="digital_sales_wrap">
        @if(count($digital_sales) > 0)
            @foreach($digital_sales as $digital_sale)
                @if ($digital_sale->Collection['title'] ?? 0 <> 0)
                    <div class="container">
                        <img src="/{{$digital_sale->Collection['cover_3d']}}" alt="">
                        <div style="display: flex">
                            <h2>{{$digital_sale->Collection['title']}}</h2>
                            <a download href="/{{$digital_sale->Collection['pre_var']}}" class="button">Скачать</a>
                        </div>
                    </div>
                @else
                    <div class="container">
                        <img src="/{{$digital_sale->own_book['cover_3d']}}" alt="">
                        <div style="display: flex">
                            <h2>{{$digital_sale->own_book['title']}}</h2>
                            <a download href="/{{$digital_sale->own_book['inside_file']}}" class="button">Скачать</a>
                        </div>
                    </div>
                @endif
            @endforeach

        @else
            <div style="max-width: 2000px;" class="no-books-yet">
                <h1> Вы еще не покупали электронные версии наших книг</h1>
                <a href="{{route('old_collections')}}" class="button">Сборники для покупки</a>
                <a href="{{route('own_books_portal')}}" class="button">Книги наших авторов</a>
            </div>
        @endif

    </div>
@endsection
@section('content')
@endsection
