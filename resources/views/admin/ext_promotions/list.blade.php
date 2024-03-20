@extends('layouts.admin_layout')
@section('title', 'Продвижения')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="justify-content-between d-flex">
                <h1 class="m-0">Продвижения</h1>
                <style>
                    .page-link, .page-item {
                        display: flex;
                        height: 38px;
                    }
                </style>
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
                            <th scope="col" style="text-align: center;">Статус</th>
                            <th scope="col" style="text-align: center;">Автор</th>
                            <th scope="col" style="text-align: center;">Сайт</th>
                            <th scope="col" style="text-align: center;">Дней</th>
                            <th scope="col" style="text-align: center;">Создан</th>
                            <th scope="col" style="text-align: center;">Последнее изменение</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{App::setLocale('ru')}}
                        @foreach($ext_promotions as $ext_promotion)

                            <tr style="
                                @if(in_array($ext_promotion['ext_promotion_status_id'], [1,3]) )
                                    background: #ffe7e7; color: black;
                                @endif
                                "
                                onclick="document.location = '' + '{{route('admin_ext_promotion', $ext_promotion['id'])}}' + ''">

                                <td data-label="Статус" style="text-align: center;">
                                    {{$ext_promotion->ext_promotion_status['title']}}
                                </td>
                                <td scope="row" data-label="Автор" style="text-align: center;">
                                    @role('admin')
                                    <a href="{{route('user_page', $ext_promotion['user_id'])}}">{{$ext_promotion->user['name']}} {{$ext_promotion->user['surname']}}</a>
                                    @else
                                    {{$ext_promotion->user['name']}} {{$ext_promotion->user['surname']}}
                                    @endrole

                                </td>
                                <td data-label="Сайт" style="text-align: center;">
                                    {{$ext_promotion['site']}}
                                </td>
                                <td data-label="Дней" style="text-align: center;">
                                    {{$ext_promotion['days']}}
                                </td>
                                <td data-label="Создан" style="text-align: center;">
                                    {{ Date::parse($ext_promotion['created_at'])->addHours(3)->format('j F H:i') }}
                                </td>

                                <td data-label="Обновлен" style="text-align: center;">
                                    {{ Date::parse($ext_promotion['updated_at'])->addHours(3)->format('j F H:i') }}
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
    <!-- /.content -->
@endsection
