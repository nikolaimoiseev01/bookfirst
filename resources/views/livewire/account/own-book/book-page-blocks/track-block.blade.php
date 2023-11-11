<div class="part track_block_wrap" id="print_part">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}

    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>Печать книги</h2>
        </div>

        <div class="info_wrap">
            @if($own_book->printorder ?? null) {{-- Если есть печать --}}

            @if ($own_book['own_book_status_id'] < 4) {{-- Если еще работаем с макетами --}}
            <p class="no-access">
                В вашем издании включены печатные экземпляры, но процесс оплаты и печати будет доступен только после
                утверждения внутреннего блока и обложки.
            </p>
            @elseif ($own_book['own_book_status_id'] === 4) {{-- Если закончили работу с макетами --}}
            <p class="no-access">
                Файлы утверждены! У вас есть заказ печати. Его нужно оплатить:
            </p>
            <form
                action="{{ route('payment.create_own_book_payment',[$own_book['id'], 'Print_only', $own_book['print_price']]) }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                <button type="submit"
                        class="yellow button">
                    Оплатить {{$own_book['print_price']}} руб.
                </button>
                <a href="#general_info_wrap" class="link yellow">Подробнее о заказе</a>
            </form>

            @elseif ($own_book['own_book_status_id'] === 5) {{-- Если уже была оплата, ждем в печать --}}
            <p class="no-access">
                Печать успешно оплачена! Ожидаем подтверждение от типографии (1-3 дня).
            </p>
            @elseif ($own_book['own_book_status_id'] === 6) {{-- Если идет печать --}}
            <p class="no-access">
                Оплата успешно подтверждена! Прямо сейчас идет печать книги. Обычно она занимает 11-13 рабочих дней. Как только экземпляры будут
                отправлены, Вы получите уведомление по Email.
                Ссылка/номер для отслеживания будут сразу доступны в этом блоке.
            </p>

            {{-- Если печать завершена, но не оплачена пересылка --}}
            @elseif ($own_book['own_book_status_id'] === 9 && $own_book->printorder['paid_at'] === null)
                <p>
                    Поздравляем! Весь заказ печатных экземпляров был успешно отправлен!
                    Для того, чтобы забрать посылку, нужно произвести оплату за отправление.
                    По нашим правилам оплата происходит именно в этот момент,
                    так как точную стоимость пересылки мы фиксируем только после окончания печати.
                    <br>
                    <b>
                        Сейчас мы заблокировали возможность получения.
                        Если оплата не будет произведена
                        до {{ Date::parse($own_book['updated_at'])->addDays(3)->format('j F') }}, нам
                        придется окончательно отменить получение и отправить заказ обратно.
                    </b>
                </p>

                <p>
                    Стоимость отправления: {{$own_book->printorder['send_price']}} руб.
                </p>

                <form
                    action="{{ route('payment.create_send_payment', [$own_book->printorder['id'], $own_book->printorder['send_price']])}}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <button type="submit"
                            class="yellow button">
                        Оплатить пересылку
                    </button>
                </form>

            @else {{-- Если все завершено, была печать, и пересылка оплачена --}}
            <p>
                Поздравляем! Издание и печать книги завершены!
                Вы всегда можете отследить посылку по ссылке ниже или по
                трек-номеру {{$own_book->Printorder['track_number']}} вручную на сайте Почты России.

            </p>
            <a target="_blank" class="button"
               href="https://www.pochta.ru/tracking#{{$own_book->Printorder['track_number']}}">
                Отследить посылку</a>

            @endif

            @else {{-- Если нет печатных экземпляров --}}
            @if ($own_book['own_book_status_id'] <= 9) {{-- Если еще не завершили работу --}}
            <p class="no-access">
                У Вас нет заказа печатных экземпляров. Вы сможете заказать печать, но процесс печати будет доступен
                только после утверждения макетов.
                <a id="create_form" class="link">Создать заказ</a>
            </p>

            @endif
            @endif
        </div>
    </div>
</div>
