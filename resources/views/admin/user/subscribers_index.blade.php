@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="align-items-center row mb-2">
                <h1 class="m-0">Наши подписчики ({{count($subscribers)}})</h1>
                <a class="ml-3 btn btn-outline-info" href="{{route('user.index')}}">Все пользователи</a>
            </div><!-- /.row -->

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="card">


                <div class="d-flex card-header">
                    <input class="form-control" id="users_input" type="text" placeholder="Поиск...">
                    <form id="chat" enctype="multipart/form-data" method="get" action="{{route('subscribers_download')}}" class="ml-3">
                        @csrf
                        <button id="chat_form" style="width:fit-content; position: relative;" class="button btn btn-block bg-gradient-primary">
                            <span class="button__text">Скачать всех</span>
                        </button>
                    </form>
                </div>
                <style>
                    tr:hover {
                        cursor: pointer;
                    }
                </style>
                <div class="card-body p-0">

                    <table id="users_input" class="table table-hover projects">
                        <thead>
                        <tr>
                            <th scope="col" style="text-align: center;width:1%;">
                                Email
                            </th>
                            <th scope="col" style="text-align: center;">
                                Пользователь
                            </th>
                            <th scope="col" style="text-align: center;">
                                Создан
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        {{App::setLocale('ru')}}
                        @foreach($subscribers as $subscriber)

                            <tr class="row_hover">
                                <td scope="row" data-label="Email" style="text-align: center;">
                                    {{$subscriber['email']}}
                                </td>


                                <td data-label="Пользователь" style="text-align: center;">
                                    @if ($subscriber['user_id'])
                                        <a href="{{route('user_page',$subscriber['user_id'])}}">{{$subscriber->user['name']}} {{$subscriber->user['surname']}}</a>
                                    @else
                                        Не регистрировался
                                    @endif
                                </td>

                                <td data-label="Дата/время" style="text-align: center;">
                                    {{ Date::parse($subscriber['created_at'])->addHours(3)->format('j F H:i') }}
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function () {
            $("#users_input").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#users_input tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    <!-- /.content -->
@endsection
