<div class="part preview_block_wrap">
    {!! $page_style !!}
    {{App::setLocale('ru')}}
    <div class="line"></div>
    {!! $status_icon !!}
    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>{{$page_title}}</h2>
        </div>
        <div class="info_wrap">
            @if ($collection['col_status_id'] >= 2 && ($participation['pat_status_id'] <= 2 || $participation['pat_status_id'] === 99))
                <p class="no-access">Из-за отсутствия оплаты Вы не были включены в список участников.</p>
            @elseif($collection['col_status_id'] === 1)

                <p class="no-access">Предварительная проверка сборника станет
                    доступна {{ Date::parse($collection['col_date2'])->format('j F Y') }} до 23:59 МСК.
                </p>

            @elseif ($collection['col_status_id'] === 2)
                <div class="pre_var_wrap">
                    <div class="info">
                        <p>На данный момент сборник находится на этапе
                            предварительной
                            проверки. Это означает, что все регистрационные
                            номера присвоены, и блок сверстан. Сейчас необходимо скачать файл, найти свой
                            блок и
                            указать комментарии, что бы вы хотели исправить в своем блоке.
                            Пожалуйста, укажите страницу исправления, а также описание того, что нужно
                            исправить.
                        </p>
                        <a class="button {{$color}}"
                           href="/{{$collection['pre_var']}}"
                           download>
                            <span class="material-symbols-outlined">download</span>
                            Скачать макет
                        </a>
                    </div>
                    <div class="pre_var_right">
                        <h2>Мои исправления</h2>
                        @livewire('account.preview-comment',['collection_id' => $collection->id, 'own_book_id' => 0,
                        'own_book_comment_type' => 'inside'])
                    </div>
                </div>

            @else
                <div class="pre_var_wrap">
                    <div class="info">
                        <p>
                            На данный момент предварительная проверка сборника завершена.
                            Сборник уже находится в печати и скоро будет отправлен авторам.
                            Предварительная дата отправки: {{ Date::parse($collection['col_date4'])->format('j F Y') }}.
                        </p>
                        <a class="button"
                           href="/{{$collection['pre_var']}}"
                           download>
                            <span class="material-symbols-outlined">download</span>
                            Скачать макет

                        </a>
                    </div>
                    <div class="pre_var_right">
                        <h2>Мои исправления:</h2>
                        <div class="chat_wrap">
                            <div class="messages_wrap">
                                @if(count($participation->preview_comment) > 0)
                                    @foreach($participation->preview_comment as $comment)
                                        <div class="message_wrap">
                                            <div style="background:#47AF98" class="message_body">
                                                <p>Стр. {{$comment['page']}}: {{$comment['text']}}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p>Вы не делали исправлений в этом сборнике.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
