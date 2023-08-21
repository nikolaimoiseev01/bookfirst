<div class="chat_wrap">


    <div class="messages_wrap">
        @if($flg_chat_creation) {{-- Если это полностью новый чат --}}
        <div class="no_messages_alert no-access">
            <span> Вы начинаете новую переписку с пользователем.</span>
        </div>
        @elseif(count($messages ?? []) == 0  && !$flg_chat_creation ) {{--Если нет сообщений и это не новый чат--}}
        <p class="no-access">

            Это чат с Вашим личным менеджером по конкретно этому изданию.
            В нем пока нет сообщений.</br>
            Здесь Вы можете задать любые вопросы, а также прикреплять файлы при необходимости.
        </p>
        @endif

        {{App::setLocale('ru')}}
        @if($messages ?? null)
            @foreach($messages as $message)

                <div class="message_wrap">

                    <p class="message_title">
                        @if($message['user_from'] === 2)
                            Поддержка
                        @else
                            {{App\Models\User::where('id',$message['user_from'])->value('name')}}
                        @endif
                    </p>

                    <div style="background:
                    @if($message['user_from'] === Auth::user()->id)
                        #47AF98;
                    @else #e7b34d
                    @endif" class="message_body">

                        <p class="text">{!! nl2br(e($message['text'])) !!}</p>


                        @if (count($message->message_file) > 0)
                            <div class="attach_wrap">
                                <h2 class="title">Прикрепленные
                                    файлы:</h2>
                                <div class="files_wrap">
                                    @foreach($message->message_file as $message_file)
                                        <a class="file_wrap" download href="/{{$message_file['file']}}">

                                            <svg width="15px"
                                                 viewBox="0 0 480 512">
                                                <path
                                                    d="M382.56,233.38A16,16,0,0,0,368,224H304V16A16,16,0,0,0,288,0H224a16,16,0,0,0-16,16V224H144a16,16,0,0,0-12,26.53l112,128a16,16,0,0,0,24.06,0l112-128A16,16,0,0,0,382.56,233.38Z"
                                                    transform="translate(-16 0)"/>
                                                <path
                                                    d="M432,352v96H80V352H16V480a32,32,0,0,0,32,32H464a32,32,0,0,0,32-32V352Z"
                                                    transform="translate(-16 0)"/>
                                            </svg>
                                            <p>
                                                {{substr($message_file['file'], strrpos($message_file['file'], '/') + 1)}}
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif


                    </div>
                    <p class="message_time">{{ Date::parse($message['created_at'])->addHours(3)->format('j F H:i') }} </p>
                </div>
            @endforeach
        @endif
    </div>

    @if($chat['chat_status_id'] ?? null <> 3) {{-- Если есть какой-то статус у чата и он не закрыт--}}

    <x-chat-textarea model="text"
                     placeholder="Введите сообщение"
                     attachable="true" sendable="true"></x-chat-textarea>

    @else
        <div class="chat-closed">
            <p style="margin-right:20px;">Этот чат закрыт</p>
            <a class="button" wire:click.prevent="reopenChat({{$chat['id']}})">
                Открыть чат заново
            </a>
        </div>
    @endif

    @if(Auth::user()->hasRole('admin'))
        <div x-data="{ show_templates: false }" class="templates_block_wrap">
            <a @click="show_templates = !show_templates" class="w-25 mt-3 mb-3 btn btn-primary">Шаблоны</a>
            <div @mousedown.outside="show_templates = false" x-show="show_templates" class="templates_wrap">
                @foreach($templates as $template)
                    <p wire:click.prevent="add_template({{$template['id']}})">{{$template['title']}}</p>
                @endforeach

            </div>
        </div>
    @endif


    @push('page-js')
        @once


            <script>


                function update_hrefs() { /* Смотрим на ссылки при обновлении */
                    $('.message_body .text').each(function () {
                        old_text = $(this).html()
                        linkedText = Autolinker.link(old_text, {
                            className: 'link'
                        });
                        $(this).html(linkedText)
                    })
                }

                function scroll_chats() {
                    $('.messages_wrap').each(function () {
                        $(this).scrollTop(999999999);
                    })
                }


                /* Первичное выполнение всех функций */
                scroll_chats()
                update_hrefs()


                /* Слушатели */

                document.addEventListener('clear_filepond', function () {
                    $('.chat_filepond').filepond('removeFiles')
                });

                document.addEventListener('update_js', function () {
                    scroll_chats()
                    update_hrefs()
                });


                document.addEventListener('filepond_trigger', function () {
                    make_chat_filepond()
                    scroll_chats()
                    update_hrefs()
                })

            </script>
        @endonce
    @endpush

</div>
