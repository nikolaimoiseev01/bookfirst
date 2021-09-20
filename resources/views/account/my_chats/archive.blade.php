@extends('layouts.app')

@section('page-tab-title')
    Архивные вопросы
@endsection


@section('page-style')

@endsection


@section('page-title')
    <div class="account-header">
        <h1>Архивные вопросы</h1>
        <a class="link fast-load" href="{{route('all_chats')}}">Активные вопросы</a>
    </div>
@endsection
@section('content')

    @if ($chats_check === 0)
        <div class="no-books-yet">
            <h1>На данный момент архивные чаты отсутствуют</h1>
        </div>
    @else
    @livewire('my-chats',['chat_group' => 2])
    @endif

@endsection
