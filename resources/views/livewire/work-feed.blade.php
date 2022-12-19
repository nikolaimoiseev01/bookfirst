<div>
    <script
        type="text/javascript"
        src="https://vk.com/js/api/share.js?95"
        charset="windows-1251"
    ></script>

    @if($all_works_num > 0)
        <div style="display: flex; margin-top: 20px; flex-wrap: wrap;" class="filter_works_block">

            <div class="cus-dropdown-wrap">
            <span class="cus-dropdown">
                        <input type="radio" name="filter_topic" value="work_topic_0"
                               checked="checked"
                               id="work_topic_0">
                        <label for="work_topic_0">Все темы</label>


                @foreach($work_topics as $work_topic)
                    <input type="radio" name="filter_topic" value="work_topic_{{$work_topic['id']}}"
                           id="work_topic_{{$work_topic['id']}}">
                    <label for="work_topic_{{$work_topic['id']}}">{{$work_topic['name']}}</label>
                @endforeach
            </span>
            </div>


            <div wire:ignore class="cus-dropdown-wrap">
            <span style="min-width: 200px;" class="cus-dropdown">
                        <input type="radio" name="sort_works_by" value="created_at"
                               checked="checked"
                               id="created_at">
                        <label data-name="sort_works_by" for="created_at">По дате</label>

                                <input type="radio" name="sort_works_by" value="work_like_count"

                                       id="work_like_count">
                        <label for="work_like_count">По популярности</label>
            </span>
            </div>

            @if(!$user_page_flag && Auth::user())
                <div wire:ignore class="cus-dropdown-wrap">
            <span style="min-width: 200px;" class="cus-dropdown">
                        <input type="radio" name="authors_filter" value="all_authors"
                               checked="checked"
                               id="all_authors">
                        <label data-name="authors_filter" for="all_authors">Все авторы</label>

                                <input type="radio" name="authors_filter" value="sub_only_authors"

                                       id="sub_only_authors">
                        <label for="sub_only_authors">Только избранные</label>
            </span>
                </div>
            @endif

            <div id="work_loader" style="display: none; position:relative;">
                <span class="button--loading"></span>
            </div>


        </div>
    @endif

    @foreach($works as $work)
        <div wire:key="work_{{ $loop->index }}" class="container user_work_block">
            <div style="     margin-bottom: 15px; display: flex; align-items: center;     justify-content: space-between; flex-wrap: wrap;">
                <div style="flex-wrap: wrap;    display: flex;    align-items: center;">
                    @if(!$user_page_flag)
                        <div>
                            <img data-for-modal="modal_user_avatar_{{$work->user['id']}}"
                                 style="margin-right: 10px; width:40px; height: 40px;" class="show_modal user_avatar"
                                 src="{{($work->user['avatar'] ?? '/img/avatars/default_avatar.svg')}}"
                                 alt="user_avatar">
                        </div>

                        <div style="display: none;" id="modal_user_avatar_{{$work->user['id']}}"
                             class="cus-modal-container">
                            <img style="    width: 100%;"
                                 src="{{$work->user['avatar_cropped']  ?? '/img/avatars/default_avatar.svg'}}">
                        </div>

                        <a href="{{route('social.user_page', ($work->user['id']))}}" target="_blank"
                           style="display: flex; margin-right: 10px;" class="link_social">
                            <h3 style="margin: 0;">
                                {{Str::limit(Str::ucfirst(Str::lower(($work->user['nickname']) ? $work->user['nickname'] : $work->user['name'] . ' ' . $work->user['surname'])), 21, '...')}}:
                            </h3>
                        </a>
                    @endif
                    <a style="width: fit-content; display:flex;"
                       href="{{route('social.work_page', $work['id'])}}">
                        <h3 style="margin: 0;">{{Str::limit(Str::ucfirst(Str::lower($work['title'])), 20, '...')}}</h3>
                    </a>
                </div>
                <p style="font-size: 18px; color:var(--grey_font)">
                    {{$work->work_topic['name']}}
                </p>
            </div>
            <div class="user_work_text_wrap">
                <div id="work_text_{{$work['id']}}" class="user_work_text">
                    <p>{!! nl2br($work['text']) !!}</p>
                    @if($work['picture_cropped'])
                        <br>
                        <img style="margin-top: 20px; max-width: 250px;" src="{{$work['picture_cropped']}}" alt="">
                    @endif
                </div>
                <a id="show_full_work_{{$work['id']}}" class="show_full_work link_social">Читать
                    полностью</a>

            </div>
            <div style="    flex-wrap: wrap; margin-top: 20px; display: flex; justify-content: space-between">
                <div class="user_work_buttons">
                    <livewire:like-button :work_id="$work->id"
                                          :wire:key="'like_live' . $work->id"
{{--                                          :wire:key= rand(0,10000000000)--}}
                    >
{{--                        @livewire('like-button', ['work_id' => $work->id], key($loop->index))--}}

                        <a style="display:flex;" href="{{route('social.work_page', $work['id'])}}">
                            <i style="color: var(--grey_font);  margin-left: 20px;"
                               class="fa-regular fa-comment"></i>
                            <span
                                style="font-size: 20px; margin-left: 6px;">{{$work->work_comment->count('id') ?? 0}}</span>
                        </a>

                        <div class="share_block">
                        <span class="tooltip" title="Поделиться">
                        <span style="display:flex;" href="">
                            <img style="width: 25px;" src="/img/share_icon.svg" alt="">
                        </span>
                            </span>
                            <div class="container share_options_block">
                                <div class="vk_share" data-title="{{$work['title']}}"
                                     data-url="{{route('social.work_page', $work['id'])}}" id="vk_share_{{$work['id']}}"
                                     style="height: 23px; margin-right: 10px;">

                                </div>
                                <div class="ok_shareWidget" style="height: 23px;" data-title="{{$work['title']}}"
                                     data-url="{{route('social.work_page', $work['id'])}}"
                                     id="ok_shareWidget_{{$work['id']}}">
                                    <a href="https://connect.ok.ru/offer?url={{route('social.work_page', $work['id'])}}&title={{$work['title']}}&imageUrl={{$work['picture']}}"
                                       target="_blank">
                                        <img src="/img/ok_icon_color.svg" alt="">
                                    </a>
                                </div>
                            </div>

                        </div>

                </div>

                <p style="font-size: 18px; color:var(--grey_font)">
                    {{App::setLocale('ru')}}{{ Date::parse($work['created_at'])->format('j F Y') }}
                </p>
            </div>
        </div>
    @endforeach
    <style>
        .button--loading::after {
            border-top-color: var(--social_blue) !important;
        }
    </style>

    @if($show_load_more)
        <div style="display: flex;     flex-wrap: wrap;">
            <p style="margin-right: 20px;">Загружено {{$works_amt}} из {{$works_num}}.</p>
            <a id="load_more" wire:click="load_more" class="link_social">Загрузить еще</a>
        </div>


    @elseif (count($works) > 0)
        <p style="margin-top: 20px; color: var(--grey_font)">Все работы ({{count($works)}}) загружены</p>
    @elseif ($all_works_num > 0)
        <p style="margin-top: 20px; color: var(--grey_font)">По заданным фильтрам работ не найдено.</p>
    @else
        <p style="    margin-right: 5px; margin-top: 20px; color: var(--grey_font)">Пока работ загружено не было, но всё еще впереди</p>
        <i style="font-size: 22px; color: var(--social_blue)" class="fa-regular fa-face-smile"></i>
    @endif


    <script>

        function make_show_more_links() {
            function isOverflown(element) {
                return element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth;
            }


            var els = document.getElementsByClassName('user_work_text');
            for (var i = 0; i < els.length; i++) {
                var el = els[i];
                var id = $(el).attr('id').substring(10, 5000);


                if (!isOverflown(el) && $('#show_full_work_' + id).text() != 'Свернуть') {
                    work_id = $(el).attr('id').substring(10, 5000);
                    $('#show_full_work_' + work_id).hide();
                }
            }


            $('.show_full_work').click(function () {
                work_id = $(this).attr('id').substring(15, 5000);

                if (parseInt($('#work_text_' + work_id).css('max-height')) === 240) {
                    $('#work_text_' + work_id).css('max-height', $('#work_text_' + work_id)[0].scrollHeight + 'px')
                    $('#show_full_work_' + work_id).text('Свернуть')
                } else {
                    $('#work_text_' + work_id).css('max-height', '240px')
                    $('#show_full_work_' + work_id).text('Читать полностью')
                }

            })

        }

        make_show_more_links()

        $('.vk_share').each(function () {
            id = $(this).attr('id');
            title = 'Мне понравилось произведение: "' + $(this).attr('data-title') + '"!';
            url = $(this).attr('data-url');


            object = VK.Share.button({
                    url: url,
                    title: 'Мне понравилось произведение: "' + title + '"!'
                },
                {
                    type: "custom",
                    text: '<img width="23" src="https://vk.com/images/share_32_2x.png" />'
                })

            $(this).html(object);
        });



        function livewire_trig() {


        $("[name='sort_works_by']").change(function () {
            $('.user_work_text_wrap').removeAttr('wire:ignore');
            $('#work_loader').show();
        @this.set("work_sort_by", $('input[name=sort_works_by]:checked').val());
            Livewire.emit('make_sorting')

        })


        $("[name='filter_topic']").change(function () {
            $('.user_work_text_wrap').removeAttr('wire:ignore');
            $('#work_loader').show();
        @this.set("work_topic", $('input[name=filter_topic]:checked').val());
            Livewire.emit('filter_topic')
        })


        $("[name='authors_filter']").change(function () {
            $('.user_work_text_wrap').removeAttr('wire:ignore');
            $('#work_loader').show();
             @this.set("authors_filter", $('input[name=authors_filter]:checked').val());
            Livewire.emit('author_filter')
        })
        }
        livewire_trig();

        document.addEventListener('livewire:update', function () {
            make_show_more_links();
            show_modal_function();
            like_icon_animation_function();
            livewire_trig();
            make_log_check();
        })


    </script>

</div>
