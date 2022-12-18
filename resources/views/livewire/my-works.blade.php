<div wire:ignore>

    <div class="element-wrap">
        <style>
            .el-button-wrap svg {
                height: 20px;
            }

            .el-button-wrap {
                flex-direction: column;
                justify-content: space-around;
            }
        </style>
        @if ($work_input_search <> 'no_search' & count($works) == 0)
            <p>По запросу <i>"{{$work_input_search}}"</i> произведений не найдено</p>
        @endif
        {{App::setLocale('ru')}}
        @foreach($works as $work)
            <div class="container">
                <div class="el-desc">
                    <a href="{{route('social.work_page', $work['id'])}}"><span>{{Str::limit($work['title'], 30)}}</span></a>
                    <p>Опубликовано: {{ Date::parse($work['created_at'])->format('j F') }}</p>
                </div>

                <div class="el-button-wrap">
                    <a href="{{route('work.edit', $work->id)}}">
                         <span class="tooltip" title="Редактировать">
                           <svg id="Слой_1" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 401 398.98">
                            <path
                                d="M370.11,251.91a10,10,0,0,0-10,10v88.68a30,30,0,0,1-30,30H49.93a30,30,0,0,1-30-30V90.32a30,30,0,0,1,30-30h88.68a10,10,0,1,0,0-20H49.93A50,50,0,0,0,0,90.32V350.57A50,50,0,0,0,49.93,400.5H330.16a50,50,0,0,0,49.93-49.93V261.89A10,10,0,0,0,370.11,251.91Z"
                                transform="translate(0 -1.52)"/>
                            <path
                                d="M376.14,14.68a45,45,0,0,0-63.56,0L134.41,192.86a10,10,0,0,0-2.57,4.39l-23.43,84.59a10,10,0,0,0,12.29,12.3l84.59-23.44a10,10,0,0,0,4.4-2.56L387.86,90a45,45,0,0,0,0-63.56Zm-220,184.67L302,53.52l47,47L203.19,246.38Zm-9.4,18.85,37.58,37.58-52,14.39Zm227-142.36-10.6,10.59-47-47,10.6-10.59a25,25,0,0,1,35.3,0l11.73,11.71A25,25,0,0,1,373.74,75.84Z"
                                transform="translate(0 -1.52)"/>
                        </svg>
                       </span>
                    </a>
                    <a wire:click.prevent="delete_confirm({{$work->id}})">
                          <span class="tooltip" title="Удалить">
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
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    {{ $works->links() }}

</div>
