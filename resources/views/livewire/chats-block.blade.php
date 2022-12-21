<div style="    position: relative; width:100%; margin-top: 10px;">
    {{App::setLocale('ru')}}


    {{--    <style>--}}
    {{--        #resizable {--}}
    {{--            width: 340px;--}}
    {{--            height: 700px;--}}
    {{--            margin-right: 30px;--}}
    {{--        }--}}

    {{--    </style>--}}
    <div class="back_to_chats" style="display: none">
        <a style="display: flex;  align-items: center; margin-bottom: 20px;" class="link">
            <i style="margin-right: 10px;" class="fa-solid fa-chevron-left"></i>
            Назад к чатам
        </a>
    </div>
    <div class="all_chats_block">


        <div wire:ignore class="chat_to_select_block">


            <div class="admin_chats_block">
                <div class="chats_with_admin_header">
                    <img src="/img/avatars/admin_icon.png" alt="">
                    <p>Чаты с поддержкой</p>
                    <i wire:ignore
                       class="show_admin_chats fa-solid fa-chevron-down"></i>
                </div>
                <div style="padding-top: 5px; border-bottom: 1px #8ecec0 solid !important; display: none;" class="chats_with_admin">
                    @foreach($user_chats as $chat)
                        @if($chat->u_to_id === 2 || $chat->u_cr_id === 2)
                            <div wire:click.prevent="choose_chat({{$chat->id}})"
                                 style="@if(!$new_chat_user_id_check && $chat->id === $this->chosen_chat_id) background: var(--grey_border)@endif"
                                 class="chat_to_select">
                                @if(($chat->flag_mes_read === 0 || $chat->flag_mes_read === null) && $chat->last_mes_to === \Illuminate\Support\Facades\Auth::user()->id)
                                    <livewire:chat-question-check :mes_id="$chat->last_mes_id" :wire:key="$loop->index">
                                        @endif

                                        {{--                                <img src="{{'/img/avatars/admin_icon.png'}}" alt="">--}}
                                        <div>
                                            <p>
                                                {{Str::limit($chat->title, 20, '...')}}
                                            </p>
                                            <span>
                            @if ($chat->last_mes_text === null)
                                                    Нет сообщений
                                                @else
                                                    {{Str::limit($chat->last_mes_text, 18, '...')}}
                                                @endif
                        </span>
                                        </div>
                                        @if ($chat->last_mes_text != null)
                                            <span class="last_mes_date">
                        {{ Date::parse($chat->last_mes_created)->addHours(3)->format('j M') }} <br>
                        {{ Date::parse($chat->last_mes_created)->addHours(3)->format('H:i') }}
                    </span>
                                @endif

                            </div>


                        @endif





                    @endforeach
                </div>
            </div>


            @if($new_chat_user_id)
                <div wire:click.prevent="choose_new_chat()"
                     style="@if($new_chat_user_id_check) background: var(--grey_border)@endif"
                     class="chat_to_select"
                     id="new_chat">
                    <img src="{{$new_chat_user_id['avatar'] ?? '/img/avatars/default_avatar.svg'}}" alt="">
                    <div>
                        <p>
                            {{$new_chat_user_id['name']}}
                        </p>
                        <span> Нет сообщений </span>
                    </div>
                </div>
            @endif

            @foreach($user_chats as $chat)
                @if($chat->u_to_id != 2 && $chat->u_cr_id != 2)


                    <div wire:click.prevent="choose_chat({{$chat->id}})"
                         style="@if(!$new_chat_user_id_check && $chat->id === $this->chosen_chat_id) background: var(--grey_border)@endif"
                         class="chat_to_select">
                        @if(($chat->flag_mes_read === 0 || $chat->flag_mes_read === null) && $chat->last_mes_to === \Illuminate\Support\Facades\Auth::user()->id)

                            <livewire:chat-question-check :mes_id="$chat->last_mes_id" :wire:key="$loop->index">
                                @endif

                                <img src="
                @if(\Illuminate\Support\Facades\Auth::user()->id === $chat->u_cr_id)
                                {{$chat->u_to_avatar ?? '/img/avatars/default_avatar.svg'}}
                                @else
                                {{$chat->u_cr_avatar ?? '/img/avatars/default_avatar.svg'}}
                                @endif
                                    " alt="">
                                <div>
                                    <p>
                                        @if(\Illuminate\Support\Facades\Auth::user()->id === $chat->u_cr_id)
                                            {{Str::limit($chat->u_to_name, 14, '...')}}
                                        @else
                                            {{Str::limit($chat->u_cr_name, 14, '...')}}
                                        @endif
                                    </p>
                                    <span>
                            @if ($chat->last_mes_text === null)
                                            Нет сообщений
                                        @else
                                            {{Str::limit($chat->last_mes_text, 18, '...')}}
                                        @endif
                        </span>
                                </div>
                                @if ($chat->last_mes_text != null)
                                    <span class="last_mes_date">
                        {{ Date::parse($chat->last_mes_created)->addHours(3)->format('j M') }} <br>
                        {{ Date::parse($chat->last_mes_created)->addHours(3)->format('H:i') }}
                    </span>
                        @endif

                    </div>
                @endif
            @endforeach
        </div>
        <span style="" id="load_mobile_chats"
              class="load_mobile_chats button--loading"></span>
        {{--        </div>--}}

        {{--        <script>--}}
        {{--            $(function () {--}}
        {{--                $("#resizable").resizable();--}}
        {{--            });--}}
        {{--        </script>--}}

        <div class="cur_chat_block">
            <div class="cur_chat_header_block">
                <img src="
                @if($new_chat_user_id_check)
                {{$new_chat_user_id['avatar'] ?? '/img/avatars/default_avatar.svg'}}
                @elseif(\Illuminate\Support\Facades\Auth::user()->id === $cur_chat[0]->u_cr_id)
                {{$cur_chat[0]->u_to_avatar ?? '/img/avatars/default_avatar.svg'}}
                @else
                {{$cur_chat[0]->u_cr_avatar ?? '/img/avatars/default_avatar.svg'}}
                @endif
                    " alt="">
                <div>
                    <h3>
                        @if($new_chat_user_id_check)
                            <a href="
{{route('social.user_page', $new_chat_user_id['id'])}}
                                ">
                                @if ($new_chat_user_id['nickname'])
                                    {{$new_chat_user_id['nickname']}}
                                @else
                                    {{$new_chat_user_id['name'] . ' ' . $new_chat_user_id['surname']}}
                                @endif

                            </a>
                        @elseif(\Illuminate\Support\Facades\Auth::user()->id === $cur_chat[0]->u_cr_id)
                            <a href="
{{route('social.user_page', $cur_chat[0]->u_to_id)}}
                                ">
                                {{$cur_chat[0]->u_to_name}}
                            </a>
                        @else
                            <a href="
{{route('social.user_page', $cur_chat[0]->u_cr_id)}}
                                ">
                                {{$cur_chat[0]->u_cr_name}}
                            </a>
                        @endif
                    </h3>
                </div>

                <p>
                    @if($new_chat_user_id_check)
                        <i>Личная переписка</i>
                    @else
                        <i>{{$cur_chat[0]->title}}</i>
                    @endif

                </p>
                @if ($cur_chat_publ_page)
                    <span class="tooltip to_publ_tooltip" title="На страницу издания">
                    <a target="_blank" href="{{$cur_chat_publ_page}}">
                    <svg class="to_publ_page" version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 16.4 11.5"
                         style="fill:var(--green); width:25px; margin: 16px 0 auto 10px; enable-background:new 0 0 16.4 11.5;"
                         xml:space="preserve">
                        <style type="text/css">
                            .st0 {
                                fill: none;
                                stroke: var(--green);
                                stroke-miterlimit: 10;
                            }
                        </style>
                                            <g>
                                                <g>
                                                    <line class="st0" x1="14.3" y1="5.8" x2="1.2" y2="5.8"/>
                                                    <g>
                                                        <path d="M7.5,1C7.4,1.2,7.4,1.5,7.7,1.6l6.5,4.1L7.7,9.9c-0.2,0.1-0.3,0.5-0.2,0.7c0.1,0.2,0.5,0.3,0.7,0.2l7.1-4.5
                                        c0.1-0.1,0.2-0.3,0.2-0.4c0-0.2-0.1-0.3-0.2-0.4L8.2,0.8C8.1,0.7,8,0.7,7.9,0.7C7.8,0.7,7.6,0.8,7.5,1z"/>
                                                    </g>
                                                </g>
                                            </g>
                    </svg>
                        </a>
                </span>
                @endif


            </div>


            <div style=" width: 100%;">

                <form
                    style="max-height: 670px; display: flex; flex-direction: column;"
                    wire:submit.prevent=""
                    enctype="multipart/form-data">
                    @csrf


                    <div id="messages" class="messages">
                        @if(!$new_chat_user_id_check)
                            @if(count($messages) == 0 && ($cur_chat[0]->collection_id || $cur_chat[0]->own_book_id) > 0)
                                <div
                                    style="margin:0; height: 100%; display: flex; align-items: center; justify-content: center"
                                    class="no-access">
                            <span>
                                Это чат с Вашим личным менеджером по конкретно этому изданию.
                                В нем пока нет сообщений.</br>
                                Здесь Вы можете задать любые вопросы, а также прикреплять файлы при необходимости.
                            </span>
                                </div>
                            @endif


                            @foreach($messages as $message)

                                <div
                                    {{--                    wire:ignore --}}
                                    class="message">
                                    <p style="font-size: 18px;">@if($message['user_from'] === 2)
                                            Поддержка @else {{App\Models\User::where('id',$message['user_from'])->value('name')}}@endif</p>
                                    <div style="background:
                                    @if($message['user_from'] === Auth::user()->id)
                                        #47AF98;
                                    @else #e7b34d
                                    @endif" class="message-wrap">
                                        <p>{!! nl2br(e($message['text'])) !!}</p>
                                        @if (count($message->message_file) > 0)
                                            <div>
                                                <h2 style="font-size: 24px; margin-bottom: 0; margin-top: 10px;">
                                                    Прикрепленные
                                                    файлы:</h2>
                                                <div style="display: flex; flex-direction: column;">
                                                    @foreach($message->message_file as $message_file)
                                                        <div>
                                                            <a download href="/{{$message_file['file']}}">

                                                                <svg width="15px"
                                                                     style="margin-right: 5px; fill:#ffffff"
                                                                     viewBox="0 0 480 512">
                                                                    <path
                                                                        d="M382.56,233.38A16,16,0,0,0,368,224H304V16A16,16,0,0,0,288,0H224a16,16,0,0,0-16,16V224H144a16,16,0,0,0-12,26.53l112,128a16,16,0,0,0,24.06,0l112-128A16,16,0,0,0,382.56,233.38Z"
                                                                        transform="translate(-16 0)"/>
                                                                    <path
                                                                        d="M432,352v96H80V352H16V480a32,32,0,0,0,32,32H464a32,32,0,0,0,32-32V352Z"
                                                                        transform="translate(-16 0)"/>
                                                                </svg>

                                                            </a>
                                                            <p>{{substr($message_file['file'], strrpos($message_file['file'], '/') + 1)}}

                                                            </p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    <p style="font-size: 17px; float:right;">{{ Date::parse($message['created_at'])->addHours(3)->format('j F H:i') }} </p>
                                </div>
                            @endforeach
                        @endif
                    </div>


                    @if($cur_chat[0]->chat_status_id <> 3)
                        <div style="z-index: 10">
                            <div class="chat_files" wire:ignore>
                                <input wire:ignore accept multiple name="chat_files" class="chat_filepond" type="file"/>
                            </div>
                            <div wire:ignore class="input-block">
                <textarea oninput="auto_grow(this)"
                          class="textarea_chat"
                          wire:model.defer="text"
                          style="z-index: 10; border-radius: 10px 0 0 10px; border-right: none;"
                          name="chat_text"
                          placeholder="Введите сообщение"
                          type="text"
                          id="chat_text"

                ></textarea>

                                <div class="send-wrap">
                    <span style="display: grid; position: absolute; right: 28px;">
                        <span class="tooltip" title="Прикрепить файл">
                        <svg onclick="trigger_filepond_function()" class="attach_icon" viewBox="0 0 268.12 494.4"><path
                                d="M247.2,0C173.29,0,113.14,60.13,113.14,134.06V387.87a16.39,16.39,0,1,0,32.78,0V134.06a101.28,101.28,0,0,1,202.56,0V395.73a66,66,0,0,1-65.89,65.89c-.27,0-.52.14-.79.16s-.51-.16-.79-.16a66,66,0,0,1-65.9-65.89v-157a32.09,32.09,0,1,1,64.18,0v149.1a16.39,16.39,0,0,0,32.78,0V238.77a64.87,64.87,0,1,0-129.74,0v157A98.78,98.78,0,0,0,281,494.4c.29,0,.52-.15.8-.16s.52.16.79.16a98.79,98.79,0,0,0,98.67-98.67V134.06C381.26,60.13,321.11,0,247.2,0Z"
                                transform="translate(-113.14 0)"/></svg>
                        </span>
                    </span>
                                    <button type="submit">
                                        <div style="position: relative;" class="send_mes_button">
                            <span id="send_env" class="tooltip" title="Отправить">
                                <svg id="send_message_{{$cur_chat[0]->id}}" id="Capa_1" data-name="Capa 1"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 512 512">
                                    <path
                                        d="M507.61,4.39a15,15,0,0,0-16.18-3.32l-482,192.8a15,15,0,0,0-1,27.43l190.07,92.18L290.7,503.54A15,15,0,0,0,304.2,512h.53a15,15,0,0,0,13.4-9.42l192.8-482A15,15,0,0,0,507.61,4.39ZM52.09,209.12l382.63-153-228,228ZM302.88,459.91l-75-154.6,228-228Z"
                                        transform="translate(0 0)"/>
                                </svg>
                            </span>
                                            <span style="display: none;" id="send_preloader"
                                                  class="button--loading"></span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="chat-closed">
                            <p style="margin-right:20px;">Этот чат закрыт</p>
                            <a class="button" wire:click.prevent="reopenChat({{$chat['id']}})">
                                Открыть чат заново
                            </a>
                        </div>
                    @endif
                </form>


                <script>


                </script>


                @section('page-js')

                    <script>

                        // --   Смотрим на ссылки при обновлении--
                        function update_hrefs() {
                            $('.message-wrap').each(function () {
                                // alert($(this).html());
                                var urlRegex = /(https?:\/\/[^\s]+)/g;
                                var found_link = $(this).text().match(urlRegex);

                                var replaced_text = $(this).html().replace(found_link,
                                    '<a style="color: #ffffff; font-style: italic; font-weight: 700" target="_blank" href="' + found_link + '">' + found_link + '</a>');

                                $(this).html(replaced_text);
                            })
                        }

                        document.addEventListener('update_hrefs', function scroll_down() {
                            update_hrefs();
                        });

                        update_hrefs();
                        // --   Смотрим на ссылки при обновлении--


                        // Скрываем и показываем блок переписки с поддержкой
                        function show_admin_chats() {
                            $('.chats_with_admin_header').click(function () {
                                chats_with_admin = $('.chats_with_admin'),
                                    show_admin_chats = $('.show_admin_chats');
                                chats_with_admin.slideToggle({
                                    complete: function () {
                                        if (chats_with_admin.is(":visible")) {
                                            show_admin_chats.removeClass('fa-chevron-down');
                                            show_admin_chats.addClass('fa-chevron-up');
                                        } else {
                                            show_admin_chats.removeClass('fa-chevron-up');
                                            show_admin_chats.addClass('fa-chevron-down');
                                        }
                                    }
                                });
                            })
                        }

                        show_admin_chats();

                        @if(!$new_chat_user_id_check && ($this->cur_chat[0]->u_cr_id === 2 || $this->cur_chat[0]->u_to_id === 2))
                        $('.chats_with_admin_header').click();
                        @endif;

                        // Когда кликаем на выбранный чат - меняем курсор и фон
                        function click_and_wait() {
                            $('.chat_to_select').css('cursor', 'pointer');
                            $('.chat_to_select').click(function () {
                                $('.chat_to_select').css('background', 'none');
                                $(this).css('background', '#e6e6e6');
                                $(this).css('cursor', 'wait');
                                document.body.style.cursor = 'wait';
                            })
                        }

                        click_and_wait();

                        function when_upload_start() {
                            $('#send_message_{{$cur_chat[0]->id}}').prop('disabled', true);
                        }

                        // --------------------------------- //
                        // --- Работа с загрузкой файлов --- //
                        // --------------------------------- //
                        function calculate_after_upload() {
                            var totalFiles = $('.chat_files .upload_type .filepond--item').length;
                            var completedFiles = $('.chat_files .upload_type .filepond--item[data-filepond-item-state="processing-complete"]').length;
                            if (completedFiles === totalFiles) {
                                message_files_paths = $("[name='chat_files']");
                                message_files = '';
                                for (var i = 0; i < message_files_paths.length; i++) {
                                    if ($(message_files_paths[i]).val() != '') {
                                        message_files += 'filepond_temp/' + $(message_files_paths[i]).val() + ';';
                                    }
                                }
                            @this.set("message_files", message_files.slice(0, -1));
                            } else {

                            }
                        }

                        function calculate_after_remove() {
                            message_files_paths = $("[name='chat_files']");
                            message_files = '';
                            for (var i = 0; i < message_files_paths.length; i++) {
                                if ($(message_files_paths[i]).val() != '') {
                                    message_files += $(message_files_paths[i]).val() + ';';
                                }
                            }
                        @this.emit('message_files', message_files.slice(0, -1));

                        }

                        FilePond.registerPlugin(FilePondPluginFileValidateSize);

                        $('.chat_filepond').filepond({
                            server: {
                                url: '/myaccount/temp-uploads/chat_files',
                                headers: {
                                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                                }
                            },
                            onprocessfile: (file) => {
                                calculate_after_upload();
                            },
                            onaddfilestart: (file) => {
                                when_upload_start();
                            },
                            onremovefile: (file) => {
                                calculate_after_remove();
                            },
                            labelIdle: `<span id="file_pond_button"></span>`,
                            maxFileSize: '10MB',
                            maxTotalFileSize: '20MB',
                            labelMaxFileSizeExceeded: 'Размер превышен!',
                            labelMaxFileSize: 'Максимальный: {filesize}',
                            labelMaxTotalFileSizeExceeded: 'Сумма размеров превышена!',
                            labelMaxTotalFileSize: 'Максимум: {filesize}',
                        });

                        var filePondObj = FilePond.create($('.chat_filepond').input);

                        function trigger_filepond_function() {
                            $('#file_pond_button').click();
                        }

                        document.addEventListener('clear_filepond', function () {
                            $('.chat_filepond').filepond('removeFiles')
                        });

                        document.addEventListener('show_send_button', function () {
                            $('#send_preloader').hide();
                            $('#send_env').css('opacity', 1);
                            $('.send-wrap button').prop("disabled", false);
                            $('.input-block').css('height', "100px");
                        });

                        // --- // Работа с загрузкой файлов


                        document.addEventListener('scroll_down', function scroll_down() {
                            document.getElementById('messages').scrollTop = 9999999;
                        });

                        document.getElementById('messages').scrollTop = 9999999;


                        document.addEventListener('new_chat_hide', function () {
                            $('#new_chat').hide();
                        })


                        // Адаптив

                        function show_only_cur() {
                            if ($(window).width() < 820 && window.location.href.includes("new_chat_user_id")) { // Если маленькое окно и делаем новый чат - хотим сразу видеть ввод
                                $('.chat_to_select_block').hide();
                                $('.cur_chat_block').show();
                                $('.back_to_chats').show();
                                $('.account-header').hide();
                            }
                        }

                        show_only_cur()

                        function make_adaptive_view() {
                            if ($(window).width() < 820) {
                                $('.chat_to_select').css('background', 'none');


                                $('.chat_to_select').click(function () {
                                    $('.chat_to_select_block').hide();
                                    $('.load_mobile_chats').show();
                                    $('.account-header').hide();
                                })

                                $('.back_to_chats').click(function () {
                                    $('.chat_to_select_block').show();
                                    $('.cur_chat_block').hide();
                                    $('.back_to_chats').hide();
                                    $('.chat_to_select').css('background', 'none');
                                    $('.account-header').show();
                                })
                            } else {
                                $('.cur_chat_block').show();
                                $('.back_to_chats').off('click'); //disables click event
                                $('.chat_to_select').off('click'); //disables click event
                                click_and_wait()
                            }
                        }

                        make_adaptive_view();

                        document.addEventListener('show_admin_chats_true', function () {
                            if ($(window).width() < 820) {
                                $('.cur_chat_block').show();
                                $('.account-header').hide();
                                $('.back_to_chats').show();
                            }
                        })

                        document.addEventListener('show_cur_chat_block_after_send', function () {
                            $('.cur_chat_block').show();
                            if ($(window).width() < 820) {
                                $('.back_to_chats').show();
                            }
                            $('#messages').scrollTop(9999999999);


                        })






                        $(window).on('resize', function () {
                            make_adaptive_view();
                        });


                        document.addEventListener('livewire:load', function () {

                            document.body.style.cursor = 'inherit';
                            $("#send_message_{{$cur_chat[0]->id}}").on('click', function (event) {
                                event.preventDefault();
                                Livewire.emit('new_message')
                            })
                        })

                        document.addEventListener('livewire:update', function () {
                            document.body.style.cursor = 'inherit';
                            click_and_wait();
                            make_adaptive_view();
                            show_only_cur();
                            $('#messages').scrollTop(9999999999);
                        })

                    </script>

                @endsection
            </div>

        </div>
    </div>
</div>
