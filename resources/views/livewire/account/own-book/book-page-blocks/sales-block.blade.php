<div class="part sales_block_wrap" id="print_part">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}

    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>Продажи книги</h2>
        </div>

        <div class="info_wrap">
            @if ($own_book['own_book_status_id'] < 9)
                <p class="no-access">
                    Книга еще издана не полностью. Как только процесс будет завершен, в этом блоке будет информация о
                    продажах книги, а также возможность вывести средства.
                </p>
            @elseif ($own_book['own_book_status_id'] === 9)
                @if(count($digital_sales) == 0)
                    <p class="no-access">Пока еще не было продаж книги на нашем сайте. При каждой продаже вы будете
                        получать
                        уведомление по Email, а здесь будет отражаться вся информация, в том числе
                        инструкция по выведению средств.</p>
                @else
                    <p>Количество продаж вашей книги на нашем сайте: <b>{{count($digital_sales)}} </b>на сумму
                        <b>{{$digital_sales->sum('price')}} руб.</b></p>
                    <a href="{{route('chat_create', 'Запрос на вывод ' . $digital_sales->sum('price') . ' руб. за книгу "' . $own_book['title'] . '"')}}"
                       class="button">Вывести {{$digital_sales->sum('price')}} руб.</a>
                @endif
            @endif
        </div>
    </div>
</div>
