@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="justify-content-between d-flex">
                <h1 class="m-0">Наши чаты</h1>
                <style>
                    .page-link, .page-item {
                        display: flex;
                        height: 38px;
                    }
                </style>
                {{ $chats->links() }}
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <style>
        tr:hover {
            cursor: pointer;
        }
    </style>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <input class="form-control" id="participants_input" type="text" placeholder="Поиск...">
                </div>
                <div class="card-body p-0">
                    <table id="participants_table" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col" style="text-align: center;">Автор</th>
                            <th scope="col" style="text-align: center;">Тема</th>
                            <th scope="col" style="text-align: center;">Статус</th>
                            <th scope="col" style="text-align: center;">Последнее сообщение</th>
                            <th scope="col" style="text-align: center;">Последнее изменение</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{App::setLocale('ru')}}
                        @foreach($chats as $chat)

                            <tr
                                @if($chat['chat_status_id'] === '1')
                                style="background: #ffe7e7; color: black;"
                                @endif
                                onclick="document.location = '' +
                                @if ($chat['collection_id'] > 0)
                                @if($chat['user_created'] <> 2)
                                    '/admin_panel/collections/participation/{{\App\Models\Participation::where('user_id', $chat['user_created'])->where('collection_id', $chat['collection_id'])->value('id')}}#chat'
                                @else'/admin_panel/collections/participation/{{\App\Models\Participation::where('user_id', $chat['user_to'])->where('collection_id', $chat['collection_id'])->value('id')}}#chat'
                                @endif

                                @elseif($chat['own_book_id'] > 0)
                                    '/admin_panel/own_books/{{$chat['own_book_id']}}#chat'
                                @else
                                    '{{route('admin_chat', $chat['id'])}}'
                                @endif +
                                    '';">
                                <td scope="row" data-label="Автор" style="text-align: center;">
                                    @if ($chat['user_created'] <> 2)
                                        <a href="{{route('user_page', $chat['user_created'])}}">{{\App\Models\User::where('id', $chat['user_created'])->value('name')}} {{\App\Models\User::where('id', $chat['user_created'])->value('surname')}}</a>
                                    @else
                                        <a href="{{route('user_page', $chat['user_to'])}}">{{\App\Models\User::where('id', $chat['user_to'])->value('name')}} {{\App\Models\User::where('id', $chat['user_to'])->value('surname')}}</a>
                                    @endif

                                </td>
                                <td data-label="Тема" style="text-align: center;">
                                    {{$chat['title']}}
                                </td>
                                <td data-label="Статус" style="text-align: center;">
                                    {{$chat->chat_status['status']}}
                                </td>

                                <td data-label="Last sms" style="text-align: center;">
                                    {{\Illuminate\Support\Str::substr($chat->message->last()['text'] ?? '',0,100)}}
                                </td>

                                <td data-label="Update" style="text-align: center;">
                                    {{ Date::parse($chat['updated_at'])->addHours(3)->format('j F H:i') }}
                                </td>


                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>

                <!-- /.card-body -->

                <script>
                    $(document).ready(function () {
                        $("#participants_input").on("keyup", function () {
                            var value = $(this).val().toLowerCase();
                            $("#participants_table tbody tr").filter(function () {
                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                            });
                        });
                    });
                </script>


            </div>

        </div>

    </section>
    <!-- /.content -->
@endsection
