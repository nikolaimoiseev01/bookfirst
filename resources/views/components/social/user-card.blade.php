<div class="user_card_wrap container">
    <img data-for-modal="modal_user_avatar_{{$user->id}}"
         class="show_modal avatar"
         src="{{($user->avatar_cropped ?? '/img/avatars/default_avatar.svg')}}" alt="user_avatar">

    <a href="{{route('social.user_page', ($user->id))}}" class="link social">
        <h4>
            {{Str::limit(Str::ucfirst(prefer_name($user->name, $user->surname, $user->nickname)), 17, '...')}}
        </h4>
    </a>

    <div class="stat_wrap">
        <div class="tooltip" title="Подписчиков">
            <i class="fa-regular fa-user"></i>
            <p>{{$user->cnt_user_subs}}</p>
        </div>

        <div class="tooltip" title="Работ">
            <img src="/img/small_book.svg" alt="">
            <p>{{$user->cnt_user_works}}</p>
        </div>
        <div class="tooltip" title="Лайков">
            <i class="fa-regular fa-heart"> </i>
            <p>{{$user->cnt_user_likes}}</p>
        </div>
        <div class="tooltip" title="Комментариев">
            <i class="fa-regular fa-comment"></i>
            <p>{{$user->cnt_user_comments}}</p>
        </div>
    </div>
</div>

<div style="display: none;" id="modal_user_avatar_{{$user->id}}"
     class="cus-modal-container">
    <img style="    width: 100%;"
         src="{{$user->avatar  ?? '/img/avatars/default_avatar.svg'}}">
</div>
