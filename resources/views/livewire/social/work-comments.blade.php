<div class="comments_block_wrap">
    <link rel="stylesheet" href="/css/chat.css">
    <link rel="stylesheet" href="/css/social.css">
    <div class="work_comments_block">
        <h2>Комментарии
            <a id="show_add_comment" class="link social">Добавить <i class="fa-solid fa-plus"></i></a>
        </h2>
        @if(count($comments) === 0 || !$comments)
            <br>
            <p>Еще нет ни одного. Будьте первым!</p>
        @endif


        <form method="post"
              wire:submit.prevent="create_comment(Object.fromEntries(new FormData($event.target)))"
              id="add_comment_form"
              style="margin-top: 15px; margin-bottom: 15px; display: none;"
              enctype="multipart/form-data">
            @csrf
            <input wire:model="work_id" value="" style="display: none" type="number" name="work_id" id="work_id">


            <div style="max-width: 1000px; z-index: 10">
                <div class="input-block">

                    <input style="display: none" id="work_id" name="work_id" value="{{$work['id']}}" type="number">


                    <textarea class="textarea_chat"
                              style="z-index: 10; border-radius: 10px 0 0 10px; border-right: none;"
                              name="comment_text"
                              type="text"
                              oninput="auto_grow(this)"
                              id="comment_text"
                              placeholder="Ваш комментарий"
                    ></textarea>


                    <div class="send-wrap">
                        <button id="new_comment" class="log_check" type="submit">
                            <div style="position: relative;" class="send_mes_button">
                            <span id="send_env" class="tooltip" title="Отправить">
                                <svg id="send_message_2" id="Capa_1" data-name="Capa 1"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 512 512">
                                    <path
                                        d="M507.61,4.39a15,15,0,0,0-16.18-3.32l-482,192.8a15,15,0,0,0-1,27.43l190.07,92.18L290.7,503.54A15,15,0,0,0,304.2,512h.53a15,15,0,0,0,13.4-9.42l192.8-482A15,15,0,0,0,507.61,4.39ZM52.09,209.12l382.63-153-228,228ZM302.88,459.91l-75-154.6,228-228Z"
                                        transform="translate(0 0)"/>
                                </svg>
                            </span>
                                <span style="display: none;" id="send_preloader" class="button--loading"></span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

        </form>

        @foreach($comments as $comment)

            {{--Основные комменты--}}
            <div wire:key="{{$comment['id']}}" style="padding: 15px 0;" class="comment_block">
                <div>
                    <div style="display: flex; align-items: center; flex-wrap: wrap;">
                        <img style="margin-right: 20px; border-radius: 100%; width:40px; height: 40px;"
                             src="{{($comment->user['avatar'] ?? '/img/avatars/default_avatar.svg')}}"
                             alt="user_avatar">
                        <a style="margin-right: 10px; font-size: 24px;"
                           href="{{route('social.user_page', $comment['user_id'])}}" class="link social">
                            {{($comment->user['nickname']) ? $comment->user['nickname'] : $comment->user['name'] . ' ' . $comment->user['surname']}}
                        </a>
                        <p style="margin-top: 0; margin-right: 10px; font-size: 18px; color: var(--grey_font)">
                            ({{ Date::parse($comment['created_at'])->format('j F Y') }})
                        </p>


                        @foreach($replies_check as $reply_check)
                            <div wire:key="reply_text_{{$reply_check->parent_comment_id}}">
                                @if($reply_check->parent_comment_id == $comment['id'])
                                    <p style="margin-top: 0; font-size: 18px; color:var(--grey_font)">
                                        <a style="font-size: 18px;" id="hide_replies_comment_{{$comment['id']}}"
                                           class="hide_replies link social">Скрыть ответы</a>
                                        ({{$reply_check->replies_to_comment}})
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <a data-comment_name="{{($comment->user['nickname']) ? $comment->user['nickname'] : $comment->user['name'] . ' ' . $comment->user['surname']}}"
                       data-comment_uid="{{$comment['user_id']}}"
                       data-comment_id="{{$comment['id']}}"
                       id="reply_button_for_comment_{{$comment['id']}}"
                       class="reply_button link social">
                        Ответить
                    </a>
                </div>
                <p>{{$comment['text']}}</p>
            </div>


            {{-- Ответы --}}
            <div class="replies_block" id="replies_block_comment_{{$comment['id']}}">
                @foreach($replies as $reply)
                    <div wire:key="{{$reply->id}}">

                        @if($reply->parent_comment_id == $comment['id'])
                            <div style="border:none; display: flex; max-width: 1000px;" class="comment_block">
                                <div
                                    style="margin-left:40px; margin-right: 40px; border-left:1px solid var(--grey_border)">

                                </div>
                                <div
                                    style="flex-wrap: wrap; padding: 15px 0; flex:1; border-bottom: 1px solid var(--grey_border);">
                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; flex-wrap: wrap;">
                                            <img style="margin-right: 20px; border-radius: 100%; width:40px;"
                                                 src="{{($reply->avatar ?? '/img/avatars/default_avatar.svg')}}"
                                                 alt="user_avatar">
                                            <a style="margin-right: 10px; font-size: 24px;"
                                               href="{{route('social.user_page', $reply->user_id)}}"
                                               class="link social">
                                                {{($reply->nickname) ? $reply->nickname : $reply->name . ' ' . $reply->surname}}
                                            </a>
                                            <p style="margin-top: 0; margin-right: 10px;  font-size: 18px; color: var(--grey_font)">
                                                ({{ Date::parse($reply->created_at)->format('j F Y') }})
                                            </p>

                                            <p style="margin-top: 0; font-size: 18px; color: var(--grey_font)">
                                                <i>В ответ на:
                                                    "{{Str::limit(Str::ucfirst(Str::lower($reply->reply_to_text)), 40, '...')}}
                                                    "</i>

                                            </p>
                                        </div>
                                        <a data-comment_name="{{($reply->nickname) ? $reply->nickname : $reply->name . ' ' . $reply->surname}}"
                                           data-comment_uid="{{$reply->user_id}}"
                                           data-comment_id="{{$reply->id}}"
                                           id="reply_button_for_comment_{{$comment['id']}}"
                                           style="font-size: 18px;"
                                           class="reply_button link social">
                                            <i>Ответить</i>
                                        </a>
                                    </div>
                                    <p>{{$reply->text}}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach


                <form method="post"
                      wire:submit.prevent="create_comment(Object.fromEntries(new FormData($event.target)))"
                      enctype="multipart/form-data">
                    @csrf
                    <div id="reply_block_for_comment_{{$comment['id']}}"
                         style="margin-top:10px; margin-bottom: 10px; display: none; max-width: 1000px; z-index: 10">
                        <p style="font-size: 18px; color: var(--grey_font)">Ответ пользователю: <span
                                id="reply_to_user_name_{{$comment['id']}}"></span></p>
                        <div style="height: 50px; " class="input-block">

                            <input style="display: none" id="work_id" name="work_id" value="{{$work['id']}}"
                                   type="number">
                            <input style="display: none" id="input_rep_uid_comment_{{$comment['id']}}"
                                   name="reply_to_user_id" type="number">
                            <input style="display: none" id="input_rep_id_comment_{{$comment['id']}}"
                                   name="reply_to_comment_id" type="number">
                            <input style="display: none" value="{{$comment['id']}}" name="parent_comment_id"
                                   type="number">

                            <textarea class="textarea_chat"
                                      style="z-index: 10; border-radius: 10px 0 0 10px; border-right: none;"
                                      name="comment_text"
                                      type="text"
                                      id="comment_text_reply_to_{{$comment['id']}}"
                                      data-reply_to_user_id="{{$comment['user_id']}}"
                                      oninput="auto_grow(this, 50)"

                            ></textarea>

                            <div class="send-wrap">
                                <button id="reply_button_{{$comment['id']}}" class="save_reply log_check"
                                        type="submit">
                                    <div style="position: relative;" class="send_mes_button">
                            <span id="send_env" class="tooltip" title="Отправить">
                                <svg id="send_message_2" id="Capa_1" data-name="Capa 1"
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
                </form>
            </div>
        @endforeach
        <div style="    display: flex; margin-top: 20px;">
            @if($comment_amt < $all_comments_amt)
                <p style="margin-right: 10px; font-size: 22px;">Комментариев загружено: {{$comment_amt}}
                    из {{$all_comments_amt}}</p>
                <a id="load_more" wire:click="load_more_comments" class="link social">Загрузить еще</a>
            @elseif($all_comments_amt > 5)
                <p style="font-size: 22px;">Все комментарии ({{$all_comments_amt}}) загружены</p>

            @endif
        </div>
    </div>

    @push('page-js')
        <script>
            function page_js_clicks() {

                $('textarea').val('');

                $("#show_add_comment").on('click',
                    function (event) {
                        event.stopImmediatePropagation();
                        event.preventDefault();
                        if ($(this).html() === 'Добавить <i class="fa-solid fa-plus"></i>') {
                            $(this).html('Свернуть')
                        } else {
                            $(this).html('Добавить <i class="fa-solid fa-plus"></i>')
                        }
                        $("#add_comment_form").slideToggle("fast");
                    }
                );

                $(".reply_button").on('click',
                    function (event) {
                        event.stopImmediatePropagation();
                        event.preventDefault();
                        comment_id = $(this).attr('id').substring(25, 500);
                        comment_name = $(this).attr('data-comment_name');
                        comment_uid = $(this).attr('data-comment_uid');
                        comment_to_reply_id = $(this).attr('data-comment_id');

                        if ($(this).text() === 'Свернуть') {
                            $("#reply_block_for_comment_" + comment_id).slideUp("fast");
                            $(this).html('Ответить')
                        } else {
                            $("#reply_block_for_comment_" + comment_id).slideDown("fast");
                            $(this).html('Свернуть')
                        }

                        $('#input_rep_uid_comment_' + comment_id).val(comment_uid);
                        $('#input_rep_id_comment_' + comment_id).val(comment_to_reply_id);

                        $("#reply_to_user_name_" + comment_id).text(comment_name);


                    }
                );


                $(".hide_replies").on('click',
                    function (event) {
                        event.stopImmediatePropagation();
                        event.preventDefault();
                        comment_id = $(this).attr('id').substring(21, 500);
                        replies_block = $("#replies_block_comment_" + comment_id)

                        if (replies_block.is(":visible")) {
                            $("#hide_replies_comment_" + comment_id).text('Показать ответы');
                        } else {
                            $("#hide_replies_comment_" + comment_id).text('Скрыть ответы');
                        }
                        replies_block.slideToggle("fast");

                    }
                );

                @if(count($comments) === 0 || !$comments)
                $("#add_comment_form").show();
                $('#show_add_comment').hide();
                @endif
                console.log('function start')
            }

            page_js_clicks();

            document.addEventListener('livewire:update', function () {
                page_js_clicks();
            })


            document.addEventListener('stop_send_preloader', function () {
                $('.button--loading').hide();
                $('.tooltip').css('opacity', 1);
                setTimeout(function () {
                    $('.send-wrap button').prop("disabled", false);
                }, 100)
            })

        </script>

    @endpush

</div>
</div>
