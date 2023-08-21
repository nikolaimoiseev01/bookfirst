<div class="my_works_account_page_wrap">

    <div class="account-header">
        <h1>Мои произведения</h1>

        <div class="buttons_wrap">
            @if(count($works) > 0)
                <x-input.search-bar
                    model="search_input"
                    :input="$search_input"></x-input.search-bar>
            @endif
            <a class="tooltip fast-load button" title="Одно произведение вручную"
               href="{{route('work.create')}}" onclick="make_session()">Добавить</a>

            <a class="tooltip fast-load button" title="Несколько произведений сразу"
               href="{{route('create_from_doc')}}" onclick="make_session()"
            >Добавить файлом</a>

        </div>

    </div>


    @if ($search_input && $works_orig && count($works ?? []) == 0)
        <p>По запросу <i>"{{$search_input}}"</i> произведений не найдено</p>
    @elseif(count($works_orig ?? []) == 0)
        <h1 class="no-access">На данный момент у Вас нет произведений в системе.</h1>
    @endif

    <div class="my_works_wrap">
        @foreach($works as $work)
            <div class="work_wrap container">
                <div class="top_wrap">
                    <a class="link" href="{{route('social.work_page', $work['id'])}}">
                        {{Str::limit($work['title'], 30)}}
                    </a>
                    <p>Опубликовано: {{ Date::parse($work['created_at'])->format('j F') }}</p>
                </div>

                <div class="bottom_wrap">
                    <div class="stat_wrap">
                        <div>
                            <i class="fa-regular like_icon fa-heart"></i>
                            <p>{{$work['work_like_count']}}</p>
                        </div>

                        <div>
                            <i class="fa-regular fa-comment"></i>
                            <p>{{$work['work_comment_count']}}</p>
                        </div>
                    </div>
                    <div class="buttons_wrap">
                        <a href="{{route('work.edit', $work->id)}}" class="tooltip" title="Редактировать">
                            <svg class="edit tooltip" title="Редактировать" id="Слой_1" data-name="Слой 1"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 401 398.98">
                                <path
                                    d="M370.11,251.91a10,10,0,0,0-10,10v88.68a30,30,0,0,1-30,30H49.93a30,30,0,0,1-30-30V90.32a30,30,0,0,1,30-30h88.68a10,10,0,1,0,0-20H49.93A50,50,0,0,0,0,90.32V350.57A50,50,0,0,0,49.93,400.5H330.16a50,50,0,0,0,49.93-49.93V261.89A10,10,0,0,0,370.11,251.91Z"
                                    transform="translate(0 -1.52)"></path>
                                <path
                                    d="M376.14,14.68a45,45,0,0,0-63.56,0L134.41,192.86a10,10,0,0,0-2.57,4.39l-23.43,84.59a10,10,0,0,0,12.29,12.3l84.59-23.44a10,10,0,0,0,4.4-2.56L387.86,90a45,45,0,0,0,0-63.56Zm-220,184.67L302,53.52l47,47L203.19,246.38Zm-9.4,18.85,37.58,37.58-52,14.39Zm227-142.36-10.6,10.59-47-47,10.6-10.59a25,25,0,0,1,35.3,0l11.73,11.71A25,25,0,0,1,373.74,75.84Z"
                                    transform="translate(0 -1.52)"></path>
                            </svg>
                        </a>
                        <a wire:click.prevent="delete_confirm({{$work->id}})" class="tooltip" title="Удалить">
                            <svg class="delete" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 346.8 427">
                                <path
                                    d="M272.4,154.7a10,10,0,0,0-10,10v189a10,10,0,0,0,20,0v-189A10,10,0,0,0,272.4,154.7Z"
                                    transform="translate(-40 0)"></path>
                                <path
                                    d="M154.4,154.7a10,10,0,0,0-10,10v189a10,10,0,0,0,20,0v-189A10,10,0,0,0,154.4,154.7Z"
                                    transform="translate(-40 0)"></path>
                                <path
                                    d="M68.4,127.12V373.5c0,14.56,5.34,28.24,14.67,38.05A49.21,49.21,0,0,0,118.8,427H308a49.21,49.21,0,0,0,35.73-15.45c9.33-9.81,14.67-23.49,14.67-38.05V127.12A38.2,38.2,0,0,0,348.6,52H297.4V39.5A39.28,39.28,0,0,0,257.8,0H169a39.28,39.28,0,0,0-39.6,39.5V52H78.2a38.2,38.2,0,0,0-9.8,75.12ZM308,407H118.8c-17.1,0-30.4-14.69-30.4-33.5V128h250V373.5C338.4,392.31,325.1,407,308,407ZM149.4,39.5A19.26,19.26,0,0,1,169,20h88.8a19.28,19.28,0,0,1,19.6,19.5V52h-128ZM78.2,72H348.6a18,18,0,0,1,0,36H78.2a18,18,0,1,1,0-36Z"
                                    transform="translate(-40 0)"></path>
                                <path
                                    d="M213.4,154.7a10,10,0,0,0-10,10v189a10,10,0,0,0,20,0v-189A10,10,0,0,0,213.4,154.7Z"
                                    transform="translate(-40 0)"></path>
                            </svg>
                        </a>
                    </div>

                </div>

            </div>
        @endforeach
    </div>

    @if(count($works_orig ?? []) > 0)
        <div class="load_more_wrap">
            <p>Загружено {{count($works)}} из {{count($works_orig)}}</p>
            @if(count($works) < count($works_orig))
                <a wire:click.prevent="load_more()" class="link show_preloader_on_click">
                    Еще
                </a>
            @endif
        </div>
    @endif

    {{--        {{ $works->links() }}--}}
</div>
