@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Наши пользователи</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="card">


                    <div class="card-header">
                        <input class="form-control" id="users_input" type="text" placeholder="Поиск...">
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
                            <th style="text-align: center;width:1%;">
                                ID
                            </th>
                            <th style="text-align: center;">
                                ФИО
                            </th>
                            <th style="text-align: center;">
                                Псевдоним
                            </th>
                            <th style="text-align: center;">
                                Email
                            </th>
                            <th style="text-align: center;">
                                Работ загружено
                            </th>


                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)

                            <tr class="row_hover" onclick="document.location = '{{route('user_page', $user['id'])}}';">
                                <td style="text-align: center;">
                                    {{$user['id']}}
                                </td>
                                <td style="text-align: center;">
                                    {{$user['name']}} {{$user['surname']}}
                                </td>
                                <td style="text-align: center;">
                                    {{$user['nickname']}}
                                </td>
                                <td style="text-align: center;">
                                    {{$user['email']}}
                                </td>

                                <td style="text-align: center;">
                                    {{count($user->work)}}
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
