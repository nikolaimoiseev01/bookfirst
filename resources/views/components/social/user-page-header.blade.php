<div class="user_header_block_wrap">

    <div class="general_info_wrap @if ($awards->count() > 0) columned @endif">

        <div class="container left_wrap">

            <div style="display: none;" id="modal_user_avatar" class="cus-modal-container">
                <img style="width: 100%;" src="{{$user['avatar']  ?? '/img/avatars/default_avatar.svg'}}">
            </div>

            <div class="pc_avatar avatar_wrap">
                <img data-for-modal="modal_user_avatar" class="show_modal"
                     src="{{($user['avatar_cropped'] ?? '/img/avatars/default_avatar.svg')}}" alt="user_avatar">
            </div>


            <div class="more_options_wrap">
                <img data-for-modal="modal_user_actions" class="show_modal"
                     src="/img/social/more_user_actions.svg" alt="">

                <div style="display:none;" id="modal_user_actions" class="cus-modal-container">
                    <h3>Дополнительные действия</h3>
                    <div class="modal_user_more_actions">
                        <a href="{{route('chat_create',($user['nickname']) ? 'Жалоба на пользователя ' . $user['nickname'] : 'Жалоба на пользователя ' . $user['name'] . ' ' . $user['surname'])}}">
                            <img src="/img/danger-alert.svg" alt="">
                            <p>Пожаловаться</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="info_wrap">

                <div class="title_wrap">
                    <div class="avatar_wrap mobile_avatar">
                        <img data-for-modal="modal_user_avatar" class="show_modal"
                             src="{{($user['avatar_cropped'] ?? '/img/avatars/default_avatar.svg')}}" alt="user_avatar">
                    </div>
                    <div class="text_wrap">
                        <a href="{{route('social.user_page', $user['id'])}}">
                            <h4>{{prefer_name($user['name'], $user['surname'], $user['nickname'])}}</h4>
                        </a>
                        @if(Cache::has('is_online' . $user->id))
                            <p class="user_status online">В сети</p>
                        @else
                            <p class="user_status offline"> Не в сети</p>
                        @endif
                    </div>

                </div>

                @livewire('social.user-header-buttons.buttons', ['user' => $user])

            </div>
        </div>

        <div class="stats_wrap">
            <div class="container">
                <img src="/img/social/user_stats_subscribers_icon.svg" alt="">
                <h4>{{count($user_stat_readers)}}</h4>
                <p>Читателей</p>
            </div>

            <div class="container">
                <img src="/img/social/user_stats_subscribed_icon.svg" alt="">
                <h4>{{count($user_stat_reads)}}</h4>
                <p>Читает</p>
            </div>

            <div class="container">
                <img src="/img/social/user_stats_work_icon.svg" alt="">
                <h4>{{count($works)}}</h4>
                <p>Работ</p>
            </div>

            <div class="container">
                <img src="/img/social/user_stats_star_icon.svg" alt="">
                <h4>{{count($awards)}}</h4>
                <p>Наград</p>
            </div>
        </div>

    </div>

    @if ($awards->count() > 0)
        <div class="container awards_block_wrap">
            <div class="header_wrap">
                <h2>Награды</h2>
                @if(count($awards) > 3)
                    <a data-for-modal="modal_user_awards_all" class="show_modal link_social">
                        Все награды ({{$awards->count()}})</a>
                @endif
            </div>
            <div class="awards_wrap">
                @foreach($awards->take(3) as $award)
                    <div class="award_wrap">
                        <img src="{{$award->award_type['picture']}}" alt="">
                        <p>{{$award->award_type['name']}}</p>
                    </div>
                @endforeach
            </div>
            {{-- Модальное окно, если много наград--}}
            <div style="display:none;" id="modal_user_awards_all" class="cus-modal-container">
                <div class="awards_wrap">
                    @foreach($awards as $award)
                        <div class="award_wrap">
                            <img src="{{$award->award_type['picture']}}" alt="">
                            <p>{{$award->award_type['name']}}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

</div>
