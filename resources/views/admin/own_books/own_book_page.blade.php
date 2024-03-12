@extends('layouts.admin_layout')
@section('title', $own_book->user['surname'])
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="card">
                <div class="d-flex align-items-center bg-gradient-lightblue card-header">
                    <h3 class=" mt-2">
                        <b style="font-size: 28px;">{{$own_book['author']}}: {{$own_book['title']}}</b>
                    </h3>


                    <i style="margin-left: auto;">
                        <a target="_blank" style="margin-right: 20px; color: #a8fffb" href="{{route('own_book_user_page', $own_book['id'])}}">
                            <i>Страница на портале</i>
                        </a>

                        <span style="font-size: 18px;">кем создан:
                            @if ($own_book['user_id'])
                                <a style="color: #a8fffb" href="{{route('user_page', $own_book['user_id'])}}">
                                <i>{{$own_book->user['name']}} {{$own_book->user['surname']}}</i>
                            </a>
                            @else
                                старая книга, недоступно
                            @endif
                        </span>


                    </i>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h4 style="display: flow-root !important;" class="m-0">Статус общий:
                            <i @if($own_book['own_book_status_id'] == 1 || $own_book['own_book_status_id'] == 5)
                                   style="display: inline-block; color: #f74e4e;"
                               @elseif($own_book['own_book_status_id'] == 3)
                                   style="display: inline-block; color: #f39405;"
                               @endif
                               id="change_book_status_text">{{$own_book->own_book_status['status_title']}}</i>
                        </h4>
                        <div style="display: none" id="change_book_status_form_wrap">
                            <form class="d-flex ml-3" style="align-items: center;"
                                  action="{{ route('change_book_status',$own_book['id']) }}" method="POST"
                                  enctype="multipart/form-data"
                            >
                                @csrf

                                <input value="{{$own_book['user_id']}}" type="text" name="user_id"
                                       style="display:none" class="form-control"
                                       id="user_id">

                                <input value="{{$own_book['id']}}" type="text" name="own_book_id"
                                       style="display:none" class="form-control"
                                       id="own_book_id">

                                <select style="padding: 0 0 0 10px; height: 33px; width: fit-content;"
                                        id="own_book_status_id" class="form-control"
                                        name="own_book_status_id">
                                    @foreach($own_book_statuses as $own_book_status)
                                        <option @if($own_book['own_book_status_id'] === $own_book_status['id']) selected
                                                @endif value="{{$own_book_status['id']}}">{{$own_book_status['status_title']}}</option>
                                    @endforeach
                                </select>

                                <button id="btn-submit" type="submit"
                                        style="height: fit-content; max-height: 30px; max-width:150px;"
                                        data-status-from="{{$own_book->own_book_status['status_title']}}"
                                        class="change_status ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                >
                                    Сохранить
                                </button>
                            </form>
                        </div>
                        <button style="border:none; width: auto; padding: 3px 10px; max-width:150px"
                                data-form="change_book_status" type="button"
                                class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                        >
                            <i style="font-size: 20px;" class="fa fa-edit"></i>

                        </button>
                    </div>

                    @if ($own_book['user_id'])
                        <div class="mt-2 d-flex align-items-center">
                            <h4 style="margin-bottom: 0 !important;" class="">Статус ВБ:
                                <i id="change_book_inside_status_text">{{$own_book->own_book_inside_status['status_title']}}</i>
                                @if($own_book['own_book_status_id'] == 3 & $own_book['own_book_inside_status_id'] == 1)
                                    <p>
                                        (Срок:
                                        до {{ Date::parse($own_book['inside_deadline'])->addHours(3)->format('j F') }})
                                    </p>
                                @endif
                            </h4>

                            <div style="display: none" id="change_book_inside_status_form_wrap">
                                <form class="d-flex ml-3" style=" align-items: center;"
                                      action="{{ route('change_book_inside_status',$own_book['id']) }}"
                                      method="POST"
                                      enctype="multipart/form-data"
                                >
                                    @csrf

                                    <input value="{{$own_book['user_id']}}" type="text" name="user_id"
                                           style="display:none" class="form-control"
                                           id="user_id">

                                    <input value="{{$own_book['id']}}" type="text" name="own_book_id"
                                           style="display:none" class="form-control"
                                           id="own_book_id">
                                    <select style="padding: 0 0 0 10px; height: 33px; width: fit-content;"
                                            id="own_book_inside_status_id"
                                            class="form-control" name="own_book_inside_status_id">
                                        @foreach($own_book_inside_statuses as $own_book_inside_status)
                                            <option
                                                @if($own_book['own_book_inside_status_id'] === $own_book_inside_status['id']) selected
                                                @endif value="{{$own_book_inside_status['id']}}">
                                                {{$own_book_inside_status['status_title']}}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button id="btn-submit" type="submit"
                                            style="height: fit-content; max-height: 30px; max-width:150px;"
                                            data-status-from="{{$own_book->own_book_inside_status['status_title']}}"
                                            class="change_status ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                    >
                                        Сохранить
                                    </button>
                                </form>
                            </div>
                            <button style="border: none; width: auto; padding: 3px 10px;max-width:150px"
                                    data-form="change_book_inside_status" type="button"
                                    class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                            >
                                <i style="font-size: 20px;" class="fa fa-edit"></i>

                            </button>
                        </div>
                        <div class="mt-2 d-flex align-items-center">
                            <h4 style="margin-bottom: 0 !important;" class="">Статус обложки:
                                <i id="change_book_cover_status_text">{{$own_book->own_book_cover_status['status_title']}}</i>
                                @if($own_book['own_book_status_id'] == 3 & $own_book['own_book_cover_status_id'] == 1)
                                    <p>
                                        (Срок:
                                        до {{ Date::parse($own_book['cover_deadline'])->addHours(3)->format('j F') }})
                                    </p>
                                @endif
                            </h4>
                            <div style="display: none" id="change_book_cover_status_form_wrap">
                                <form class="d-flex ml-3" style=" align-items: center;"
                                      action="{{ route('change_book_cover_status',$own_book['id']) }}" method="POST"
                                      enctype="multipart/form-data"
                                >
                                    @csrf

                                    <input value="{{$own_book['user_id']}}" type="text" name="user_id"
                                           style="display:none" class="form-control"
                                           id="user_id">

                                    <input value="{{$own_book['id']}}" type="text" name="own_book_id"
                                           style="display:none" class="form-control"
                                           id="own_book_id">
                                    <select style="width: fit-content;" id="own_book_cover_status_id"
                                            class="form-control" name="own_book_cover_status_id">
                                        @foreach($own_book_cover_statuses as $own_book_cover_status)
                                            <option
                                                @if($own_book['own_book_cover_status_id'] === $own_book_cover_status['id']) selected
                                                @endif value="{{$own_book_cover_status['id']}}">
                                                {{$own_book_cover_status['status_title']}}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button id="btn-submit" type="submit"
                                            style="height: fit-content; max-height: 30px; max-width:150px;"
                                            data-status-from="{{$own_book->own_book_cover_status['status_title']}}"
                                            class="change_status ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                    >
                                        Сохранить
                                    </button>
                                </form>
                            </div>
                            <button style="border: none; width: auto; padding: 3px 10px; max-width:150px"
                                    data-form="change_book_cover_status" type="button"
                                    class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                            >
                                <i style="font-size: 20px;" class="fa fa-edit"></i>

                            </button>
                        </div>
                    @endif
                    <div class="mt-2 d-flex align-items-center">
                        <h4 class="m-0">Страниц:
                            <i id="change_book_pages_text"><b>{{$own_book['pages']}}</b>
                                (<b>{{$own_book['color_pages'] ?? 0}}</b> цветных)</i>
                        </h4>
                        <div style="display: none" id="change_book_pages_form_wrap">
                            <form class="d-flex ml-3" style=" align-items: center;"
                                  action="{{ route('change_book_pages',$own_book['id']) }}" method="POST"
                                  enctype="multipart/form-data"
                            >
                                @csrf

                                <input value="{{$own_book['user_id']}}" type="text" name="user_id"
                                       style="display:none" class="form-control"
                                       id="user_id">

                                <input value="{{$own_book['id']}}" type="text" name="own_book_id"
                                       style="display:none" class="form-control"
                                       id="own_book_id">

                                <input style="width: 50px; padding: 0 0 0 10px; height: 33px;" type="number"
                                       id="own_book_pages" class="form-control"
                                       name="own_book_pages"
                                       value="{{$own_book['pages']}}">
                                <h4 class="mb-0 ml-2 mr-1">цветных:</h4>
                                <input style="width: 50px; padding: 0 0 0 10px; height: 33px;" type="number"
                                       id="own_book_color_pages" class="form-control"
                                       name="own_book_color_pages"
                                       value="{{$own_book['color_pages'] ?? 0}}">


                                <button id="btn-submit" type="submit"
                                        style="height: fit-content; max-height: 30px; max-width:150px;"
                                        data-status-from="{{$own_book['pages']}}"
                                        class="ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                >
                                    Сохранить
                                </button>
                            </form>
                        </div>
                        <button style="border:none; width: auto; padding: 3px 10px; max-width:150px"
                                data-form="change_book_pages" type="button"
                                class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                        >
                            <i style="font-size: 20px;" class="fa fa-edit"></i>

                        </button>
                    </div>

                    <div class="d-flex align-items-center">
                        <h4 class="mt-2">Вариант продвижения:
                            <i id="change_promo_text">@if($own_book['promo_price'])
                                    за {{$own_book['promo_price']}}
                                    руб.
                                @else
                                    не требуется
                                @endif</i>
                        </h4>
                        <div style="display: none" id="change_promo_form_wrap">
                            <form class="d-flex ml-3" style=" align-items: center;"
                                  action="{{ route('change_book_promo_type',$own_book['id']) }}" method="POST"
                                  enctype="multipart/form-data"
                            >
                                @csrf

                                <input value="{{$own_book['id']}}" type="text" name="own_book_id"
                                       style="display:none" class="form-control"
                                       id="own_book_id">


                                <select style="padding: 0 0 0 10px; height: 33px; width: fit-content;"
                                        id="promo_type" class="form-control"
                                        name="promo_type">
                                    <option value="">Не требуется</option>
                                    <option value="500">За 500</option>
                                    <option value="2000">За 2000</option>
                                </select>

                                <button id="btn-submit" type="submit"
                                        style="height: fit-content; max-height: 30px; max-width:150px;"
                                        data-status-from="@if($own_book['promo_price'])за {{$own_book['promo_price']}} руб.@else не требуется @endif"
                                        class="change_status ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                >
                                    Сохранить
                                </button>
                            </form>
                        </div>
                        <button style="border:none; width: auto; padding: 3px 10px; max-width:150px"
                                data-form="change_promo" type="button"
                                class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                        >
                            <i style="font-size: 20px;" class="fa fa-edit"></i>

                        </button>
                    </div>

                    <div class="d-flex align-items-center">
                        <h4 class="m-0">
                            @if($own_book['amazon_link'])
                                <a href="{{$own_book['amazon_link']}}" target="_blank">Amazon ссылка</a>
                            @else
                                Amazon ссылка: нет
                            @endif
                        </h4>
                        <div style="display: none" id="change_amazon_link_form_wrap">
                            <form class="d-flex ml-3" style=" align-items: center;"
                                  action="{{ route('change_amazon_link',$own_book['id']) }}" method="POST"
                                  enctype="multipart/form-data"
                            >
                                @csrf

                                <input value="{{$own_book['user_id'] ?? 0}}" type="text" name="user_id"
                                       style="display:none" class="form-control"
                                       id="user_id">

                                <input value="{{$own_book['id']}}" type="text" name="own_book_id"
                                       style="display:none" class="form-control"
                                       id="own_book_id">

                                <input style="padding: 0 0 0 10px; height: 33px;" type="text"
                                       id="amazon_link" class="form-control"
                                       name="amazon_link"
                                       value="{{$own_book['amazon_link']}}">
                                <button id="btn-submit" type="submit"
                                        style="height: fit-content; max-height: 30px; max-width:150px;"
                                        data-status-from="{{substr($own_book['amazon_link'] ?? '',12,20)}}"
                                        class="ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                >
                                    Сохранить
                                </button>
                            </form>
                        </div>
                        <button style="border:none; width: auto; padding: 3px 10px; max-width:150px"
                                data-form="change_amazon_link" type="button"
                                class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                        >
                            <i style="font-size: 20px;" class="fa fa-edit"></i>

                        </button>
                    </div>

                    <form style="gap: 20px;" class="d-flex flex-wrap align-items-center mt-2 gap-2"
                          action="{{ route('add_own_book_comment',$own_book['id']) }}" method="POST"
                          enctype="multipart/form-data"
                    >
                        @csrf
                        <h4 class="m-0">Комментарий: </h4>
                        <div id="comment_text">
                            {!! $own_book['comment'] !!}
                        </div>
                        <div style="display: none;" id="comment_text_edit">
                            <textarea name="comment" id="summernote">{{$own_book['comment']}}</textarea>
                            <button type="submit" class="mt-2 btn btn-primary">Обновить</button>
                        </div>

                        <button style="border:none; width: auto; padding: 3px 10px; max-width:150px"
                                id="edit_comment_button"
                                type="button"
                                class="ml-1 btn btn-outline-info btn-block btn-sm"
                        >
                            <i style="font-size: 20px;" class="fa fa-edit"></i>

                        </button>
                        <style>
                            #comment_text p {
                                margin: 0 !important;
                            }
                        </style>
                        @push('page-js')
                            <script>
                                $(document).ready(function () {
                                    $('#summernote').summernote({
                                        toolbar: [
                                            // [groupName, [list of button]]
                                            ['style', ['bold', 'italic', 'underline']],
                                            // ['font', ['strikethrough', 'superscript', 'subscript']],
                                            ['fontsize', ['fontsize']],
                                            ['color', ['forecolor']],
                                            // ['para', ['ul', 'ol', 'paragraph']],
                                            // ['height', ['height']]
                                        ],
                                        fontSizes: ['18', '20', '22', '24'],
                                        Width: 2000,
                                    });
                                });

                                $('#edit_comment_button').on('click', function (e) {
                                    e.preventDefault()
                                    $('#comment_text_edit').toggle();
                                    $('#comment_text').toggle();
                                    // if ($('#comment_text_edit').is(":visible")) {
                                    //     $(this).text('Скрыть')
                                    // } else {
                                    //     $(this).text('Редактировать')
                                    // }
                                })
                            </script>
                        @endpush
                    </form>


                </div>
            </div>
        </div><!-- /.container-fluid -->


    </div>
    <!-- /.content-header -->
    {{\Illuminate\Support\Facades\App::setLocale('ru')}}
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#inside" data-toggle="tab">Внутренний
                                блок</a></li>
                        <li class="nav-item"><a class="nav-link" href="#cover" data-toggle="tab">Обложка</a></li>
                        <li class="nav-item"><a class="nav-link" href="#print" data-toggle="tab">Печать</a></li>
                        <li class="nav-item"><a class="nav-link" href="#finance" data-toggle="tab">Финансы</a></li>
                        <li class="nav-item">
                            <a @if ($chat['chat_status_id'] == 1) style="padding-right: 22px;"
                               @endif  class="position-relative nav-link" href="#chat" data-toggle="tab">
                                @if ($chat['chat_status_id'] == 1)
                                    <span style="right: 5px; top:11px;"
                                          class="position-absolute right badge badge-danger">!</span>
                                @endif
                                Чат по изданию
                            </a>
                        </li>
                    </ul>
                </div><!-- /.card-header -->

                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane active" id="inside">
                            <div class="row">
                                <div class="pr-2 col-sm-6">
                                    @if ($own_book['inside_type'] == 'by_system' || $own_book['inside_type'] == 'системой')
                                        <h3>Автор загрузил произведения из системы:</h3>
                                        @foreach($own_book->own_books_works as $work)
                                            <a href="{{route('social.work_page', $work['id'])}}" target="_blank">
                                                <h3>{{$loop->index + 1}}. {{$work->work['title']}}</h3>
                                            </a>
                                            <p>{{$work->work['text']}}</p>
                                        @endforeach
                                    @endif
                                    @if ($own_book['inside_type'] == 'by_file' || $own_book['inside_type'] == 'файлами')
                                        <h3>Автор загрузил файлы:</h3>
                                        @foreach($inside_files as $file)
                                            <h3 style="font-size: 1.3em">{{$loop->index + 1}}. <a
                                                    href="/{{$file['file']}}">{{substr($file['file'], strrpos($file['file'], '/', -10) + 1, 200) }}</a>
                                            </h3>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="border-left pl-2 col-sm-6">
                                    <h3>Готовый внутренний блок:</h3>

                                    <form action="{{ route('update_own_book_inside',$own_book['id']) }}"
                                          method="post"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex">
                                            <input type="file" name="inside_file"
                                                   class="d-none form-control custom-file-input" id="inside_file"
                                                   aria-describedby="myInput">

                                            <label id="label_inside_file"
                                                   class="position-relative form-control custom-file-label"
                                                   for="inside_file"
                                            >
                                                @if(file_exists($own_book['inside_file']))
                                                    {{substr($own_book['inside_file'], strrpos($own_book['inside_file'], '/') + 1)}}
                                                @else
                                                    Файл еще не загружен!
                                                @endif
                                            </label>
                                            <a target="_blank"
                                               style="height: 38px;display: flex; flex-direction: column; padding: 5px;"
                                               href="/{{$own_book['inside_file']}}" class="btn btn-app">
                                                <i style="font-size: 15px" class="fas fa-download"></i>
                                                Скачать
                                            </a>
                                        </div>
                                        <div class="align-items-center d-flex">
                                            <button type="submit" class="btn btn-primary">Обновить</button>
                                            @if(file_exists($own_book['inside_file']))
                                                <p class="mb-0 ml-3">


                                                    <b>Последнее
                                                        сохранение:</b> {{ Date::parse(date("d-M-Y H:i", filemtime($own_book['inside_file'])))->addHours(3)->format('j F Y | H:i') }}
                                                    <br>
                                                    @endif
                                                </p>


                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="border-top mt-3 pt-3 row">
                                <div class="border-right pr-3 col-sm-6">
                                    <h3>Аннотация книги</h3>
                                    <form action="{{ route('update_own_book_desc',$own_book['id']) }}"
                                          method="post"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <textarea style="font-size: 20px; min-height: 150px" type="text" name="desc"
                                                  class="mb-3 form-control" id="desc"
                                                  aria-describedby="myInput">@if($own_book['own_book_desc'])
                                                {{$own_book['own_book_desc']}}
                                            @else
                                                Еще не загружена
                                            @endif</textarea>

                                        <button type="submit" class="btn btn-primary">Сохранить</button>

                                    </form>
                                </div>
                                <div class="pr-2 col-sm-6">
                                    <div class="mt-3 d-flex justify-content-between align-items-end mb-3">
                                        <h3 class="m-0 d-inline">Исправления:</h3>
                                        <form
                                            class="d-flex flex-column justify-content-center align-items-center"
                                            style=" align-items: center;"
                                            action="{{ route('change_all_preview_comment_status',[$own_book['id'], 'inside']) }}"
                                            method="POST"
                                            enctype="multipart/form-data"
                                        >
                                            @csrf
                                            <button class="float-right border-0 " type="submit"
                                                    style="background: #fff0; color: #34b734 !important;">Отметить
                                                все
                                                выполненными
                                            </button>
                                        </form>
                                    </div>
                                    <table id="participants_table" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 1%;">Страница</th>
                                            <th style="width: 1%;">Создан</th>
                                            <th>Текст</th>
                                            <th style="width: 1%;">Статус</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($prev_comments_inside as $prev_comment)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$prev_comment['page']}}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ Date::parse($prev_comment['created_at'])->addHours(3)->format('j F H:i') }}
                                                </td>
                                                <td style="text-align:inherit">
                                                    {!! nl2br(e($prev_comment['text'])) !!}
                                                </td>
                                                <td class="d-flex flex-column justify-content-center align-items-center"
                                                    style="text-align: center;">
                                                    <form
                                                        class="d-flex flex-column justify-content-center align-items-center"
                                                        style=" align-items: center;"
                                                        action="{{ route('change_preview_comment_status',$prev_comment->id) }}"
                                                        method="POST"
                                                        enctype="multipart/form-data"
                                                    >
                                                        @csrf
                                                        @if($prev_comment['status_done'] === 0)
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
                            </div>
                        </div>

                        <div class="tab-pane" id="cover">
                            <div class="row">
                                <div class="col-sm-3 border-right">
                                    <h3>Обложка:</h3>
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-2d" type="button" role="tab"
                                                    aria-controls="nav-2d" aria-selected="true">2d
                                            </button>
                                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-3d" type="button" role="tab"
                                                    aria-controls="nav-3d" aria-selected="false">3d
                                            </button>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-2d" role="tabpanel"
                                             aria-labelledby="nav-2d-tab">
                                            @if (!$own_book['cover_2d'])
                                                <p class="m-3"><b>2d вариант не загружен!</b></p>
                                            @else
                                                <img class="m-2" style="width: 200px;"
                                                     src="/{{$own_book['cover_2d']}}"
                                                     alt="">
                                            @endif
                                        </div>
                                        <div class="tab-pane fade" id="nav-3d" role="tabpanel"
                                             aria-labelledby="nav-3d-tab">
                                            @if (!$own_book['cover_3d'])
                                                <p class="m-3"><b>3d вариант не загружен!</b></p>
                                            @else
                                                <img style="width: 250px;" src="/{{$own_book['cover_3d']}}" alt="">
                                            @endif
                                        </div>
                                    </div>


                                    <form action="{{ route('update_own_book_cover',$own_book['id']) }}"
                                          method="post"
                                          enctype="multipart/form-data">
                                        @csrf
                                        Обложка 2d

                                        <input type="file" name="cover_2d"
                                               class="d-none form-control custom-file-input" id="cover_2d"
                                               aria-describedby="myInput">

                                        <label id="label_cover_2d"
                                               class="position-relative form-control custom-file-label"
                                               for="cover_2d">
                                            {{substr($own_book['cover_2d'], strrpos($own_book['cover_2d'], '/') + 1)}}
                                        </label>
                                        Обложка 3d
                                        <input type="file" name="cover_3d"
                                               class="d-none form-control custom-file-input" id="cover_3d"
                                               aria-describedby="myInput">

                                        <label id="label_cover_3d"
                                               class="position-relative form-control custom-file-label"
                                               for="cover_3d">
                                            {{substr($own_book['cover_3d'], strrpos($own_book['cover_3d'], '/') + 1)}}
                                        </label>

                                        <button type="submit" class="btn btn-primary">Обновить</button>
                                    </form>
                                </div>
                                <div class="col-sm-9 pl-3">
                                    <div class="pb-2" style="border-bottom: 1px solid #dee2e6;">
                                        <h3 class="m-0 d-inline">Автор загрузил файлы обложки:</h3>
                                        @foreach($cover_files as $file)
                                            <h3 style="font-size: 1.3em">{{$loop->index + 1}}. <a
                                                    href="/{{$file['file']}}">{{substr($file['file'], strrpos($file['file'], '/', -10) + 1, 200) }}</a>
                                            </h3>
                                        @endforeach
                                        <div>
                                            @if ($own_book['cover_comment'])
                                                <h4 class="mt-2">Пожелания автора:</h4>
                                                <div class="mt-2 mb-4 p-2 border">
                                                    {!! nl2br(e($own_book['cover_comment'])) !!}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="pt-2">
                                        @if ($own_book['own_book_cover_status_id'] == 3 & count($prev_comments_cover) == 0)
                                            <h3 class="mt-3 d-inline">Исправления обложки</h3>
                                            <h4 class="mt-2">Автор отправил на исправления, но не указал ни одного
                                                исправления. Видимо, можно апрувить.</h4>
                                        @endif

                                        @if ( count($prev_comments_cover) == 0)
                                            <h3 class="mt-3 d-inline">Исправления обложки</h3>
                                            <h4 class="mt-2">Еще не было исправлений обложки</h4>
                                        @endif

                                        @if (count($prev_comments_cover) > 0)
                                            <div class="d-flex justify-content-between align-items-end mb-3">
                                                <h3 class="m-0 d-inline">Исправления обложки</h3>
                                                <form
                                                    class="d-flex flex-column justify-content-center align-items-center"
                                                    style=" align-items: center;"
                                                    action="{{ route('change_all_preview_comment_status',[$own_book['id'], 'cover']) }}"
                                                    method="POST"
                                                    enctype="multipart/form-data"
                                                >
                                                    @csrf
                                                    <button class="float-right border-0 " type="submit"
                                                            style="background: #fff0; color: #34b734 !important;">
                                                        Отметить
                                                        все
                                                        выполненными
                                                    </button>
                                                </form>

                                            </div>
                                            <table id="participants_table"
                                                   class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Текст</th>
                                                    <th style="width: 1%;">Статус</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($prev_comments_cover as $prev_comment)
                                                    <tr>
                                                        <td style="text-align: inherit;">
                                                            {!! nl2br(e($prev_comment['text'])) !!}
                                                        </td>
                                                        <td class="d-flex flex-column justify-content-center align-items-center"
                                                            style="text-align: center;">
                                                            <form
                                                                class="d-flex flex-column justify-content-center align-items-center"
                                                                style=" align-items: center;"
                                                                action="{{ route('change_preview_comment_status',$prev_comment->id) }}"
                                                                method="POST"
                                                                enctype="multipart/form-data"
                                                            >
                                                                @csrf
                                                                @if($prev_comment['status_done'] === 0)
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
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="print">
                            @if($own_book->printorder['books_needed'] ?? 0 > 0)
                                <div style="max-width: 600px;">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td style="font-weight: bold">ID Printorder</td>
                                            <td>
                                                {{$own_book->printorder['id']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Страниц</td>
                                            <td>
                                                {{$own_book['pages']}}
                                                (цветных: {{$own_book->printorder['color_pages']}})
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Экземпляров</td>
                                            <td>
                                                {{$own_book->printorder['books_needed']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Отправить на адрес</td>
                                            <td>
                                                {{print_address($own_book->printorder['id'])}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Формат обложки:</td>
                                            <td>
                                                @if($own_book->printorder['cover_type'] == 'soft')
                                                    Мягкая
                                                @elseif($own_book->printorder['cover_type'] == 'hard')
                                                    <b style="color: red">Твердая</b>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Цветность ВБ:</td>
                                            <td>
                                                @if($own_book->printorder['color_pages'] > 0)
                                                    <b style="color: red">Цветной</b>
                                                @elseif($own_book->printorder['color_pages'] == 0)
                                                    Черно-белый
                                                @endif
                                            </td>
                                        </tr>


                                        </tbody>
                                    </table>
                                    <div class="d-flex align-items-center">
                                        <h4 class="m-0">Трек номер: <a target="_blank"
                                                                       href="https://www.pochta.ru/tracking#{{$own_book->printorder['track_number']}}">{{$own_book->printorder['track_number']}}</a>
                                        </h4>
                                        <div style="display: none" id="change_book_track_number_form_wrap">
                                            <form class="d-flex ml-3" style=" align-items: center;"
                                                  action="{{ route('update_own_book_track_number',$own_book['id']) }}"
                                                  method="POST"
                                                  enctype="multipart/form-data"
                                            >
                                                @csrf

                                                <input value="{{$own_book['user_id']}}" type="text" name="user_id"
                                                       style="display:none" class="form-control"
                                                       id="user_id">

                                                <input value="{{$own_book['id']}}" type="text" name="own_book_id"
                                                       style="display:none" class="form-control"
                                                       id="own_book_id">

                                                <input class="form-control" name="track_number" type="text">

                                                <button id="btn-submit" type="submit"
                                                        style="height: fit-content; max-height: 30px; max-width:150px;"
                                                        data-status-from="{{$own_book->printorder['track_number']}}"
                                                        class="ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                                >
                                                    Сохранить
                                                </button>
                                            </form>
                                        </div>
                                        <button style="border:none; width: auto; padding: 3px 10px; max-width:150px"
                                                data-form="change_book_track_number" type="button"
                                                class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                                        >
                                            <i style="font-size: 20px;" class="fa fa-edit"></i>

                                        </button>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <h4 class="m-0">Стоимость пересылки: {{$own_book->printorder['send_price']}}
                                            руб.
                                        </h4>
                                        <div style="display: none" id="change_book_send_price_form_wrap">
                                            <form class="d-flex ml-3" style=" align-items: center;"
                                                  action="{{ route('update_own_book_send_price',$own_book['id']) }}"
                                                  method="POST"
                                                  enctype="multipart/form-data"
                                            >
                                                @csrf

                                                <input value="{{$own_book['user_id']}}" type="text" name="user_id"
                                                       style="display:none" class="form-control"
                                                       id="user_id">

                                                <input value="{{$own_book['id']}}" type="text" name="own_book_id"
                                                       style="display:none" class="form-control"
                                                       id="own_book_id">

                                                <input class="form-control" name="send_price" type="text">

                                                <button id="btn-submit" type="submit"
                                                        style="height: fit-content; max-height: 30px; max-width:150px;"
                                                        data-status-from="{{$own_book->printorder['send_price']}}"
                                                        class="ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                                >
                                                    Сохранить
                                                </button>
                                            </form>
                                        </div>
                                        <button style="border:none; width: auto; padding: 3px 10px; max-width:150px"
                                                data-form="change_book_send_price" type="button"
                                                class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                                        >
                                            <i style="font-size: 20px;" class="fa fa-edit"></i>

                                        </button>
                                    </div>

                                    <h4 class="m-0">
                                        Статус:
                                        @if($own_book->printorder['paid_at'])
                                            <span
                                                style="color:#00cd00;">оплачена {{Date::parse($own_book->printorder['paid_at'])->format('j F H:i')}}</span>
                                        @else
                                            <span style="color:#e54c4c;">не оплачена (ждет с {{Date::parse($own_book->printorder['updated_at'])->addHours(3)->format('j F H:i')}})</span>
                                        @endif

                                    </h4>
                                </div>
                            @else
                                <h2>Печать не нужна автору!</h2>
                            @endif
                        </div>

                        <div class=" tab-pane" id="finance">
                            <div class="d-flex justify-content-around flex-wrap">
                                <div>
                                    <div class="justify-content-between d-flex">
                                        <h2>Общий расчет</h2>

                                        <div class="d-flex">
                                            <a class="m-0 p-0 d-flex align-items-center btn">
                                                <i id="change_finance_show" style="font-size: 22px;"
                                                   class="fas fa-edit"></i>
                                            </a>

                                            <a id="change_finance_save" style="display: none !important;"
                                               class="m-0 p-0 d-flex align-items-center btn">
                                                <i style="font-size: 25px;"
                                                   class="fas fa-save"></i>
                                            </a>

                                            <a href="{{route('own_book_page')}}" target="_blank"
                                               id="change_finance_calc" style="display: none !important;"
                                               class="ml-3 p-0 d-flex align-items-center btn">
                                                <i style="font-size: 20px;"
                                                   class="fas fa-calculator"></i>
                                            </a>

                                            <a id="change_finance_hide" style="display: none !important;"
                                               class="ml-3 p-0 d-flex align-items-center btn">
                                                <i style="font-size: 27px;"
                                                   class="fas fa-times"></i>
                                            </a>


                                        </div>
                                    </div>
                                    <style>
                                        .change_finance_block {
                                            display: none;
                                        }
                                    </style>
                                    <div style="max-width: 600px;">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <td style="font-weight: bold">ISBN</td>
                                                <td>
                                                    300 руб.
                                                </td>
                                            </tr>
                                            <form id="save_own_book_prices"
                                                  action="{{ route('update_own_book_prices',$own_book['id']) }}"
                                                  method="POST"
                                                  enctype="multipart/form-data"
                                            >
                                                @csrf
                                                <tr>
                                                    <td style="font-weight: bold">Стоимость дизайна</td>
                                                    <td>
                                                        <div class="change_finance_text">
                                                            @if($own_book['text_design_price'] === 0)
                                                                не нужна
                                                            @else
                                                                {{$own_book['text_design_price']}} руб.
                                                            @endif

                                                        </div>
                                                        <input type="number"
                                                               value="{{$own_book['text_design_price']}}"
                                                               id="text_design_price"
                                                               name="text_design_price"
                                                               class="form-control change_finance_block">
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold">Стоимость проверки текста</td>
                                                    <td>
                                                        <div class="change_finance_text">
                                                            @if($own_book['text_check_price'] === 0)
                                                                не нужна
                                                            @else
                                                                {{$own_book['text_check_price']}} руб.
                                                            @endif
                                                        </div>
                                                        <input type="number"
                                                               value="{{$own_book['text_check_price']}}"
                                                               id="text_check_price"
                                                               name="text_check_price"
                                                               class="form-control change_finance_block">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold">Стоимость обложки</td>
                                                    <td>
                                                        <div class="change_finance_text">
                                                            @if($own_book['cover_price'] === 0)
                                                                готова от автора
                                                            @else
                                                                {{$own_book['cover_price']}} руб.
                                                            @endif
                                                        </div>
                                                        <input type="number"
                                                               value="{{$own_book['cover_price']}}"
                                                               id="cover_price"
                                                               name="cover_price"
                                                               class="form-control change_finance_block">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold">Стоимость печати</td>
                                                    <td>
                                                        <div class="change_finance_text">
                                                            @if($own_book['print_price'] === 0)
                                                                не нужна
                                                            @else
                                                                {{$own_book['print_price']}} руб.
                                                            @endif
                                                        </div>
                                                        <input type="number"
                                                               value="{{$own_book['print_price']}}"
                                                               id="print_price"
                                                               name="print_price"
                                                               class="form-control change_finance_block">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="font-weight: bold">Стоимость продвижения</td>
                                                    <td>
                                                        <div class="change_finance_text">
                                                            @if($own_book['promo_price'] === 0)
                                                                не нужна
                                                            @else
                                                                {{$own_book['promo_price']}} руб.
                                                            @endif
                                                        </div>
                                                        <input type="number"
                                                               value="{{$own_book['promo_price']}}"
                                                               id="promo_price"
                                                               name="promo_price"
                                                               class="form-control change_finance_block">
                                                    </td>
                                                </tr>

                                                <tr class="bg-info">
                                                    <td style="font-weight: bold">ИТОГО</td>
                                                    <td>
                                                        {{$own_book['total_price']}} руб.
                                                    </td>
                                                </tr>
                                                <tr class="bg-info">
                                                    <td style="font-weight: bold">Приблизительные затраты</td>
                                                    <td>
                                                        @if($own_book['print_price'] === 0)
                                                            0 руб.
                                                        @else
                                                            {{$own_book['pages'] * $own_book->printorder['books_needed']}}
                                                            руб.
                                                            ({{$own_book['pages']}}
                                                            стр.; {{$own_book->printorder['books_needed'] ?? 0}}
                                                            экз.)
                                                        @endif
                                                        {{-- {{$own_book->printorder['books_needed'] * 80}} руб.--}}
                                                    </td>
                                                </tr>
                                                <tr style="font-size: 22px;" class="bg-success">
                                                    <td style="font-weight: bold">Итого рибыль</td>
                                                    <td>
                                                        {{$own_book['total_price'] - ($own_book['pages'] * ($own_book->printorder['books_needed'] ?? 0))}}
                                                        руб.
                                                    </td>
                                                </tr>
                                            </form>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div>
                                    <h2>Транзакции по этой книге</h2>
                                    <div>
                                        <table id="participants_table" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="width: 1%;">Создан</th>
                                                <th style="width: 1%;">Статус</th>
                                                <th style="width: 1%;">Сумма</th>
                                                <th>Описание</th>
                                                <th style="width: 1%;">YooID</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($transactions as $transaction)
                                                <tr>
                                                    <td style="text-align: center;">
                                                        {{ Date::parse($transaction['created_at'])->addHours(3)->format('j F H:i') }}

                                                    </td>
                                                    <td style="
                                                        color:
                                                    @if($transaction['status'] === 'CONFIRMED') #09c73a
                                                    @elseif($transaction['status'] === 'CREATED') #ff2929
                                                    @endif;
                                                        text-align: center;">
                                                        {{$transaction['status']}}
                                                    </td>
                                                    <td style="text-align:inherit">
                                                        {{$transaction['amount']}}
                                                    </td>
                                                    <td class="d-flex flex-column justify-content-center align-items-center"
                                                        style="text-align: center;">
                                                        {{$transaction['description']}}
                                                    </td>
                                                    <td style="text-align:inherit">
                                                        {{$transaction['yoo_id']}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="chat">
                            @if ($own_book['user_id'])
                                <div class="mb-3 d-flex align-items-center">
                                    <h4 style="margin-bottom: 0 !important;" class="">Статус чата:
                                        <i id="change_chat_status_text">{{$chat->chat_status['status']}}</i>
                                    </h4>
                                    <div style="display: none" id="change_chat_status_form_wrap">
                                        <form class="d-flex ml-3" style=" align-items: center;"
                                              action="{{ route('change_chat_status', $chat['id']) }}"
                                              method="POST"
                                              enctype="multipart/form-data"
                                        >
                                            @csrf

                                            <select id="chat_status_id" class="form-control" name="chat_status_id">
                                                @foreach($chat_statuses as $chat_status)
                                                    <option
                                                        @if($chat['chat_status_id'] == $chat_status['id']) selected
                                                        @endif value="{{$chat_status['id']}}">{{$chat_status['status']}}</option>
                                                @endforeach
                                            </select>

                                            <button id="btn-submit" type="submit"
                                                    style="height: fit-content; max-height: 30px; max-width:150px;"
                                                    data-status-from="{{$chat->chat_status['status']}}"
                                                    class="change_status ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                            >
                                                Сохранить
                                            </button>
                                        </form>
                                    </div>
                                    <button style="border: none; width: auto; padding: 3px 10px;max-width:150px"
                                            data-form="change_chat_status" type="button"
                                            class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                                    >
                                        <i style="font-size: 20px;" class="fa fa-edit"></i>

                                    </button>
                                </div>

                                <div style="width:100%; max-width: 2000px" class="chat">
                                    {{-- Чат книги --}}
                                    <div id="book_chat" style="margin: 0 0 30px 0; width: 100%; max-width: 2000px;"
                                         class="chat">
                                        <div style="margin: 0; width: 100%; max-width: 2000px;" class="container">

                                            @livewire('account.chat.chat',['chat_id'=>$chat->id,
                                            'new_chat_user_id'=>null])

                                        </div>
                                    </div>
                                    {{-- // Чат книги --}}
                                </div>
                            @else
                                <h2>Книга старая, опция недостпуна!</h2>
                            @endif
                        </div>


                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </section>
    <script>
        $('#change_finance_show').on('click', function () {

            $(this).hide();

            $('.change_finance_text').each(function () {
                $(this).hide();
            })

            $('.change_finance_block').each(function () {
                $(this).show();
            })

            $('#change_finance_hide').show();
            $('#change_finance_save').show();
            $('#change_finance_calc').show();
        })

        $('#change_finance_hide').on('click', function () {
            $(this).attr('style', 'display: none !important');
            $('#change_finance_save').attr('style', 'display: none !important');
            $('#change_finance_calc').attr('style', 'display: none !important');

            $('.change_finance_text').each(function () {
                $(this).show();
            })

            $('.change_finance_block').each(function () {
                $(this).hide();
            })

            $('#change_finance_show').show();
        })


        $('#change_finance_save').on('click', function () {
            $('#save_own_book_prices').submit();
        })

    </script>
@endsection


@section('page-js')

@endsection
