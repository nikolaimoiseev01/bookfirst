@extends('layouts.app')

@section('page-tab-title')
    Мои вопросы
@endsection

@section('page-style')
@endsection


@section('page-title')
    <div class="account-header">
        <h1 id="page_title">Мои вопросы</h1>
        <div class="buttons_wrap">
            <a href="{{route('help_account')}}" class="button fast-load">Инструкция работы с
                платформой</a>
            <a href="{{route('chat_create','Общий вопрос')}}" class="button fast-load">Создать
                общий вопрос</a>

            <a class="link fast-load" href="{{route('archive_chats')}}">Закрытые вопросы</a>
        </div>

    </div>
@endsection

@section('content')

    @livewire('account.chat.chats-block', ['new_chat_user_id' => $new_user_id])

@endsection
