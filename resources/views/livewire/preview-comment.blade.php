<div style="height: 100%; display: flex; flex-direction: column;">

    <div class="comments-wrap">
        @if (count($comments) == 0)
            <p style="height: 100%; display: flex; align-items: center; justify-content: center; color: #bcbcbc; font-size: 30px;">
                Вы еще не указывали исправления
            </p>
        @endif
        <div class="messages">

            @foreach($comments as $comment)
                <div style="position: relative; margin-top: 30px; margin-bottom:20px;" class="message">
                    @if($comment['status_done'] === 0)
                    <div wire:click.prevent="delete_confirm({{$comment['id']}})" class="remove-work-wrap">
                        <a><img src="/img/cancel.svg"></a>
                    </div>
                    @endif
                    <div style="background: @if($comment['status_done'] === 0) #acacac @else #47AF98 @endif"
                         class="message-wrap">
                        @if ($comment_type === 'inside')
                            Страница {{$comment['page']}}:
                        @endif
                        {{$comment['text']}}
                    </div>
                    <p style="margin-right: 5px; margin-top: -5px;font-size: 17px; float:right;">
                        Статус: @if($comment['status_done'] === 0) выполняется @else учтено @endif</p>
                </div>
            @endforeach
        </div>

        <form
            {{--            id="add_preview_comment_form"--}}
            wire:submit.prevent="add_preview_comment(Object.fromEntries(new FormData($event.target)))"
            enctype="multipart/form-data">
            @csrf
            <div style="margin-top: 10px; " class="input-block">
                @if ($comment_type === 'inside')
                    <input
                        style="text-align: center; max-width: 60px;height: 60px;border-radius: 10px 0 0 10px; border-right:none;"
                        type="number" wire:model="page" name="page" placeholder="Стр." id="page" required="">
                @endif
                <textarea
                    wire:model="text"
                    style="height: 60px;
                    @if ($comment_type === 'cover')
                        border-radius: 10px 0 0 10px;
                    @elseif ($comment_type === 'inside')
                        border-radius: 0;
                    @endif
                        border-right: none;"
                    placeholder="Описание исправления" name="text" required type="text"></textarea>
                <div class="send-wrap">
                    <button style="height: 60px;" type="submit">
                    <span class="tooltip" title="Отправить">
                    <svg id="Capa_1" data-name="Capa 1"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 512 512">
                        <path
                            d="M507.61,4.39a15,15,0,0,0-16.18-3.32l-482,192.8a15,15,0,0,0-1,27.43l190.07,92.18L290.7,503.54A15,15,0,0,0,304.2,512h.53a15,15,0,0,0,13.4-9.42l192.8-482A15,15,0,0,0,507.61,4.39ZM52.09,209.12l382.63-153-228,228ZM302.88,459.91l-75-154.6,228-228Z"
                            transform="translate(0 0)"/>
                    </svg>
                </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if ($preview_comment_type == 'own_book')
        @if ($comment_type === 'inside')
            <div style="display: flex; text-align: end; padding: 15px 0 0 0; justify-content: space-between;">
                <a wire:click.prevent="change_inside_status(3)" class="button">Отправить на исправление</a>
                <a wire:click.prevent="change_inside_status(4)" style="margin-left: 16px;" href="" class="button">Утвердить
                    макет</a>
            </div>
        @endif

        @if ($comment_type === 'cover')
            <div style="display: flex; text-align: end; padding: 15px 0 0 0; justify-content: space-between;">
                <a wire:click.prevent="change_cover_status(3)" class="button">Отправить на исправление</a>
                <a wire:click.prevent="change_cover_status(4)" style="margin-left: 16px;" href="" class="button">Утвердить
                    обложку</a>
            </div>
        @endif
    @endif
</div>
