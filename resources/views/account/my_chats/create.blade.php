@extends('layouts.app')

@section('page-tab-title')
    Создание вопроса
@endsection

@section('page-style')
    @livewireStyles
@endsection

@section('page-title')
    <div class="account-header">
        <h1>Создание обсуждения</h1>
    </div>
@endsection

@section('content')
        @livewire('chat-create',['chat_title' => $chat_title, 'collection_id' => $collection_id, 'own_book_id' => 0, 'user_to' => 2])
        <a style="display: none" id="back" href="/myaccount/chats" class="fast-load">Кнопка назад</a>
@endsection

@section('page-js')

@endsection

