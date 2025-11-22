<div class="pay_block_wrap part" id="payment_block">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}

    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>{{$page_title}}</h2>
        </div>

        <div class="info_wrap">
            @if ($ext_promotion['ext_promotion_status_id'] === 1)
                <p class="no-access">
                    После создания или редактирования заявки нам необходимо ее подтвердить (до 3-х рабочих дней).
                    Оплата станет доступна сразу после подтверждения Вашей заявки.
                </p>
            @elseif ($ext_promotion['ext_promotion_status_id'] === 2)
                <div class="need_to_pay_wrap">
                    <div class="pay_info_wrap">
                        <p>Отлично, Ваша заявка подтверждена! Для начала продвижения необходимо
                            произвести
                            оплату.</p>
                        <form
                            action="{{route('payment.create_ext_promotion_payment', [$ext_promotion['id'], $ext_promotion['price_total']])}}"
                            method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input value="{{$ext_promotion['id']}}" type="text" name="ext_promotion"
                                   style="display:none" class="form-control"
                                   id="ext_promotion">

                            <div class="buttons_wrap">
                                <button id="btn-submit" type="submit"
                                        style="height: fit-content; max-width:250px;"
                                        class="pay-button button">
                                    Оплатить {{$ext_promotion['price_total']}} руб.
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

                        <div class="price_wrap total_price">
                            <div class="price">
                                <h1>{{$ext_promotion['price_total']}} руб.</h1>
                                <p class="desc">
                                    Итого
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
           @elseif ($ext_promotion['ext_promotion_status_id'] == 99) {{-- Ожидание автора в чате --}}
                <p class="no-access">
                    Сейчас продвижение находится "на паузе". Мы задали вопрос в чате (блок наверху этой страницы) и готовы продолжить сразу после вашего ответа.
                </p>
            @elseif ($ext_promotion['ext_promotion_status_id'] == 999) {{-- Неактуальна --}}
            <p class="no-access">
                С заявкой что-то пошло не так и сейчас у нее статус - неактуально.
            </p>
            @else

                <div class="prices_wrap pay_success_wrap">
                    <div class="price_wrap total_price">
                        <div class="price">
                            <h1>{{$ext_promotion['price_total']}} руб.</h1>
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
