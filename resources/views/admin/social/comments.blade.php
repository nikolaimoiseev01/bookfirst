@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="justify-content-between d-flex">
                <h1 class="m-0">Комментарии пользователей ({{$comments->total()}})</h1>
                <style>
                    .page-link, .page-item {
                        display: flex;
                        height: 38px;
                    }
                </style>
                {{ $comments->links() }}
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
                            <th scope="col" style="text-align: center;">К работе</th>
                            <th scope="col" style="text-align: center;">Текст</th>
                            <th scope="col" style="text-align: center;">Создано</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{App::setLocale('ru')}}
                        @foreach($comments as $comment)

                            <tr onclick="window.open('/social/work/{{$comment['work_id']}}')">
                                <td scope="row" data-label="Автор" style="text-align: center;">
                                        <a href="{{route('user_page', $comment['user_id'])}}">{{$comment->user['name']}} {{$comment->user['surname']}}</a>

                                </td>
                                <td data-label="К работе" style="text-align: center;">
                                    {{$comment->work['title']}}
                                </td>
                                <td data-label="текст" style="text-align: center;">
                                    {{$comment['text']}}
                                </td>

                                <td data-label="Update" style="text-align: center;">
                                    {{ Date::parse($comment['updated_at'])->addHours(3)->format('j F H:i') }}
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
