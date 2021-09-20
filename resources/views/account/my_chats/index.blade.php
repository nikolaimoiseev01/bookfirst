@extends('layouts.app')

@section('page-tab-title')
    Мои вопросы
@endsection

@section('page-style')

@endsection


@section('page-title')
    <div class="account-header">
        <h1 id="page_title">Мои вопросы</h1>
        <a style="box-shadow: none;" href="{{route('chat_create','Общий вопрос')}}" class="button fast-load">Создать общий вопрос</a>
        <a class="link fast-load" href="{{route('archive_chats')}}">Закрытые вопросы</a>
    </div>
@endsection

@section('content')

    @if (count($chats_check) === 0)
        <div class="no-books-yet">
            <h1 style="line-height: 45px;">На данный момент активные чаты отсутствуют. Обращаем внимание, что общение по вопросам конкретного начатого издания ведется на странице конкретного издания. Создавать отдельный чат для этого не нужно.</h1>
        </div>
    @endif
  @livewire('my-chats',['chat_group' => 1])
@endsection
