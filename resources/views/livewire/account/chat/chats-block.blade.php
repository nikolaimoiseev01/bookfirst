<div class="chats_block_wrap">
    {{App::setLocale('ru')}}


    @if(count($user_chats_all_check) == 0 && !$new_chat_user_id_check)
        <h1 class="no-access">У Вас нет активных обсуждений на данный момент</h1>
    @endif

    @if(count($user_chats_all_check) > 0 || $new_chat_user_id_check)

        <div wire:click.prevent="back_to_chats()"
             class="@if($show_type === 'list') hide_block @endif back_to_chats">
            <a class="link">
                <i class="fa-solid fa-chevron-left"></i>
                Назад к чатам
            </a>
        </div>

        <div class="@if($show_type === 'chat') hide_block @endif all_chats_block">

            <div class="chats_type_wrap">
                <p class="@if($chats_type_to_show === 'personal') active @endif"
                   wire:click.prevent="choose_chats_type('personal')">Личные</p>
                <p class="@if($chats_type_to_show === 'admin') active @endif"
                   wire:click.prevent="choose_chats_type('admin')">Поддержка</p>
            </div>

            @if(count($cur_chat)>0 || $new_chat_user_id_check)
                <div class="chat_to_select_block">

                    @if($new_chat_user_id_check)
                        <div wire:click.prevent="choose_new_chat()"
                             class="@if($new_chat_user_id_check && !$cur_chat_id) active @endif chat_to_select"
                             id="new_chat">
                            <img src="{{$new_chat_user['avatar'] ?? '/img/avatars/default_avatar.svg'}}" alt="">
                            <div>
                                <p>
                                    {{prefer_name($new_chat_user['name'], $new_chat_user['surname'], $new_chat_user['nickname'])}}
                                </p>
                                <span> Нет сообщений </span>
                            </div>
                        </div>
                    @endif

                    @foreach($user_chats as $chat)
                        <div wire:click.prevent="choose_chat({{$chat['id']}})"
                             class="@if($chat['id'] === $cur_chat_id ?? null) active @endif chat_to_select">
                            @if(($chat['flag_mes_read'] === 0 || $chat['flag_mes_read'] === null) && $chat['last_mes_to'] === \Illuminate\Support\Facades\Auth::user()->id)

                                <livewire:account.chat.chat-question-check :chat_id="$chat->id"
                                                                           :wire:key="$loop->index">
                                    @endif

                                    <img src="{{$chat['u_avatar'] ?? '/img/avatars/default_avatar.svg'}}" alt="">
                                    <div>
                                        <p>{{Str::limit($chat['u_name'], 14, '...')}}</p>
                                        <span>
                                            @if ($chat['last_mes_text'] === null)
                                                Нет сообщений
                                            @else
                                                {{Str::limit($chat['last_mes_text'], 18, '...')}}
                                            @endif
                                        </span>
                                    </div>
                                    @if ($chat['last_mes_text'] != null)
                                        <span class="last_mes_date">
                        {{ Date::parse($chat['last_mes_created'])->addHours(3)->format('j M') }} <br>
                        {{ Date::parse($chat['last_mes_created'])->addHours(3)->format('H:i') }}
                    </span>
                            @endif

                        </div>

                    @endforeach

                </div>
            @else
                <h1 class="no-access">Чатов в этой категории еще нет </h1>
            @endif

            <span style="" id="load_mobile_chats"
                  class="load_mobile_chats button--loading"></span>
        </div>


        <div class="@if($show_type === 'list') hide_block @endif cur_chat_block">

            @if(count($cur_chat)>0 || $new_chat_user_id_check)
                <div class="cur_chat_header_block">
                    <img
                        src="{{$cur_chat['u_avatar'] ?? $new_chat_user['avatar'] ?? '/img/avatars/default_avatar.svg'}}"
                        alt="">

                    <div class="info_wrap">
                        <a href="{{route('social.user_page', $cur_chat['u_id'] ?? $new_chat_user['id'])}}">
                            <h3>
                                {{$cur_chat['u_name'] ?? prefer_name($new_chat_user['name'], $new_chat_user['surname'], $new_chat_user['nickname'])}}
                            </h3>
                        </a>

                        @if($new_chat_user_id_check)
                            <i>Личная переписка</i>
                        @elseif($cur_chat_publ_page)
                            <a class="link tooltip" href="{{$cur_chat_publ_page}}" title="На страницу издания">
                                <i>{{$cur_chat['title']}}</i>
                            </a>
                        @elseif($cur_chat['title'])
                            <i>{{$cur_chat['title']}}</i>
                        @endif

                    </div>
                </div>
            @endif

            <div @if(count($cur_chat)==0 && !$new_chat_user_id_check) style="display: none" @endif>
                <livewire:account.chat.chat key="{{ rand() }}"
                                            :chat_id="$cur_chat['id'] ?? null"
                                            :new_chat_user_id="$new_chat_user->id ?? null"/>
            </div>


        </div>


    @endif


    @push('page-js')

        <script>

            function click_and_wait() { /* Когда кликаем на выбранный чат - меняем курсор и фон */
                $('.chat_to_select').css('cursor', 'pointer');
                $('.chat_to_select').click(function () {
                    $('.chat_to_select').css('background', 'none');
                    $(this).css('background', '#e6e6e6');
                    $(this).css('cursor', 'wait');
                    document.body.style.cursor = 'wait';
                })
            }

            click_and_wait()

            document.addEventListener('livewire:update', function () {
                document.body.style.cursor = 'inherit';
                click_and_wait();
                $('#messages').scrollTop(9999999999);
            })

        </script>

    @endpush

</div>


