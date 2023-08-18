<div class="pay_block_wrap part" id="payment_block">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}

    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>{{$page_title}}</h2>
        </div>

        <div class="info_wrap">

            <div class="need_to_pay_wrap">
                @if ($own_book['own_book_status_id'] === 1)
                    <p class="no-access">
                        После создания или редактирования заявки нам необходимо ее подтвердить.
                        После подтверждения (до 3-х рабочих дней) оплата станет доступна.
                    </p>
                @else
                    @if ($own_book['own_book_status_id'] === 2)
                        <div class="pay_info_wrap">
                            <p>После создания или редактирования заявки нам необходимо ее подтвердить.
                                После подтверждения (до 3-х рабочих дней) оплата станет доступна.</p>
                            <form
                                action="{{ route('payment.create_own_book_payment',[$own_book['id'], 'Without_Print', $own_book['total_price'] - $own_book['print_price']]) }}"
                                method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input value="{{$own_book['id']}}" type="text" name="own_book_status_id"
                                       style="display:none" class="form-control"
                                       id="own_book_status_id">
                                <button id="btn-submit" type="submit" style="height: fit-content; max-width:250px;"
                                        class="pay-button button">
                                    Оплатить {{$own_book['total_price'] - $own_book['print_price']}} руб.
                                </button>
                            </form>

                        </div>
                    @endif
                    <div class="prices_wrap @if ($own_book['own_book_status_id'] > 2) pay_success_wrap @endif">
                        <div class="price_wrap">
                            <div class="price">
                                <h1>{{$own_book['inside_price']}} руб.</h1>
                                <p class="desc">
                                    работа с макетом
                                </p>
                            </div>
                        </div>

                        @if($own_book['cover_price'] > 0)
                            <div class="price_wrap">
                                <h2 class="plus">+</h2>
                                <div class="price">
                                    <h1>{{$own_book['cover_price']}} руб.</h1>
                                    <p class="desc">
                                        Подготовка обложки
                                    </p>
                                </div>
                            </div>
                        @endif


                        @if($own_book['promo_price'] > 0)
                            <div class="price_wrap">
                                <h2 class="plus">+</h2>
                                <div class="price">
                                    <h1>{{$own_book['promo_price']}} руб.</h1>
                                    <p class="desc">
                                        Продвижение
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="price_wrap print_price">
                            <h2 class="plus">+</h2>
                            <div class="price">
                                @if($own_book['print_price'] > 0)
                                    <h1>{{$own_book['print_price']}} руб.</h1>
                                    <p class="desc">
                                        За печать ({{$own_book->printorder['books_needed']}} экз.)
                                    </p>
                                @else
                                    <h1>0 руб.</h1>
                                    <p class="desc">
                                        Без печатных экз.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="price_wrap total_price">
                            <h2 class="plus">=</h2>
                            <div class="price">
                                <h1>{{$own_book['total_price'] - $own_book['print_price']}} руб.</h1>
                                <p class="desc">
                                    Итого*
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="pay_parts_text">*На данном этапе оплата производится за все услуги, кроме печати, так как цена
                        печати может измениться после утверждения макетов.</p>
                @endif
            </div>
        </div>
    </div>

</div>
