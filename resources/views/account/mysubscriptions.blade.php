@extends('layouts.app')
@section('page-title')
    <style>
        .user_found_block {
            display: flex;
            flex-wrap: wrap;
        }

        .user_found_block .container {
            align-items: center;
            width: fit-content;
            padding: 15px 10px;
            margin: 10px 30px 10px 0;
            height: fit-content;
        }
    </style>
    <div class="account-header">
        <h1>Избранные авторы</h1>
        @if(count($sub_users) == 0)
            <div class="buttons_wrap">
                <a href="{{route('social.all_works_feed')}}" class="button">Наши авторы</a>
            </div>
        @endif
    </div>

    <div class="user_found_block">
        @foreach($sub_users as $sub_user)
            <div class="container">
                <div style="    display: flex; align-items: center;">
                    <img data-for-modal="modal_user_avatar_{{$sub_user['id']}}"
                         style="margin-right: 10px; width:30px;" class="show_modal user_avatar"
                         src="{{($sub_user['avatar'] ?? '/img/avatars/default_avatar.svg')}}" alt="user_avatar">
                </div>

                <div style="display: none;" id="modal_user_avatar_{{$sub_user['id']}}"
                     class="cus-modal-container">
                    <img style="    width: 100%;"
                         src="{{$sub_user['avatar_cropped']  ?? '/img/avatars/default_avatar.svg'}}">
                </div>

                <a href="{{route('social.user_page', ($sub_user['id']))}}" target="_blank"
                   style="display: flex;" class="link_social">
                    <h3 style="font-size: 30px; margin: 0;">
                        {{Str::limit(Str::ucfirst(Str::lower(($sub_user['nickname']) ? $sub_user['nickname'] : $sub_user['name'] . ' ' . $sub_user['surname'])), 21, '...')}}
                    </h3>
                </a>
            </div>
        @endforeach
    </div>
    <div>
        {{$sub_users->links()}}
    </div>

    @if(count($sub_users) == 0)
        <h4 class="no-access">Вы еще не подписывались на авторов</h4>
    @endif

@endsection
@section('content')
@endsection
