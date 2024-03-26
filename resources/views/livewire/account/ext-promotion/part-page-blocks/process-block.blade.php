<div class="part process_block_wrap track_block_wrap" id="print_part">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}

    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>Процесс продвижения</h2>
        </div>

        <div class="info_wrap">
            @if($ext_promotion['ext_promotion_status_id'] == 1 )
                <p class="no-access">
                    После создания или редактирования заявки нам необходимо ее подтвердить (до 3-х рабочих дней).
                    Оплата станет доступна сразу после подтверждения Вашей заявки.
                </p>
            @elseif($ext_promotion['ext_promotion_status_id'] == 2)
                <div class="no-access">
                    Заявка успешно прошла проверку! Чтобы начать продвижение, необходимо произвести оплату в блоке выше.
                    Сразу после оплаты здесь будет отображаться актуальный процесс продвижения.
                </div>
            @elseif($ext_promotion['ext_promotion_status_id'] == 3)
                <p class="no-access">
                    <span class="green">Мы успешно приняли оплату!</span>
                    Сейчас нам потребуется немного времени, чтобы продвижение началось (1-2 дня). Как только оно
                    начнется, здесь будет отображаться актуальный процесс продвижения.
                </p>
            @elseif($ext_promotion['ext_promotion_status_id'] == 4)
                <b><p class="green">Мы успешно приняли оплату и продвижение началось!</p></b><br>
                <p style="color: #578bcd"><b>Дата начала {{$ext_promotion['started_at']}}
                        продвижения:</b> {{Date::parse($ext_promotion['started_at'])->format('j F H:i')}}</p><br>
                <p style="color: #578bcd"><b>Дата окончания
                        продвижения:</b> {{Date::parse($ext_promotion['started_at'])->addDays($ext_promotion['days'])->format('j F')}} 21:00 МСК
                </p>

                @if(count($ext_promotion->ext_promotion_parsed_reader) > 0)
                    <h4>Статистика читателей на сайте {{$ext_promotion['site']}}</h4>
                    {!! $this->chart->container() !!}
                    <script src="{{ $this->chart->cdn() }}"></script>
                    {{ $this->chart->script() }}
                @else
                    <h4>Статистика будет здесь (обновляется каждый вечер)</h4>
                @endif
                <a style="color: #578bcd" wire:click="update_stat" class="link">Обновить данные</a>

            @elseif ($ext_promotion['ext_promotion_status_id'] == 9)
                {{-- Если уже закончен--}}
                <b><p class="green">Продвижение успешно закончилось!</p></b><br>
                <p style="color: #47AF98"><b>Дата начала
                        продвижения:</b> {{Date::parse($ext_promotion['started_at'])->format('j F H:i')}}</p><br>
                <p style="color: #47AF98"><b>Дата окончания
                        продвижения:</b> {{Date::parse($ext_promotion['started_at'])->addDays($ext_promotion['days'])->format('j F')}} 21:00 МСК
                </p>
                <h4>Статистика читателей на сайте {{$ext_promotion['site']}}</h4>
                <a style="color: #47AF98" wire:click="update_stat" class="link">Обновить данные</a>
                {!! $this->chart->container() !!}
                <script src="{{ $this->chart->cdn() }}"></script>

                {{ $this->chart->script() }}
            @elseif ($ext_promotion['ext_promotion_status_id'] == 99) {{-- Ожидание автора в чате --}}
            <p class="no-access">
                Сейчас продвижение находится "на паузе". Мы задали вопрос в чате (блок наверху этой страницы) и готовы продолжить сразу после вашего ответа.
            </p>
            @elseif ($ext_promotion['ext_promotion_status_id'] == 999) {{-- Неактуальна --}}
            <p class="no-access">
                С заявкой что-то пошло не так и сейчас у нее статус - неактуально.
            </p>
            @endif
        </div>
    </div>
</div>
