@extends('layouts.admin_layout')
@section('title', 'Сборник')
@section('content')
    <!-- Content Header (Page header) -->

    <div class="content-header">
        <h1 style="font-size: 37px;" class="mb-3">{{$collection['title']}}</h1>
    </div><!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="mb-3 card collapsed-card">
                <div class="bg-gradient-info card-header">
                    <h1 style="font-size: 25px;" class="card-title">Общая информация сборника ({{$collection->col_status['col_status']}})</h1>

                    <div class="card-tools">
                        <button class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- form start -->
                            <form action="{{ route('collection.update',$collection['id']) }}" method="post"
                                  enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="mb-4 p-3 form-row">

                                    <div style="width:100%;" class="d-flex align-items-center mb-3 col-md-2">
                                        <img width="100%" src="/{{$collection['cover_3d']}}" alt="">
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <div class="mb-2">
                                            Название
                                            <input value="{{$collection['title']}}" type="text" name="title"
                                                   class="form-control" id="exampleInputEmail1"
                                                   placeholder="Название сборника" required>
                                        </div>
                                        <div class="mb-2"> Обложка 2d
                                            <input type="file" name="cover_2d"
                                                   class="d-none form-control custom-file-input" id="cover_2d"
                                                   aria-describedby="myInput">

                                            <label id="label_cover_2d"
                                                   class="position-relative form-control custom-file-label"
                                                   for="cover_2d">{{substr($collection['cover_2d'], strrpos($collection['cover_2d'], '/') + 1)}}
                                            </label>
                                        </div>
                                        <div class="mb-2"> Обложка 3d
                                            <input type="file" name="cover_3d"
                                                   class="d-none form-control custom-file-input" id="cover_3d"
                                                   aria-describedby="myInput">

                                            <label id="label_cover_3d"
                                                   class="position-relative form-control custom-file-label"
                                                   for="cover_3d">
                                                {{substr($collection['cover_3d'], strrpos($collection['cover_3d'], '/') + 1)}}
                                            </label>
                                        </div>

                                        <div class="mb-2">
                                            Amazon ссылка
                                            <input value="{{$collection['amazon_link']}}" type="text" name="amazon_link"
                                                   class="form-control" id="amazon_link"
                                                   placeholder="Ссылки еще нет...">
                                        </div>

                                        <div class="mb-2">
                                            Статус
                                            <select name="col_status_id" id="status_select" class="form-control">
                                                @foreach ($col_statuses as $col_status)
                                                    <option value="{{ $col_status['id'] }}"
                                                            @if ($collection['col_status_id'] == $col_status['id']) selected @endif>
                                                        {{ $col_status['col_status'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div @if ($collection['col_status_id'] <> 2) style="display:none"
                                             @endif id="preview_file" class="mb-2">
                                            Файл предварительной проверки
                                            <input value="{{$collection['pre_var']}}" type="file" id="pre_var" name="pre_var"
                                                   class="d-none custom-file-input"
                                                   aria-describedby="myInput">
                                            <label class="w-100 mb-0 position-relative custom-file-label"
                                                  id="label_pre_var" for="pre_var">{{substr($collection['pre_var'], strrpos($collection['pre_var'], '/') + 1)}}</label>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        Описание сборника
                                        <textarea class="h-100 form-control" name="col_desc"
                                                  placeholder="Описание сборника">{{$collection['col_desc']}}</textarea>
                                    </div>

                                    <div class="mb-3 col-md-2">
                                        <div class="mb-2 date">Конец приема заявок <input
                                                value="{{$collection['col_date1']}}" name="col_date1" class="datepicker"
                                                id="datepicker1"/></div>
                                        <div class="mb-2 date">Предварительные экземпляры <input
                                                value="{{$collection['col_date2']}}" name="col_date2" class="datepicker"
                                                id="datepicker2"/></div>
                                        <div class="mb-2 date">Начало печати <input value="{{$collection['col_date3']}}"
                                                                                    name="col_date3" class="datepicker"
                                                                                    id="datepicker3"/></div>
                                        <div class="mb-2 date">Печать до<input value="{{$collection['col_date4']}}"
                                                                               name="col_date4" class="datepicker"
                                                                               id="datepicker4"/></div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Обновить</button>
                                </div>
                            </form>

                            <script>
                                $("#status_select").change(function () {
                                    if ($(this).val() == 2)
                                        $('#preview_file').slideDown();
                                    else
                                        $('#preview_file').slideUp();
                                });
                            </script>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

    <div class="mt-4 row">
        <div class="col-md-12">
            <div class="card mt-0 collapsed-card">
                <div class="bg-gradient-lightblue card-header">
                    <h1 style="font-size: 25px;" class="card-title">Участники </h1>
                    <h1 style="font-size: 25px;" class="ml-2 card-title"> (Оплатили: {{\App\Models\Participation::where([['collection_id', $collection['id']],['pat_status_id', '3']])->count()}}; Оплачивают: {{\App\Models\Participation::where([['collection_id', $collection['id']],['pat_status_id', '2']])->count()}}; Ждут апрува: {{\App\Models\Participation::where([['collection_id', $collection['id']],['pat_status_id', '1']])->count()}})</h1>

                    <div class="card-tools">
                        <button class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card-header">
                                <input class="form-control" id="participants_input" type="text"
                                       placeholder="Поиск...">
                            </div>
                            <div class="card-body p-0">
                                <table id="participants_table" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Статус</th>
                                        <th>Автор</th>
                                        <th>Псевдоним</th>
                                        <th>Email</th>
                                        <th>Страниц</th>
                                        <th>Экземпляров</th>
                                        <th>Промокод</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($participations as $participation)

                                        <tr onclick="document.location = '{{route('user_participation', ['participation_id' => $participation['id']])}}';"
                                            class="row_hover
                                          ">
                                            <td style="text-align: center;">
                                                <i data-toggle="tooltip" data-placement="top"
                                                   title="{{$participation->pat_status['pat_status_title']}}"
                                                   class="fas question-mark
                                       @if ($participation['pat_status_id'] == 1) fa-glass-cheers
                                       @elseif ($participation['pat_status_id'] == 2) fa-comments-dollar
                                       @elseif ($participation['pat_status_id'] == 3) fa-check-circle
                                       @elseif ($participation['pat_status_id'] == 4) fa-comments-dollar
                                       @elseif ($participation['pat_status_id'] == 9) fa-edit
                                       @endif
                                                       "></i>
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
                                                {{$participation->printorder['books_needed'] ?? 0}}
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
                                        $("#participants_table tbody tr").filter(function () {
                                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                        });
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->
    </div>

    <div class="mt-4 row">
        <div class="col-md-12">
            <div class="card collapsed-card mt-0">
                <div class="bg-gradient-teal card-header">
                    <h1 style="font-size: 25px;" class="card-title">Исправления: {{$pre_comments->count()}}  <span style="@if($pre_comments->where('status_done', 0)->count() > 0)text-transform: uppercase; font-weight: 600; @endif">(нужно исправить: {{$pre_comments->where('status_done', 0)->count()}})</span></h1>

                    <div class="card-tools">
                        <button class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card-header">
                                <input class="form-control" id="comments_input" type="text"
                                       placeholder="Поиск...">
                            </div>
                            <div class="card-body p-0">
                                <form
                                    class="d-flex flex-column justify-content-center align-items-right"
                                    style=" align-items: flex-end;"
                                    action="{{ route('change_all_preview_collection_comment_status',$collection['id']) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                >
                                    @csrf
                                    <button class="float-right border-0 " type="submit"
                                            style="background: #fff0; color: #34b734 !important;">Отметить все
                                        выполненными
                                    </button>
                                </form>
                                <table id="comments_table" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Автор</th>
                                        <th>Псевдоним</th>
                                        <th>Страница</th>
                                        <th>Описание</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pre_comments as $pre_comment)

                                        <tr>
                                            <td style="width: 10%; text-align: center;">
                                                {{$pre_comment->participation['surname']}} {{$pre_comment->participation['name']}}
                                            </td>
                                            <td style="width: 10%;  text-align: center;">
                                                {{$pre_comment->participation['nickname']}}
                                            </td>
                                            <td style="width: 10%;  text-align: center;">
                                                {{$pre_comment['page']}}
                                            </td>
                                            <td>
                                                {{$pre_comment['text']}}
                                            </td>
                                            <td style="width: 5%; text-align: center;">
                                                <form
                                                    class="d-flex flex-column justify-content-center align-items-center"
                                                    style=" align-items: center;"
                                                    action="{{ route('change_preview_comment_status',$pre_comment->id) }}"
                                                    method="POST"
                                                    enctype="multipart/form-data"
                                                >
                                                    @csrf
                                                    @if($pre_comment['status_done'] === 0)
                                                        <svg width="25px" style="fill: red;"
                                                             viewBox="0 0 512 512">
                                                            <path
                                                                d="M256,512C114.84,512,0,397.16,0,256S114.84,0,256,0,512,114.84,512,256,397.16,512,256,512Zm0-475.43C135,36.57,36.57,135,36.57,256S135,475.43,256,475.43,475.43,377,475.43,256,377,36.57,256,36.57Z"
                                                                transform="translate(0 0)"/>
                                                            <path
                                                                d="M347.43,365.71a18.22,18.22,0,0,1-12.93-5.35L151.64,177.5a18.29,18.29,0,0,1,25.86-25.86L360.36,334.5a18.28,18.28,0,0,1-12.93,31.21Z"
                                                                transform="translate(0 0)"/>
                                                            <path
                                                                d="M164.57,365.71a18.28,18.28,0,0,1-12.93-31.21L334.5,151.64a18.29,18.29,0,0,1,25.86,25.86L177.5,360.36A18.22,18.22,0,0,1,164.57,365.71Z"
                                                                transform="translate(0 0)"/>
                                                        </svg>
                                                        <button class="border-0 " type="submit"
                                                                style="background: #fff0; color: #34b734 !important;">
                                                            Выполнить
                                                        </button>
                                                    @else
                                                        <svg width="30px" style="fill: #34b734;"
                                                             viewBox="0 0 477.87 477.87">
                                                            <path
                                                                d="M238.93,0C107,0,0,107,0,238.93S107,477.87,238.93,477.87s238.94-107,238.94-238.94S370.83.14,238.93,0Zm0,443.73c-113.11,0-204.8-91.69-204.8-204.8s91.69-204.8,204.8-204.8,204.8,91.69,204.8,204.8S352,443.61,238.93,443.73Z"
                                                                transform="translate(0 0)"/>
                                                            <path
                                                                d="M370.05,141.53a17.09,17.09,0,0,0-23.72,0h0l-158.6,158.6-56.2-56.2A17.07,17.07,0,1,0,107,267.65l.42.41,68.27,68.27a17.07,17.07,0,0,0,24.13,0L370.47,165.66A17.07,17.07,0,0,0,370.05,141.53Z"
                                                                transform="translate(0 0)"/>
                                                        </svg>
                                                        <button class="border-0 " type="submit"
                                                                style="background: #fff0; color: #de6464 !important;">
                                                            Отменить
                                                        </button>
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        $("#comments_input").on("keyup", function () {
                            var value = $(this).val().toLowerCase();
                            $("#comments_table tbody tr").filter(function () {
                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                            });
                        });
                    });
                </script>
                <!-- /.row -->
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->

    </div>

    <div class="mt-4 row">
        <div class="col-md-12">
            <div class="card collapsed-card mt-0">
                <div class="bg-gradient-cyan card-header">
                    <h1 style="font-size: 25px;" class="card-title">Печатные экзепляры</h1>

                    <div class="card-tools">
                        <button class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card-header">
                                <input class="form-control" id="print_orders_input" type="text"
                                       placeholder="Поиск...">
                            </div>
                            <div class="card-body p-0">
                                @livewire('admin-print-order-table',['collection_id'=>$collection['id']])
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>

                <!-- /.row -->
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->

    </div>


@endsection
