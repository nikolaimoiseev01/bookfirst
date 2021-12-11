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
                    <h1 style="font-size: 25px;" class="card-title">Общая информация сборника
                        ({{$collection->col_status['col_status']}})</h1>

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
                                            <input value="{{$collection['pre_var']}}" type="file" id="pre_var"
                                                   name="pre_var"
                                                   class="d-none custom-file-input"
                                                   aria-describedby="myInput">
                                            <label class="w-100 mb-0 position-relative custom-file-label"
                                                   id="label_pre_var"
                                                   for="pre_var">{{substr($collection['pre_var'], strrpos($collection['pre_var'], '/') + 1)}}</label>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        Описание сборника
                                        <textarea class="h-100 form-control" name="col_desc"
                                                  placeholder="Описание сборника">{{$collection['col_desc']}}</textarea>
                                    </div>

                                    <div class="mb-3 col-md-2">
                                        {{App::setLocale('ru')}}
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

                                    <script>

                                        function check_cur_date () {
                                            date_1 = $('#datepicker1').val()
                                            date_2 = $('#datepicker2').val()
                                            date_3 = $('#datepicker3').val()
                                            date_4 = $('#datepicker4').val()
                                        }

                                        function take_cur_date(id) {
                                            cur_date_before =  $('#datepicker_').val()
                                        }

                                        check_cur_date();

                                        $('.datepicker').on('change', function() {
                                            cur_date = $(this).val();
                                            id = $(this).attr('id').slice(-1);


                                            if (cur_date != date_1) {

                                            }
                                        })
                                    </script>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="save_collection btn btn-primary">Обновить</button>
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
                    <h1 style="font-size: 25px;" class="ml-2 card-title">
                        (Оплатили: {{\App\Models\Participation::where([['collection_id', $collection['id']],['pat_status_id', '3']])->count()}}
                        ;
                        Оплачивают: {{\App\Models\Participation::where([['collection_id', $collection['id']],['pat_status_id', '2']])->count()}}
                        ; Ждут
                        апрува: {{\App\Models\Participation::where([['collection_id', $collection['id']],['pat_status_id', '1']])->count()}}
                        )</h1>

                    <div class="card-tools">
                        <button class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body p-0" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card-header">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#all_participants"
                                                            data-toggle="tab">
                                            Все участники
                                        </a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#winners"
                                                            data-toggle="tab">Конкурс</a>
                                    </li>

                                    <li class="nav-item"><a class="nav-link" href="#all_emails"
                                                            data-toggle="tab">Email всем</a>
                                    </li>
                                    <li class="ml-auto nav-item">
                                        <form
                                            id="chat"
                                            enctype="multipart/form-data"
                                            method="get"
                                            action="{{route('create_col_file')}}"
                                            class="ml-auto">
                                            @csrf
                                            <input style="display: none" type="number" id="col_id" name="col_id" value="{{$collection['id']}}">
                                                <button id="chat_form" style="width:fit-content; position: relative;"  class="button btn btn-block bg-gradient-primary" >
                                                    <span class="button__text">Скачать верстку!</span>
                                                </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body p-0">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="all_participants">
                                        <div class="p-2">
                                            <input class="form-control" id="participants_input" type="text"
                                                   placeholder="Поиск...">
                                        </div>
                                        <table id="participants_table" class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th scope="col">Статус</th>
                                                <th scope="col">Автор</th>
                                                <th scope="col">Псевдоним</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Страниц</th>
                                                <th scope="col">Экземпляров</th>
                                                <th scope="col">Промокод</th>
                                                <th scope="col">Общая сумма</th>
                                                <th scope="col">Создан</th>
                                                <th scope="col">Обновлен</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{App::setLocale('ru')}}
                                            @foreach($participations as $participation)

                                                <tr onclick="document.location = '{{route('user_participation', ['participation_id' => $participation['id']])}}';"
                                                    class="row_hover
                                          ">
                                                    <td scope="row" data-label="Статус" style="text-align: center;">
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
                                                        {{$participation->printorder['books_needed'] ?? 0}}
                                                    </td>
                                                    <td data-label="Промокод" style="text-align: center;">
                                                        {{$participation['promocode']}}
                                                    </td>
                                                    <td data-label="Общая сумма" style="text-align: center;">
                                                        {{$participation['total_price']}} руб.
                                                    </td>
                                                    <td data-label="Создан" style="text-align: center;">
                                                        {{ Date::parse($participation['created_at'])->addHours(3)->format('j F H:i') }}
                                                    </td>
                                                    <td data-label="ОБновлен" style="text-align: center;">
                                                        {{ Date::parse($participation['updated_at'])->addHours(3)->format('j F H:i') }}
                                                    </td>


                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="winners">
                                        <div class="p-4">
                                            <div class="row">
                                                <div class="border-right col-md-6">
                                                    @foreach($winners as $winner)
                                                        <h2 style="font-size: 30px;">{{$loop->index + 1}} место
                                                            (голосов: {{$winner->votes_got}}):
                                                            @if($winner->nickname <> null)
                                                                {{$winner->nickname}}

                                                            @else
                                                                {{$winner->name}} {{$winner->surname}}
                                                            @endif
                                                        </h2>
                                                    @endforeach
                                                </div>
                                                <div style="font-size: 20px;" class="mb-3 pl-4 col-md-6">
                                                    @foreach($votes as $vote)
                                                        @if($vote->user_from_nickname <> null)
                                                            {{$vote->user_from_nickname}}

                                                        @else
                                                            {{$vote->user_from_name}} {{$vote->user_from_surname}}
                                                        @endif
                                                        -> за
                                                        @if($vote->user_to_nickname <> null)
                                                            {{$vote->user_to_nickname}}

                                                        @else
                                                            {{$vote->user_to_name}} {{$vote->user_to_surname}}
                                                        @endif
                                                    <br>
                                                    @endforeach
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="p-3 tab-pane" id="all_emails">

                                        <!-- /.card-header -->
                                        <div class="card-body p-0">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                <tr>
                                                    <th style="text-align: center">Тема</th>
                                                    <th style="text-align: center">Текст</th>
                                                    <th style="text-align: center">Кому</th>
                                                    <th style="text-align: center">Отправлен</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {{App::setLocale('ru')}}
                                                @foreach($emails_sent as $email_sent)
                                                    <tr>
                                                        <td style="text-align: center">{{$email_sent['subject']}}</td>

                                                        <td style="text-align: center">
                                                            {{$email_sent['email_text']}}
                                                        </td>

                                                        <td style="text-align: center">
                                                            @php
                                                              $users_sent_to =  explode(';', $email_sent['sent_to_user']);
                                                                foreach ($users_sent_to as $users_sent_to) {
                                                                    $partic = \App\Models\Participation::where('collection_id', $collection['id'])->where('user_id', $users_sent_to)->first();
                                                                    echo'
                                                                        <a href="/admin_panel/collections/participation/' . $partic['id'] . '">' . $partic['name'] . '</a>;&nbsp
                                                                   ';
                                                                }
                                                            @endphp
                                                        </td>

                                                        <td style="text-align: center">
                                                            {{ Date::parse($email_sent['created_at'])->addHours(3)->format('j F H:i') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                            <div style="display:none" class="p-3 mt-3 border chat-create-admin">
                                                <form
                                                    id="chat"
                                                    enctype="multipart/form-data"
                                                    method="post"
                                                    action="{{route('send_email_all_participants')}}">
                                                    @csrf
                                                    <div class="chat-create-wrap">
                                                        <p class="mb-0">Тема: </p>
                                                        <input type="number" id="col_id" name="col_id" style="display: none;" value="{{$collection['id']}}">
                                                        <input value="Процесс издания сборника" id="subject" name="subject" class="form-control" type="text">
                                                        <textarea style="min-height: 200px; resize: none;" type="text" placeholder="ТОЛЬКО ТЕЛО ПИСЬМА! Не нужно писать приветствие и концовку, это будет автоматом!" name="email_text" class="mt-3 form-control" id="email_text"></textarea>
                                                        <button id="chat_form" style="width:fit-content; position: relative;"  class="all_participants_email mt-3 button btn btn-block bg-gradient-primary" >
                                                            <span class="button__text">Отправить всем участникам!</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            <a id="chat_add" style="width: fit-content;" class="mt-3 btn btn-outline-secondary">
                                                <i class="mr-2 fa fa-plus"></i> Создать Email всем участникам</a>
                                        </div>
                                        <script>
                                            $('#chat_add').click(function () {
                                                $('.chat-create-admin').toggle();

                                                if($('.chat-create-admin').is(":visible")) {
                                                    $('#chat_add').html('<i class="mr-2 fa fa-times"></i> Отменить');
                                                }
                                                else {
                                                    $('#chat_add').html(' <i class="mr-2 fa fa-plus"></i> Создать чат');
                                                }
                                            })
                                        </script>
                                    </div><!-- /.tab-content -->
                                </div>
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
                    <h1 style="font-size: 25px;" class="card-title">Исправления: {{$pre_comments->count()}}
                        <span
                            style="@if($pre_comments->where('status_done', 0)->count() > 0)text-transform: uppercase; font-weight: 600; @endif">(нужно исправить: {{$pre_comments->where('status_done', 0)->count()}})</span>
                    </h1>

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

                            <div class="d-flex card-header">
                                <input class="form-control" id="print_orders_input" type="text"
                                       placeholder="Поиск...">
                                <form
                                    id="chat"
                                    enctype="multipart/form-data"
                                    method="get"
                                    action="{{route('download_all_prints')}}"
                                    class="ml-3">
                                    @csrf
                                    <input style="display: none" type="number" id="col_id" name="col_id" value="{{$collection['id']}}">
                                    <button id="chat_form" style="width:fit-content; position: relative;"  class="button btn btn-block bg-gradient-primary" >
                                        <span class="button__text">Скачать таблицу печати!</span>
                                    </button>
                                </form>
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
