<div class="pay_block_wrap part" id="payment_block">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}

    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>{{$page_title}}</h2>
        </div>

        <div class="info_wrap">
            @if($participation->collection['col_status_id'] >= 2 && !($participation['paid_at'] ?? null)) {{-- Если сборник уже пошел издаваться --}}
            <p class="no-access">
                К сожалению, Вы пропустили срок оплаты участия, из-чего не были включены в сборник. Мы с радостью готовы
                включить Вас в другие выпуски сборников!
                <a href="{{route('actual_collections')}}" class="button">Актуальные сборники</a>
            </p>
            @elseif ($participation['pat_status_id'] === 1)
                <p class="no-access">
                    После создания или редактирования заявки нам необходимо ее подтвердить (до 3-х рабочих дней).
                    Оплата станет доступна сразу после подтверждения Вашей заявки.
                </p>
            @elseif ($participation['pat_status_id'] === 2)
                <div class="need_to_pay_wrap">
                    <div class="pay_info_wrap">
                        <p>Отлично, Ваша заявка подтверждена! Для включения Вас в сборник необходимо
                            произвести
                            оплату.</p>
                        <form
                            action="{{ route('payment.create_part_payment', [$participation['id'], $participation['total_price']  - $already_paid_amount])}}"
                            method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input value="{{$participation['id']}}" type="text" name="pat_id"
                                   style="display:none" class="form-control"
                                   id="pat_id">

                            <div class="buttons_wrap">
                                <button id="btn-submit" type="submit"
                                        style="height: fit-content; max-width:250px;"
                                        class="pay-button button">
                                    Оплатить {{$participation['total_price']  - $already_paid_amount}} руб.
                                </button>

                                <a class="foreign_pay link yellow">Для переводов из-за границы</a>
                            </div>

                            @push('page-js')
                                <script>
                                    $(".foreign_pay").on('click', function (event) {
                                        event.preventDefault();
                                        Swal.fire({
                                        html: '<p>Если вы не можете оплатить через автоматическую форму, например, по причине наличия только заграничного счета, можно сделать прямой перевод по любым реквизитам из списка по ссылке ниже (в том числе есть счета в иностранных банках). Как только перевод будет сделан, пожалуйста, напишите, нам об этом в чате сверху страницы. Тогда мы вручную сменим статус участия.</p><div class="buttons_wrap"><a class="button" download href="/admin_files/Реквизиты Первая Книга.pdf">Реквизиты</a></div>',
                                        // icon: 'info',
                                        showConfirmButton: false,
                                        })
                                    });
                                </script>
                            @endpush
                        </form>

                    </div>
                    <div class="prices_wrap">
                        <div class="price_wrap">
                            <div class="price">
                                <h1>{{$participation['part_price']}} руб.</h1>
                                <p class="desc">
                                    За участие ({{$participation['pages']}} стр.)
                                </p>
                            </div>
                        </div>

                        @if($participation['check_price'] > 0)
                            <div class="price_wrap">
                                <h2 class="plus">+</h2>
                                <div class="price">
                                    <h1>{{$participation['check_price']}} руб.</h1>
                                    <p class="desc">
                                        Проверка текста
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="price_wrap">
                            <h2 class="plus">+</h2>
                            <div class="price">
                                @if($participation['print_price'] > 0)
                                    <h1>{{$participation['print_price']}} руб.</h1>
                                    <p class="desc">
                                        За печать ({{$participation->printorder['books_needed']}} экз.)
                                    </p>
                                @else
                                    <h1>0 руб.</h1>
                                    <p class="desc">
                                        Без печатных экз.
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($already_paid_amount ?? 0 > 0)
                            <div class="price_wrap">
                                <h2 class="plus">-</h2>
                                <div class="price">

                                    <h1>{{$already_paid_amount}} руб.</h1>
                                    <p class="desc">
                                        Уже оплачено
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="price_wrap total_price">
                            <h2 class="plus">=</h2>
                            <div class="price">
                                <h1>{{$participation['total_price'] - $already_paid_amount}} руб.</h1>
                                <p class="desc">
                                    Итого
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            @else

                <div class="prices_wrap pay_success_wrap">
                    <div class="price_wrap">
                        <div class="price">
                            <h1>{{$participation['part_price']}} руб.</h1>
                            <p class="desc">
                                За участие ({{$participation['pages']}} стр.)
                            </p>
                        </div>
                    </div>

                    <div class="price_wrap">
                        <h2 class="plus">+</h2>
                        <div class="price">
                            @if($participation['print_price'] > 0)
                                <h1>{{$participation['print_price']}} руб.</h1>
                                <p class="desc">
                                    За печать ({{$participation->printorder['books_needed']}} экз.)
                                </p>
                            @else
                                <h1>0 руб.</h1>
                                <p class="desc">
                                    Без печатных экз.
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($participation['check_price'] > 0)
                        <div class="price_wrap">
                            <h2 class="plus">+</h2>
                            <div class="price">

                                <h1>{{$participation['check_price']}} руб.</h1>
                                <p class="desc">
                                    За проверку
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="price_wrap total_price">
                        <h2 class="plus">=</h2>
                        <div class="price">
                            <h1>{{$participation['total_price']}} руб.</h1>
                            <p class="desc">
                                Итого
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
