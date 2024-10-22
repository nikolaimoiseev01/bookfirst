<div class="part track_block_wrap" id="print_part">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}

    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>Отслеживание сборника</h2>
        </div>

        <div class="info_wrap">
            @if($participation->collection['col_status_id'] >= 2 && !($participation['paid_at'] ?? null))
                {{-- Если сборник уже пошел издаваться --}}
                <p class="no-access">
                    К сожалению, Вы пропустили срок оплаты участия, из-за чего не были включены в сборник.
                </p>
            @elseif(!($participation->printorder ?? null))
                {{-- Если не было заказа--}}
                <div class="no-access">
                    Вы не создавали заказ печатных экземпляров.
                </div>
            @elseif($collection['col_status_id'] === 3)
                {{-- Если сборник начал печататься --}}
                <p class="no-access">
                    <span class="green">Сборник успешно ушел в печать!</span>
                    Отслеживание станет доступно после отправки печатных экземпляров авторам:
                    {{ Date::parse($collection['col_date4'])->format('j F') }}
                </p>
            @elseif($collection['col_status_id'] < 9)
                {{-- Если был заказ, но сборник еще не конец--}}
                <span class="no-access">
                    Отслеживание станет доступно после отправки печатных экземпляров авторам:
                    {{ Date::parse($collection['col_date4'])->format('j F') }}
             </span>
                {{--            @elseif (!($participation->printorder['paid_at'] ?? null)) --}}{{-- Если еще не оплачен--}}
                {{--            <div class="no-access">--}}
                {{--                <p>Сборник успешно отправлен всем авторам! Для того, чтобы получить посылку, нужно--}}
                {{--                    произвести оплату за отправление.--}}
                {{--                    По нашим правилам оплата происходит именно в этот момент,--}}
                {{--                    так как точную стоимость пересылки мы фиксируем только после окончания печати.--}}
                {{--                    <br><b>Если оплата не будет произведена--}}
                {{--                        до {{ Date::parse($collection['updated_at'])->addDays(3)->format('j F') }},--}}
                {{--                        нам придется заблокировать возможность получения!</b>--}}
                {{--                </p>--}}
                {{--                @if ($participation->printorder['send_price']) --}}{{-- Если с отправкой все окей --}}

                {{--                <p>Стоимость именно вашего отправления:--}}
                {{--                    {{$participation->printorder['send_price'] ?? 0}} руб.--}}
                {{--                </p>--}}

                {{--                <form--}}
                {{--                    action="{{ route('payment.create_send_payment', [$participation->printorder['id'] ?? null, $participation->printorder['send_price'] ?? 0])}}"--}}
                {{--                    method="POST"--}}
                {{--                    enctype="multipart/form-data">--}}
                {{--                    @csrf--}}
                {{--                    <input value="{{$participation['id']}}" type="text" name="pat_id"--}}
                {{--                           style="display:none" class="form-control"--}}
                {{--                           id="pat_id">--}}

                {{--                    <button id="btn-submit" type="submit"--}}
                {{--                            class="show_preloader_on_click pay-button {{$status_color}} button">--}}
                {{--                        Оплатить отправление--}}
                {{--                    </button>--}}
                {{--                </form>--}}
                {{--                @else --}}{{-- Если с отправкой что-то не так --}}
                {{--                <p><b>Стоимость не найдена!</b></p> <a--}}
                {{--                    href="{{route('chat_create', 'У меня проблема с пересылкой (' . $collection['title'] . ')')}}"--}}
                {{--                    class="link ">У меня проблема с пересылкой</a>--}}
                {{--                @endif--}}
                {{--            </div>--}}
                {{--            @else --}}{{-- Если уже отправили --}}
                {{--            <div class="no-access">--}}
                {{--                <p>Сборник успешно отправлен всем авторам! Вы оплатили пересылку, поэтому можете--}}
                {{--                    отследить ее по--}}
                {{--                    номеру: {{$participation->printorder['track_number'] ?? "ссылка не найдена"}}.</p>--}}
                {{--                <a target="_blank"--}}
                {{--                   href="https://www.pochta.ru/tracking#{{$participation->printorder['track_number'] ?? null ?? "ссылка не найдена"}}"--}}
                {{--                   class="@if ($participation->printorder['track_number'] ?? 0 <> 0) @else amazon_link_error @endif button">Отследить</a>--}}
                {{--                <a href="{{route('chat_create', 'У меня проблема с пересылкой')}}" class="link">У--}}
                {{--                    меня--}}
                {{--                    проблема с пересылкой</a>--}}
                {{--            </div>--}}
            @else
                {{-- Если уже отправили --}}
                <div class="no-access">
                    <p>Сборник успешно отправлен всем авторам! Вы можете
                        отследить свой заказ по
                        номеру: {{$participation->printorder['track_number'] ?? "ссылка не найдена"}}.<br>
                        По нашим правилам <b>стоимость пересылки</b> оплачивается отдельно на финальном этапе
                        (только сейчас мы понимаем точный вес посылки и можем расчитать стоимость).
                        Она должна быть оплачена в виде наложенного платежа при получении посылки (он четко равен
                        стоимости отправления).
                    </p>
                    @if($participation['collection_id'] == 117 || $participation['collection_id'] == 118  ||
                        $participation['collection_id'] == 119  || $participation['collection_id'] == 120 )
                        <p><i style="color: #47AF98">Мы приносим наши искренние извинения за такую большую задержку с
                                отправкой сборников.
                                Мы понимаем, что это наша слабая сторона, и на следующие выпуски мы сильно
                                модернезировали систему подачи заявки,
                                чтобы с отправкой в последствие не возникало никаких проблем.
                                В качестве компенсации за сложившуюся ситуацию мы хотим подарить вам скидку в 30% на
                                участие в следующем выпуске: промокод ALMOST_30.
                            </i></p>
                        <a target="_blank"
                           href="https://www.pochta.ru/tracking#{{$participation->printorder['track_number'] ?? null ?? "ссылка не найдена"}}"
                           class="@if ($participation->printorder['track_number'] ?? 0 <> 0) @else amazon_link_error @endif button">Отследить</a>
                        <a href="{{route('chat_create', 'У меня проблема с пересылкой')}}" class="link">У
                            меня
                            проблема с пересылкой</a>
                </div>
            @endif
        </div>
    </div>
</div>
