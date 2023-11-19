<div class="part preview_block_wrap">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}
    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>{{$page_title}}</h2>
            <div class="switch-wrap {{$color}}">
                <input wire:model="chosen_type" type="radio" value="inside" id="chosen_type_inside"
                       name="chosen_type">
                <label for="chosen_type_inside">Внутренний блок</label>

                <input wire:model="chosen_type" checked type="radio" id="chosen_type_cover" value="cover"
                       name="chosen_type">
                <label for="chosen_type_cover">Обложка</label>
            </div>
        </div>
        <div class="info_wrap">
            <div class="text @if($chosen_type == 'cover') cover_info_wrap @endif">
                <div>
                    @if($status_id > 1 && $status_id !== 9)
                        <h2>Статус: <p><b>{{$status_title}}</b></p></h2>
                    @endif
                    {!! $text !!}
                    @if($status_id > 1 && $status_id !== 9 && $status_id !== 99)
                        <a class="button {{$color}} download_preview"
                           href="/{{$chosen_type == 'inside' ? $own_book['inside_file'] : $own_book['cover_2d']}}"
                           download>
                            <span class="material-symbols-outlined">download</span>
                            Скачать макет
                        </a>
                    @endif
                </div>

                @if($chosen_type == 'cover' && $status_id > 1 && $status_id !== 9)
                    <div class="preview_book_wrap">
                        <div class="book">
                            <img
                                alt=""
                                src="/{{$own_book['cover_2d']}}"
                            />
                        </div>
                    </div>
                @endif


            </div>
            <div>
                @if($status_id > 1 && $status_id !== 9)
                    <h2>Исправления:</h2>
                    @if($status_id == 2)
                        @if ($chosen_type === 'inside')
                            @livewire('account.preview-comment',['collection_id' => 0, 'own_book_id' => $own_book->id,
                            'own_book_comment_type' => 'inside'])
                        @elseif ($chosen_type === 'cover')
                            @livewire('account.preview-comment',['collection_id' => 0, 'own_book_id' => $own_book->id,
                            'own_book_comment_type' => 'cover'])
                        @endif
                    @endif
                    @if($status_id !== 2)
                        <div class="chat_wrap disabled">
                            @if (count($comments) == 0)
                                <p class="messages_placeholder">
                                    Вы не указывали исправления.
                                </p>
                            @endif
                            @if (count($comments) > 0)
                                <div class="messages_wrap">
                                    @foreach($comments as $comment)
                                        <div class="message_wrap">
                                            <div
                                                style="background: @if($comment['status_done'] === 0) #acacac @else #47AF98 @endif"
                                                class="message_body">
                                                @if ($chosen_type === 'inside')
                                                    <p>Стр. {{$comment['page']}}:</p>
                                                @endif
                                                <p>{{$comment['text']}}</p>
                                            </div>
                                            <p class="message_time">
                                                Статус: @if($comment['status_done'] === 0) выполняется @else
                                                    учтено @endif</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
