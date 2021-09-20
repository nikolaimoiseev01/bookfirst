@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="mb-3">Чат с автором:
                @if($chat['user_created'] <> 2)
                    {{\App\Models\User::where('id', $chat['user_created'])->value('name')}}
                    {{\App\Models\User::where('id', $chat['user_created'])->value('surname')}}
                @else
                    <a href="{{route('user_page', \App\Models\User::where('id', $chat['user_to'])->value('id'))}}">
                        {{\App\Models\User::where('id', $chat['user_to'])->value('name')}}
                        {{\App\Models\User::where('id', $chat['user_to'])->value('surname')}}
                    </a>
                @endif
            </h1>
            <h4 class="m-0"></h4>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="d-flex align-items-center bg-gradient-info card-header">
                    <h1 style="font-size: 25px;" class="card-title">{{$chat['title']}}</h1>
                    <h1 style="font-size: 20px;" class="ml-auto card-title">(Создан: {{$chat['created_at']}})</h1>
                </div>
                <div id="book_chat" style="width: 100%; max-width: 2000px;" class="chat">
                    <div style="margin: 0; width: 100%; max-width: 2000px;" class="container">
                        @livewire('chat',['chat_id'=>$chat['id']])
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
