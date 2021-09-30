@extends('layouts.app')

@section('page-tab-title')
    Чат: {{$chat['title']}}
@endsection


@section('page-style')
    @livewireStyles
@endsection

@section('page-title')
    <div class="account-header">
        <h1>Обсуждение. {{$chat['title']}}</h1>
    </div>
@endsection

@section('content')
    @if ($chat['collection_id'] > 0)
    <a href="/myaccount/collections/{{$chat['collection_id']}}/participation/{{\App\Models\Participation::where('collection_id', $chat['collection_id'])->where('user_id', Auth::user()->id)->value('id')}}" style="margin-bottom: 20px;" class="button">На страницу моего участия</a>
    @endif

    @if ($chat['own_book_id'] > 0)
        <a href="/myaccount/mybooks/{{$chat['own_book_id']}}/book_page" style="margin-bottom: 20px;" class="button">Страница издания книги</a>
    @endif

    <div class="chat">
        <div class="container">
            @livewire('chat',['chat_id'=>$chat['id']])
        </div>
    </div>
    <a style="display: none" id="back" href="/myaccount/chats" class="fast-load">Кнопка назад</a>
@endsection

@section('page-js')

@endsection

