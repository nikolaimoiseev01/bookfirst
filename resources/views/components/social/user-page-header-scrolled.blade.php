<div class="user_header_scrolled_wrap page_content_wrap">


    <div class="left_wrap">

        <img class="avatar"
             src="{{($user['avatar_cropped'] ?? '/img/avatars/default_avatar.svg')}}" alt="user_avatar">
        <a href="{{route('social.user_page', $user['id'])}}">
            <h4>{{prefer_name($user['name'], $user['surname'], $user['nickname'])}}</h4>
        </a>
        @if(Cache::has('is_online' . $user->id))
            <p class="user_status online">В сети</p>
        @else
            <p class="user_status offline"> Не в сети</p>
        @endif
    </div>

    <div class="buttons_wrap">
        @livewire('social.user-header-buttons.buttons', ['user' => $user])
    </div>


</div>
