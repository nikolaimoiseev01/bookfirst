<div class="chat_wrap">

    @if (count($comments) == 0)
        <p class="messages_placeholder">
            Вы еще не указывали исправления
        </p>
    @endif
    <div class="messages_wrap">
        @foreach($comments as $comment)
            <div class="message_wrap">
                <div style="background: @if($comment['status_done'] === 0) #acacac @else #47AF98 @endif"
                     class="message_body">
                    @if($comment['status_done'] === 0)
                        <img wire:click.prevent="delete_confirm({{$comment['id']}})" class="remove" src="/img/cancel.svg">
                    @endif
                    @if ($comment_type === 'inside')
                        <p>Стр. {{$comment['page']}}:</p>
                    @endif
                    <p>{{$comment['text']}}</p>
                </div>
                <p class="message_time">
                    Статус: @if($comment['status_done'] === 0) выполняется @else учтено @endif</p>
            </div>
        @endforeach
    </div>

    <div class="preview_input_wrap">
        @if ($comment_type === 'inside')
            <input class="page yellow" type="number" wire:model="page" name="page" placeholder="Стр.">
        @endif
            <x-chat-textarea model="text"
                             placeholder="Введите исправление"
                             attachable="false"
                             sendable="true"></x-chat-textarea>
    </div>

    @if ($preview_comment_type == 'own_book')
        @if ($comment_type === 'inside')
            <div class="preview_buttons_wrap">
                <a wire:click.prevent="change_inside_status(3)" class="button yellow show_preloader_on_click">Отправить на исправление</a>
                <a wire:click.prevent="change_inside_status(4)" class="button show_preloader_on_click">Утвердить
                    макет</a>
            </div>
        @endif

        @if ($comment_type === 'cover')
            <div  class="preview_buttons_wrap">
                <a wire:click.prevent="change_cover_status(3)" class="button yellow show_preloader_on_click">Отправить на исправление</a>
                <a wire:click.prevent="change_cover_status(4)" class="button show_preloader_on_click">Утвердить
                    обложку</a>
            </div>
        @endif
    @endif
</div>
