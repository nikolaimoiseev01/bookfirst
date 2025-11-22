<div class="work_feed_wrap">
    <script
        type="text/javascript"
        src="https://vk.com/js/api/share.js?95"
        charset="windows-1251"
    ></script>

    @if(count($works_orig) > 0)
        <div class="filter_works_block">

            <x-input.dropdown class="custom_dropdown_wrap social"
                              model="work_topic"
                              :options="$work_topics"
                              alltext="Все темы"
                              :default="null"
            ></x-input.dropdown>

            <x-input.dropdown class="custom_dropdown_wrap social"
                              model="sort_option"
                              :options="$sort_options"
                              alltext="null"
                              default="1"
            ></x-input.dropdown>


            @if(!$user_page_flag && Auth::user())
                <x-input.dropdown class="custom_dropdown_wrap social"
                                  model="author_filter"
                                  :options="$author_filters"
                                  alltext="null"
                                  default="1"
                ></x-input.dropdown>
            @endif

            <div wire:loading>
                <div class="loader loading_process social">
                    <span class="button--loading"></span>
                </div>
            </div>


        </div>
    @endif

    <div class="works_wrap">

        @foreach($works as $work)
            <div wire:key="{{rand()}}_work_{{ $loop->index }}" class="container work_wrap">
                <div class="title_wrap">
                    @if(!$user_page_flag)
                        <img data-for-modal="modal_user_avatar_{{$work->user['id']}}"
                             class="show_modal user_avatar"
                             src="{{($work->user['avatar_cropped'] ?? '/img/avatars/default_avatar.svg')}}"
                             alt="user_avatar">

                        <div style="display: none;" id="modal_user_avatar_{{$work->user['id']}}"
                             class="cus-modal-container">
                            <img style="    width: 100%;"
                                 src="{{$work->user['avatar']  ?? '/img/avatars/default_avatar.svg'}}">
                        </div>

                        <a href="{{route('social.user_page', ($work->user['id']))}}" target="_blank"
                           class="link social">
                            <h3>
                                {{Str::limit(Str::ucfirst(prefer_name($work->user['name'], $work->user['surname'], $work->user['nickname'])), 21, '...')}}
                                :
                            </h3>
                        </a>
                    @endif
                    <a href="{{route('social.work_page', $work['id'])}}">
                        <h3>{{Str::limit(Str::ucfirst(Str::lower($work['title'])), 20, '...')}}</h3>
                    </a>
                </div>
                <div class="user_work_text_wrap">
                    <div id="work_text_{{$work['id']}}" class="user_work_text">
                        <p>{!! nl2br($work['text']) !!}</p>
                        @if($work['picture_cropped'])
                            <br>
                            <img src="{{$work['picture_cropped']}}" alt="">
                        @endif
                    </div>
                    <a id="show_full_work_{{$work['id']}}" class="show_full_work link social">Читать
                        полностью</a>
                </div>


                <div class="buttons_wrap">
                    <livewire:social.like-button :work_id="$work->id"
                                                 wire:key="{{rand()}}_work_{{ $loop->index }}"
                    ></livewire:social.like-button>

                    <a class="comment_button" href="{{route('social.work_page', $work['id'])}}">
                        <span class="fa-regular fa-comment comment_icon"></span>
                        <p>{{$work->work_comment->count('id') ?? 0}}</p>
                    </a>

                    <div class="share_block">
                        <span class="material-symbols-outlined share_icon">share</span>

                        <div class="container share_options_block">
                            <div class="vk_share" data-title="{{$work['title']}}"
                                 data-url="{{route('social.work_page', $work['id'])}}" id="vk_share_{{$work['id']}}">
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


                <p class="topic">
                    {{$work->work_topic['name']}}
                </p>
                <p class="date">
                    {{App::setLocale('ru')}}{{ Date::parse($work['created_at'])->format('j F Y') }}
                </p>
            </div>
        @endforeach
    </div>

    <div class="end_text_wrap">
        @if(count($works_orig) > 0)
            @if(count($works) > 0 && (count($works_orig) > count($works)))
                <p>Загружено {{count($works)}} из {{count($works_orig)}}.</p>
                <a id="load_more" wire:click="load_more" class="link social">Загрузить еще</a>
                <div wire:loading class="loader loading_process social">
                    <span class="button--loading"></span>
                </div>
            @elseif (count($works_orig) == count($works))
                <p>Все работы ({{count($works_orig)}}) загружены</p>
            @else (count($works_orig) > 0)
                <p>По заданным фильтрам работ не найдено.</p>
            @endif
        @else
            <p>Пока работ загружено не было, но всё еще впереди</p>
            <i class="fa-regular fa-face-smile"></i>
        @endif
    </div>


    @push('page-js')
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

                    if (parseInt($('#work_text_' + work_id).css('max-height')) === 250) {
                        $('#work_text_' + work_id).css('max-height', $('#work_text_' + work_id)[0].scrollHeight + 'px')
                        $('#show_full_work_' + work_id).text('Свернуть')
                    } else {
                        $('#work_text_' + work_id).css('max-height', '250px')
                        $('#show_full_work_' + work_id).text('Читать полностью')
                    }

                })

            }

            make_show_more_links()

            function make_vk() {


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
            }
            make_vk()

            //
            document.addEventListener('livewire:update', function () {
                make_show_more_links();
                like_icon_animation_function();
                make_log_check();
                make_vk();
            })


        </script>
    @endpush

</div>
