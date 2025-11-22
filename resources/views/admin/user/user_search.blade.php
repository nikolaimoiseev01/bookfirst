@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="align-items-center row mb-2">
                <h1 class="m-0">По запросу "<span style="color: #007bff">{{$user_input}}</span>" найдено пользователей: {{$users_amt}}</h1>

                <a class="ml-auto btn btn-outline-info" href="{{route('user.index')}}">Все пользователи</a>
                <style>
                    .page-link, .page-item {
                        display: flex;
                        height: 38px;
                    }

                    .pagination {
                        margin: auto;
                    }

                    nav {
                        margin-left: auto;
                    }
                </style>
                {{ $users->links() }}
            </div><!-- /.row -->

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="card">


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
                                ID
                            </th>
                            <th scope="col" style="text-align: center;">
                                ФИО
                            </th>
                            <th scope="col" style="text-align: center;">
                                Псевдоним
                            </th>
                            <th scope="col" style="text-align: center;">
                                Email
                            </th>
                            <th scope="col" style="text-align: center;">
                                Время регистрации
                            </th>
                            <th scope="col" style="text-align: center;">
                                Работ загружено
                            </th>
                            <th scope="col" style="text-align: center;">
                                Reg_utm_source
                            </th>


                        </tr>
                        </thead>
                        <tbody>
                        {{App::setLocale('ru')}}
                        @foreach($users as $user)

                            <tr class="row_hover" onclick="document.location = '{{route('user_page', $user['id'])}}';">
                                <td scope="row" data-label="ID" style="text-align: center;">
                                    {{$user['id']}}
                                </td>
                                <td data-label="ФИО" style="text-align: center;">
                                    {{$user['name']}} {{$user['surname']}}
                                </td>
                                <td data-label="Псевдоним" style="text-align: center;">
                                    {{$user['nickname']}}
                                </td>
                                <td data-label="Email" style="text-align: center;">
                                    {{$user['email']}}
                                </td>

                                <td data-label="Дата/время" style="text-align: center;">
                                    {{ Date::parse($user['created_at'])->addHours(3)->format('j F H:i') }}
                                </td>

                                <td data-label="Работ загружено" style="text-align: center;">
                                    {{count($user->work)}}
                                </td>
                                <td data-label="UTM Source" style="text-align: center;">
                                    {{$user['reg_utm_source']}}
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
    {{--    <script>--}}
    {{--        $(document).ready(function () {--}}
    {{--            $("#users_input").on("keyup", function () {--}}
    {{--                var value = $(this).val().toLowerCase();--}}
    {{--                $("#users_input tr").filter(function () {--}}
    {{--                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)--}}
    {{--                });--}}
    {{--            });--}}
    {{--        });--}}
    {{--    </script>--}}
    <!-- /.content -->
@endsection
