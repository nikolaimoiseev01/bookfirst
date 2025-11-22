@extends('layouts.app')
@section('content')
    <div class="dig_sales_account_page_wrap">
        <div class="account-header">
            <h1>Мои покупки</h1>
            <div x-data="{show_input: false}" class="wallet_wrap">
                <p>Баланс: {{$user_wallet['cur_amount']}} руб.</p>
                <form x-show="show_input"
                      action="{{ route('payment.create_points_payment')}}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="input_points_form">
                    @csrf
                    <input value="{{route('my_digital_sales')}}"
                           type="text" name="url_redirect"
                           style="display:none;"
                           id="url_redirect">
                    <input placeholder="сумма" type="number"
                           name="amount"
                           class="form-control"
                           required
                           id="amount">

                    <button type="submit"
                            class="button">
                        К оплате
                    </button>
                    <a @click="show_input = false" x-show="show_input" class="link">Отменить</a>
                </form>
                <a @click="show_input = true" x-show="!show_input" type="submit"
                   class="link">
                    Пополнить
                </a>
            </div>
        </div>

        <div class="digital_sales_wrap">
            @if(count($digital_sales) > 0)
                @foreach($digital_sales as $digital_sale)
                    @if ($digital_sale->Collection['title'] ?? 0 <> 0)
                        <div class="sale_wrap container">
                            <img src="/{{$digital_sale->Collection['cover_3d']}}" alt="">
                            <div class="info">
                                <h4>{{$digital_sale->Collection['title']}}</h4>
                                <a download href="/{{$digital_sale->Collection['pre_var']}}" class="button">Скачать</a>
                            </div>
                        </div>
                    @else
                        <div class="sale_wrap container">
                            <img src="/{{$digital_sale->own_book['cover_3d']}}" alt="">
                            <div class="info">
                                <h4>{{$digital_sale->own_book['title']}}</h4>
                                <a download href="/{{$digital_sale->own_book['inside_file']}}"
                                   class="button">Скачать</a>
                            </div>
                        </div>
                    @endif
                @endforeach

            @else
                <h1 class="no-access"> Вы еще не покупали электронные версии наших книг</h1>
            @endif

        </div>

        <div class="buttons_to_buy_wrap">
            <a href="{{route('old_collections')}}" class="button">Сборники для покупки</a>
            <a href="{{route('own_books_portal')}}" class="button">Книги наших авторов</a>
        </div>

    </div>
@endsection
@push('page-js')

@endpush
