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
                                <th scope="col">Статус</th>
                                <th scope="col">Создана</th>
                                <th scope="col">Автор</th>
                                <th scope="col">Псевдоним</th>
                                <th scope="col">Email</th>
                                <th scope="col">Страниц</th>
                                <th scope="col">Экземпляров</th>
                                <th scope="col">Промокод</th>
                            </tr>
                        </thead>
                        <tbody>
                        {{App::setLocale('ru')}}
                        @foreach($new_participations as $participation)
                        <tr class="row_hover" onclick="document.location = '{{route('user_participation', ['participation_id' => $participation['id']])}}';">
                            <td scope="row" data-label="Статус" style="text-align: center;">
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

                            <td data-label="Создана" style="text-align: center;">
                                {{ Date::parse($participation['created_at'])->addHours(3)->format('j F H:i') }}
                            </td>

                            <td data-label="Автор" style="text-align: center;">
                                {{$participation['name']}} {{$participation['surname']}}
                            </td>
                            <td data-label="Псевдоним" style="text-align: center;">
                                {{$participation['nickname']}}
                            </td>
                            <td data-label="Email" style="text-align: center;">
                                {{$participation->user['email']}}
                            </td>
                            <td data-label="Страниц" style="text-align: center;">
                                {{$participation['pages']}}
                            </td>
                            <td data-label="Экземпляров" style="text-align: center;">
                                {{$participation->printorder['books_needed'] ?? 'Печать не нужна'}}
                            </td>
                            <td data-label="Промокод" style="text-align: center;">
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
