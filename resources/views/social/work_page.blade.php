@extends('layouts.portal_layout')

@section('page-title')Соц. сеть@endsection

@section('page-style')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/social.css">
    <link rel="stylesheet" href="/css/books-example.css">
    <link rel="stylesheet" href="/plugins/slick/slick.css">
@endsection


@section('content')
    <div class="content">
        @include('layouts.parts.user_portal_header')

        <div class="work_block">
            <div>
                <h2>{{$work['title']}}  </h2>
                @livewire('like-button', ['work_id' => $work->id])
            </div>

            <div>
                <p>{!! nl2br($work['text']) !!}</p>
            </div>
            {{App::setLocale('ru')}}
            <div>
                <p>
                    <b>Рубрика:</b>
                    <span style="color: var(--grey_font) !important;">
                    @if ($work->work_type['type'] == 'Не определено')
                            {{Str::lower($work->work_type['type'])}}
                        @else
                            {{Str::lower($work->work_type['type'])}} / {{Str::lower($work->work_type['topic'])}}
                        @endif


                </span>
                </p>

                <br>

                <p>
                    <b>Опубликовано:</b>
                    <span
                        style="color: var(--grey_font) !important;"> {{ Date::parse($work['created_at'])->format('j F Y') }}</span>
                </p>

            </div>
        </div>

        <link rel="stylesheet" href="/css/chat.css">
        <div class="work_comments_block">
            <h2>Комментарии
                <a id="show_add_comment" class="link">Добавить <i class="fa-solid fa-plus"></i></a>
            </h2>

            <form method="post"
                  action="{{ route('social.create_comment') }}"
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
                                  name="comment_text" required
                                  type="text"
                                  oninput="auto_grow(this)"
                                  id="comment_text"
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
                <div style="padding: 15px 0;" class="comment_block">
                    <div>
                        <img style="width:40px;"
                             src="{{($comment->user['avatar'] ?? '/img/avatars/default_avatar.png')}}"
                             alt="user_avatar">
                        <a style="margin-left: 20px; font-size: 24px;"
                           href="{{route('social.user_page', $comment['user_id'])}}" class="link">
                            {{($comment->user['nickname']) ? $comment->user['nickname'] : $comment->user['name'] . ' ' . $comment->user['surname']}}
                        </a>
                        <p style="margin-top: 0; margin-left: 10px; font-size: 18px; color: var(--grey_font)">
                            ({{ Date::parse($comment['created_at'])->format('j F Y') }})
                        </p>



                        @foreach($replies_check as $reply_check)
                            @if($reply_check->parent_comment_id == $comment['id'])
                                <p style="margin-top: 0; margin-left: 10px; font-size: 18px; color:var(--grey_font)">
                                    <a style="font-size: 18px;" id="hide_replies_comment_{{$comment['id']}}" class="hide_replies link">Скрыть ответы</a> ({{$reply_check->replies_to_comment}})
                                </p>
                            @endif
                        @endforeach

                        <a data-comment_name="{{($comment->user['nickname']) ? $comment->user['nickname'] : $comment->user['name'] . ' ' . $comment->user['surname']}}"
                           data-comment_uid="{{$comment['user_id']}}"
                           data-comment_id="{{$comment['id']}}"
                           id="reply_button_for_comment_{{$comment['id']}}"
                           style="margin-left: auto"
                           class="reply_button link">
                            Ответить
                        </a>
                    </div>
                    <p>{{$comment['text']}}</p>
                </div>


                {{-- Ответы --}}
                <div class="replies_block" id="replies_block_comment_{{$comment['id']}}">
                    @foreach($replies as $reply)

                        @if($reply->parent_comment_id == $comment['id'])
                            <div style="border:none; display: flex; max-width: 1000px;" class="comment_block">
                                <div
                                    style="margin-left:40px; margin-right: 40px; border-left:1px solid var(--grey_border)">

                                </div>
                                <div style="padding: 15px 0; flex:1; border-bottom: 1px solid var(--grey_border);">
                                    <div style="display: flex; align-items: center;">
                                        <img style="width:40px;"
                                             src="{{($reply->avatar ?? '/img/avatars/default_avatar.png')}}"
                                             alt="user_avatar">
                                        <a style="margin-left: 20px; font-size: 24px;"
                                           href="{{route('social.user_page', $reply->user_id)}}" class="link">
                                            {{($reply->nickname) ? $reply->nickname : $reply->name . ' ' . $reply->surname}}
                                        </a>
                                        <p style="margin-top: 0; margin-left: 10px; font-size: 18px; color: var(--grey_font)">
                                            ({{ Date::parse($reply->created_at)->format('j F Y') }})
                                        </p>

                                        <p style="margin-left: 10px; margin-top: 0; font-size: 18px; color: var(--grey_font)">
                                            <i>В ответ на:
                                                "{{Str::limit(Str::ucfirst(Str::lower($reply->reply_to_text)), 40, '...')}}
                                                "</i>

                                        </p>

                                        <a data-comment_name="{{($reply->nickname) ? $reply->nickname : $reply->name . ' ' . $reply->surname}}"
                                           data-comment_uid="{{$reply->user_id}}"
                                           data-comment_id="{{$reply->id}}"
                                           id="reply_button_for_comment_{{$comment['id']}}"
                                           style="margin-left: auto; font-size: 18px;"
                                           class="reply_button link">
                                            <i>Ответить</i>
                                        </a>
                                    </div>
                                    <p>{{$reply->text}}</p>
                                </div>
                            </div>
                        @endif

                    @endforeach


                <form method="post"
                      action="{{ route('social.create_comment') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div id="reply_block_for_comment_{{$comment['id']}}"
                         style="margin-top:10px; margin-bottom: 10px; display: none; max-width: 1000px; z-index: 10">
                        <p style="font-size: 18px; color: var(--grey_font)">Ответ пользователю: <span
                                id="reply_to_user_name_{{$comment['id']}}"></span></p>
                        <div wire:ignore style="height: 50px; " class="input-block">

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
                                      name="comment_text" required
                                      type="text"
                                      id="comment_text_reply_to_{{$comment['id']}}"
                                      data-reply_to_user_id="{{$comment['user_id']}}"
                                      oninput="auto_grow(this, 50)"

                            ></textarea>

                            <div class="send-wrap">
                                <button id="reply_button_{{$comment['id']}}" class="save_reply log_check" type="submit">
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
                </div>
            @endforeach

        </div>

        <script>
            $("#show_add_comment").click(
                function (event) {
                    event.preventDefault();
                    $("#add_comment_form").slideToggle("fast");
                }
            );

            $(".reply_button").click(
                function (event) {
                    event.preventDefault();
                    comment_id = $(this).attr('id').substring(25, 500);
                    comment_name = $(this).attr('data-comment_name');
                    comment_uid = $(this).attr('data-comment_uid');
                    comment_to_reply_id = $(this).attr('data-comment_id');

                    $('#input_rep_uid_comment_' + comment_id).val(comment_uid);
                    $('#input_rep_id_comment_' + comment_id).val(comment_to_reply_id);

                    $("#reply_to_user_name_" + comment_id).text(comment_name);

                    $("#reply_block_for_comment_" + comment_id).slideDown("fast");

                }
            );


            $(".hide_replies").click(
                function (event) {
                    event.preventDefault();
                    comment_id = $(this).attr('id').substring(21, 500);
                    replies_block = $("#replies_block_comment_" + comment_id)

                    if (replies_block.is(":visible")) {
                        $("#hide_replies_comment_" + comment_id).text('Показать ответы');
                    }

                    else {
                        $("#hide_replies_comment_" + comment_id).text('Скрыть ответы');
                    }
                    replies_block.slideToggle("fast");

                }
            );


        </script>

    </div>
@endsection
