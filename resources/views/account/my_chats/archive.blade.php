@extends('layouts.app')

@section('page-tab-title')
    Архивные вопросы
@endsection


@section('page-style')

@endsection


@section('page-title')
    <div class="account-header">
        <h1>Архивные вопросы</h1>
        <a class="button" href="{{route('all_chats')}}">Активные вопросы</a>
    </div>
@endsection
@section('content')

    @if (!$chats)
        <h1 class="no-access">На данный момент архивные чаты отсутствуют</h1>
    @else
        <div class="archive_chats_wrap">
            @foreach($chats as $chat)

                <div class="container chat_wrap">
                        <h4 class="title">{{Str::limit($chat['title'], 30)}}</h4>
                        <p>Создан: {{ Date::parse($chat['created_at'])->format('j F Y') }}</p>
                    <a class="link" href="{{route('chat',$chat['id'])}}">Подробнее</a>
                </div>

            @endforeach
        </div>
    @endif

@endsection
