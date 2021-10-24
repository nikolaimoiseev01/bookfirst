<div>
    <div class="element-wrap">
        {{App::setLocale('ru')}}
        @foreach($chats as $chat)

            <div style="box-shadow:
                 @if($chat['chat_status_id'] === '1' || $chat['chat_status_id'] === '4')
                0 0 6px 4px rgb(217 186 19 / 20%)
                 @elseif($chat['chat_status_id'] === '2')
                0 0 6px 4px rgb(54 193 76 / 25%)
            @endif; padding:15px;position:relative" class="container-hover container">
                <div class="el-desc">
                    <span>{{Str::limit($chat['title'], 30)}}</span>
                    <p>Статус: {{$chat->chat_status['status']}}</p>
                    <p>Создан: {{ Date::parse($chat['created_at'])->format('j F Y') }}</p>
                </div>
                <a style="position:absolute; width:100%; top: 0; left: 0; height: 100%; text-decoration: none;"
                   class="fast-load" href="{{route('chat',$chat['id'])}}"></a>
                @if ($chat['chat_status_id'] != 3)
                    <div wire:click.prevent="delete_confirm({{$chat['id']}})" class="el-button-wrap">
                            <span class="tooltip"
                                  title="Закрыть вопрос">
                                <svg id="#delete_chat" data-name="Слой 1"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 346.8 427">
                                    <path
                                        d="M272.4,154.7a10,10,0,0,0-10,10v189a10,10,0,0,0,20,0v-189A10,10,0,0,0,272.4,154.7Z"
                                        transform="translate(-40 0)"/>
                                    <path
                                        d="M154.4,154.7a10,10,0,0,0-10,10v189a10,10,0,0,0,20,0v-189A10,10,0,0,0,154.4,154.7Z"
                                        transform="translate(-40 0)"/>
                                    <path
                                        d="M68.4,127.12V373.5c0,14.56,5.34,28.24,14.67,38.05A49.21,49.21,0,0,0,118.8,427H308a49.21,49.21,0,0,0,35.73-15.45c9.33-9.81,14.67-23.49,14.67-38.05V127.12A38.2,38.2,0,0,0,348.6,52H297.4V39.5A39.28,39.28,0,0,0,257.8,0H169a39.28,39.28,0,0,0-39.6,39.5V52H78.2a38.2,38.2,0,0,0-9.8,75.12ZM308,407H118.8c-17.1,0-30.4-14.69-30.4-33.5V128h250V373.5C338.4,392.31,325.1,407,308,407ZM149.4,39.5A19.26,19.26,0,0,1,169,20h88.8a19.28,19.28,0,0,1,19.6,19.5V52h-128ZM78.2,72H348.6a18,18,0,0,1,0,36H78.2a18,18,0,1,1,0-36Z"
                                        transform="translate(-40 0)"/>
                                    <path
                                        d="M213.4,154.7a10,10,0,0,0-10,10v189a10,10,0,0,0,20,0v-189A10,10,0,0,0,213.4,154.7Z"
                                        transform="translate(-40 0)"/>
                                </svg>
                            </span>
                    </div>
                @endif
            </div>

        @endforeach
    </div>
</div>
