@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Новые заявки</h1>
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
                    <input class="form-control" id="participants_input" type="text" placeholder="Поиск...">
                </div>
                <style>
                    .row_hover:hover {
                        cursor: pointer;
                    }
                </style>
                <div class="card-body p-0">
                    <table id="participants_table" class="table table-bordered table-hover">
                        <thead>

                            <tr>
                                <th>Статус</th>
                                <th>Время создания</th>
                                <th>Автор</th>
                                <th>Псевдоним</th>
                                <th>Email</th>
                                <th>Страниц</th>
                                <th>Экземпляров</th>
                                <th>Промокод</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($new_participations as $participation)
                        <tr class="row_hover" onclick="document.location = '{{route('user_participation', ['participation_id' => $participation['id']])}}';">
                            <td style="text-align: center;">
                                <i data-toggle="tooltip" data-placement="top"
                                   title="{{$participation->pat_status['pat_status_title']}}"
                                   class="fas
                                       @if ($participation['pat_status_id'] == 1) fa-glass-cheers
                                       @elseif ($participation['pat_status_id'] == 2) fa-comments-dollar
                                       @elseif ($participation['pat_status_id'] == 3) fa-check-circle
                                       @elseif ($participation['pat_status_id'] == 4) fa-comments-dollar
                                       @elseif ($participation['pat_status_id'] == 9) fa-edit
                                       @endif
                                       "></i>
                            </td>

                            <td style="text-align: center;">
                                {{substr($participation['created_at'],0,16)}}
                            </td>

                            <td style="text-align: center;">
                                {{$participation['name']}} {{$participation['surname']}}
                            </td>
                            <td style="text-align: center;">
                                {{$participation['nickname']}}
                            </td>
                            <td style="text-align: center;">
                                {{$participation->user['email']}}
                            </td>
                            <td style="text-align: center;">
                                {{$participation['pages']}}
                            </td>
                            <td style="text-align: center;">
                                {{$participation->printorder['books_needed'] ?? 'Печать не нужна'}}
                            </td>
                            <td style="text-align: center;">
                                {{$participation['promocode']}}
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
                            $("#participants_table tr").filter(function () {
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
