@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <link rel="stylesheet" href="/css/chat.css">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="card">
                <div class="d-flex align-items-center bg-gradient-lightblue card-header">
                    <h3 class=" mt-2">
                        <b style="font-size: 28px;">{{$own_book['author']}}: {{$own_book['title']}}</b>
                    </h3>
                    <i style="margin-left: auto;">

                        <span style="font-size: 18px;">кем создан:
                            @if ($own_book['user_id'])
                            <a style="color: #a8fffb"  href="{{route('user_page', $own_book['user_id'])}}" >
                                <i>{{$own_book->user['name']}}{{$own_book->user['surname']}}</i>
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
                            <h4 style="margin-bottom: 0 !important;" class="">Статус внутреннего блока:
                                <i id="change_book_inside_status_text">{{$own_book->own_book_inside_status['status_title']}}</i>
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
                            <i id="change_promo_text">@if($own_book['promo_price'])за {{$own_book['promo_price']}}
                                руб.@else не требуется @endif</i>
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


                </div>
            </div>
        </div><!-- /.container-fluid -->


    </div>
    <!-- /.content-header -->

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
                        <li class="nav-item"><a class="nav-link" href="#chat" data-toggle="tab">Чат по изданию</a></li>
                    </ul>
                </div><!-- /.card-header -->

                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane active" id="inside">
                            <div class="row">
                                <div class="pr-2 col-sm-6">
                                    @if ($own_book['inside_type'] == 'системой')
                                        <h3>Автор загрузил произведения из системы:</h3>
                                        @foreach($own_book->own_books_works as $work)
                                            <h3>{{$loop->index + 1}}. {{$work->work['title']}}</h3>
                                            <p>{{$work->work['text']}}</p>
                                        @endforeach
                                    @endif
                                    @if ($own_book['inside_type'] == 'файлами')
                                        <h3>Автор загрузил файлы:</h3>
                                        @foreach($inside_files as $file)
                                            <h3 style="font-size: 1.3em">{{$loop->index + 1}}. {{$file['file']}}</h3>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="border-left pl-2 col-sm-6">

                                    @if ($own_book['own_book_inside_status_id'] == 9)
                                        <div class="align-items-center d-flex">
                                            <h3 style="margin-bottom: 0; font-size: 28px; color: #14b514">Готовые файлы
                                                от автора лежат в его папке :)</h3>
                                            <button id="chat_add"
                                                    style="height: 30px; border-radius: 10px; width: fit-content;"
                                                    class="ml-3 d-flex align-items-center btn btn-outline-danger btn-block btn-flat">
                                                Отправить на доработку
                                            </button>
                                        </div>
                                    @else
                                        <h3>Готовый внутренний блок:</h3>
                                        @if(file_exists($own_book['inside_file']))

                                            <h3 style="font-size: 23px; color: #14b514">Файл верстки успешно найден в
                                                папке книги, супер!)</h3>
                                        @else
                                            <h3 style="font-size: 23px; color: red">!!ОСТОРОЖНО!! Я не могу найти PDF
                                                файл внутреннего блока! Ищу файл: {{$own_book['inside_file']}}</h3>
                                        @endif
                                    @endif
                                </div>
                            </div>


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
                            <table id="participants_table" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 1%;">Страница</th>
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
                                        <td style="text-align:inherit">
                                            {{$prev_comment['text']}}
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
                                            @if ($own_book['cover_2d'] === '')
                                                <p class="m-3"><b>2d вариант не загружен!</b></p>
                                            @else
                                                <img class="m-2" style="width: 200px;" src="/{{$own_book['cover_2d']}}"
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


                                    <form action="{{ route('update_own_book_cover',$own_book['id']) }}" method="post"
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

                                    @if ($own_book['own_book_cover_status_id'] == 9)
                                        <div class="align-items-center d-flex">
                                            <h3 style="margin-bottom: 0; font-size: 28px; color: #14b514">Готовые файлы
                                                от автора лежат в его папке :)</h3>
                                            <button id="chat_add"
                                                    style="height: 30px; border-radius: 10px; width: fit-content;"
                                                    class="ml-3 d-flex align-items-center btn btn-outline-danger btn-block btn-flat">
                                                Отправить на доработку
                                            </button>
                                        </div>
                                    @endif

                                    @if ($own_book['cover_comment'])
                                        <h3 class="mb-2 d-inline">Необходимо создание обложки</h3>
                                        <h4 class="mt-2">Пожелания автора:</h4>
                                        <div class="mt-2 mb-4 p-2 border">
                                            {{$own_book['cover_comment']}}
                                        </div>
                                    @endif

                                    @if ($own_book['own_book_cover_status_id'] == 3 & count($prev_comments_cover) == 0)
                                        <h3 class="m-0 d-inline">Исправления обложки</h3>
                                        <h4 class="mt-2">Автор отправил на исправления, но не указал ни одного
                                            исправления. Видимо, можно апрувить.</h4>
                                    @endif

                                    @if ( count($prev_comments_cover) == 0)
                                        <h3 class="m-0 d-inline">Исправления обложки</h3>
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
                                                        style="background: #fff0; color: #34b734 !important;">Отметить
                                                    все
                                                    выполненными
                                                </button>
                                            </form>

                                        </div>
                                        <table id="participants_table" class="table table-bordered table-hover">
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
                                                        {{$prev_comment['text']}}
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

                        <div class="tab-pane" id="print">
                            @if($own_book->printorder['books_needed'] ?? 0 > 0)
                                <div style="max-width: 600px;">
                                    <table class="table table-bordered">
                                        <tbody>
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
                                            <td style="font-weight: bold">Фио адресата</td>
                                            <td>
                                                {{$own_book->printorder['send_to_name']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Адрес</td>
                                            <td>
                                                {{$own_book->printorder['send_to_address']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Телефон</td>
                                            <td>
                                                {{$own_book->printorder['send_to_tel']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Формат обложки:</td>
                                            <td>
                                                @if($own_book->printorder['cover_type'] = 'soft')
                                                    Мягкая,
                                                @elseif($own_book->printorder['cover_type'] = 'hard')
                                                    Твердая,
                                                @endif
                                                @if($own_book->printorder['cover_color'] = 1)
                                                    цветная
                                                @elseif($own_book->printorder['cover_color'] = 0)
                                                    черно-белая
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Цветность ВБ:</td>
                                            <td>
                                                @if($own_book->printorder['cover_type'] = 1)
                                                    Цветной
                                                @elseif($own_book->printorder['cover_type'] = 0)
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
                                </div>
                            @else
                                <h2>Печать не нужна автору!</h2>
                            @endif
                        </div>

                        <div class=" tab-pane" id="finance">
                            <div style="max-width: 600px;">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td style="font-weight: bold">ISBN</td>
                                        <td>
                                            500 руб.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold">Стоимость дизайна</td>
                                        <td>
                                            @if($own_book['text_design_price'] === 0)
                                                не нужна
                                            @else
                                                {{$own_book['text_design_price']}} руб.
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold">Стоимость проверки текста</td>
                                        <td>
                                            @if($own_book['text_check_price'] === 0)
                                                не нужна
                                            @else
                                                {{$own_book['text_check_price']}} руб.
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold">Стоимость обложки</td>
                                        <td>
                                            @if($own_book['cover_price'] === 0)
                                                готова от автора
                                            @else
                                                {{$own_book['cover_price']}} руб.
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold">Стоимость печати</td>
                                        <td>
                                            @if($own_book['print_price'] === 0)
                                                не нужна
                                            @else
                                                {{$own_book['print_price']}} руб.
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-weight: bold">Стоимость продвижения</td>
                                        <td>
                                            @if($own_book['promo_price'] === 0)
                                                не нужна
                                            @else
                                                {{$own_book['promo_price']}} руб.
                                            @endif
                                        </td>
                                    </tr>

                                    <tr class="bg-info">
                                        <td style="font-weight: bold">Получили средств</td>
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
                                                {{$own_book['pages'] * 4}} руб.
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
                                            {{$own_book['total_price'] - ($own_book['pages'] * 4)}}
                                            руб.
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

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
                                                <option @if($chat['chat_status_id'] == $chat_status['id']) selected @endif value="{{$chat_status['id']}}">{{$chat_status['status']}}</option>
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

                                        @livewire('chat',['chat_id'=>$chat->id])

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

@endsection


@section('page-js')

@endsection
