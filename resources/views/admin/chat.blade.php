@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="mb-3">Чат с автором:
                @if($chat['user_created'] <> 2)
                    <a href="{{route('user_page', \App\Models\User::where('id', $chat['user_created'])->value('id'))}}">
                    {{\App\Models\User::where('id', $chat['user_created'])->value('name')}}
                    {{\App\Models\User::where('id', $chat['user_created'])->value('surname')}}
                    </a>
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
            <div class="tab-pane" id="chat">
                    <div class="mb-3 d-flex align-items-center">
                        <h4 style="margin-bottom: 0 !important;" class="">Статус чата:
                            <i id="change_chat_status_text">{{$chat->chat_status['status']}}</i>
                        </h4>
                        <div style="display: none" id="change_chat_status_form_wrap">
                            <form class="d-flex ml-3" style=" align-items: center;"
                                  action="{{ route('change_chat_status', $chat['id']) }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                            >
                                @csrf

                                <select id="chat_status_id" class="form-control" name="chat_status_id">
                                    @foreach($chat_statuses as $chat_status)
                                        <option @if($chat['chat_status_id'] == $chat_status['id']) selected @endif value="{{$chat_status['id']}}">{{$chat_status['status']}}</option>
                                    @endforeach
                                </select>

                                <button id="btn-submit" type="submit"
                                        style="height: fit-content; max-height: 30px; max-width:150px;"
                                        data-status-from="{{$chat->chat_status['status']}}"
                                        class="change_status ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                >
                                    Сохранить
                                </button>
                            </form>
                        </div>
                        <button style="border: none; width: auto; padding: 3px 10px;max-width:150px"
                                data-form="change_chat_status" type="button"
                                class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                        >
                            <i style="font-size: 20px;" class="fa fa-edit"></i>

                        </button>
                    </div>
            <div class="card">
                <div class="d-flex align-items-center bg-gradient-info card-header">
                    <h1 style="font-size: 25px;" class="card-title">{{$chat['title']}}</h1>
                    <h1 style="font-size: 20px;" class="ml-auto card-title">(Создан: {{ Date::parse($chat['created_at'])->format('j F Y | H:i') }})</h1>
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
