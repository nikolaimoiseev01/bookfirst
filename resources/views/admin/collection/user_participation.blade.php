@extends('layouts.admin_layout')
@section('title', 'Добавить книгу')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">


            <h1 class="mt-2">
                Страница автора: <a
                    href="{{route('user_page', $participation['user_id'])}}"><i>{{$participation['name']}} {{$participation['surname']}}
                        @if($participation['nickname'])
                            (псевдоним: {{$participation['nickname']}})
                        @endif
                    </i></a>
            </h1>

            <div class="mt-2 d-flex align-items-center">

                <h1 class="mt-2">
                    В сборинке: <i><a id="change_user_collection_text"
                                      href="{{route('collection.edit', $participation->collection['id'])}}">{{$participation->collection['title']}}</a></i>
                </h1>

                <div style="display: none" id="change_user_collection_form_wrap">
                    <form class="d-flex ml-3" style="align-items: center;"
                          action="{{ route('change_user_collection',$participation['id']) }}" method="POST"
                          enctype="multipart/form-data"
                    >
                        @csrf

                        <select style="padding: 0 0 0 10px; height: 33px; width: fit-content;"
                                id="collection_id_to_update" class="form-control"
                                name="collection_id_to_update">
                            @foreach($collections_to_update as $collection_to_update)
                                <option
                                    @if($collection_to_update['id'] === $participation['collection_id']) selected
                                    @endif value="{{$collection_to_update['id']}}">{{$collection_to_update['title']}}</option>
                            @endforeach
                        </select>

                        <button id="btn-submit" type="submit"
                                style="height: fit-content; max-height: 30px; max-width:150px;"
                                data-status-from="{{$participation->collection['title']}}"
                                class="change_status ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                        >
                            Сохранить
                        </button>
                    </form>
                </div>

                <button style="display: flex; border: none; width: auto; padding: 3px 10px;max-width:150px"
                        data-form="change_user_collection" type="button"
                        class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                >
                    <i style="font-size: 20px;" class="fa fa-edit"></i>

                </button>
            </div>

            <div class="mt-2 d-flex align-items-center">
                <h1 style="margin-bottom: 0 !important;" class="">Статус участия:
                    <i id="change_pat_status_text">{{$participation->pat_status['pat_status_title']}}</i>
                </h1>
                <div style="display: none" id="change_pat_status_form_wrap">
                    <form class="d-flex ml-3" style=" align-items: center;"
                          action="{{ route('change_user_status',$participation['id']) }}"
                          method="POST"
                          enctype="multipart/form-data"
                    >
                        @csrf

                        <input value="{{$participation['user_id']}}" type="text" name="user_id"
                               style="display:none" class="form-control"
                               id="user_id">

                        <input value="{{$participation['id']}}" type="text" name="pat_id"
                               style="display:none" class="form-control"
                               id="pat_id">
                        <select id="pat_status_id" class="form-control" name="pat_status_id">
                            @foreach($pat_statuses as $pat_status)
                                <option @if($participation['pat_status_id'] === $pat_status['id']) selected
                                        @endif value="{{$pat_status['id']}}">{{$pat_status['pat_status_title']}}</option>
                            @endforeach
                        </select>

                        <button id="btn-submit" type="submit"
                                style="height: fit-content; max-height: 30px; max-width:150px;"
                                data-status-from="{{$participation->pat_status['pat_status_title']}}"
                                class="change_status ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                        >
                            Сохранить
                        </button>
                    </form>
                </div>
                <button style="border: none; width: auto; padding: 3px 10px;max-width:150px"
                        data-form="change_pat_status" type="button"
                        class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                >
                    <i style="font-size: 20px;" class="fa fa-edit"></i>

                </button>


            </div>

            <form style="gap: 20px;" class="d-flex flex-wrap  align-items-center mt-2 gap-2" action="{{ route('add_participation_comment',$participation['id']) }}" method="POST"
                  enctype="multipart/form-data"
            >
                @csrf
                <h1>Комментарий: </h1>
                <div id="comment_text">
                    {!! $participation['comment'] !!}
                </div>
                <div style="display: none;" id="comment_text_edit">
                            <textarea name="comment" id="summernote"
                                      name="editordata">{{$participation['comment']}}</textarea>
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
                @push('scripts')
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
                                fontSizes: ['18', '20', '22', '24']
                            });
                        });

                        $('#edit_comment_button').on('click', function (e) {
                            e.preventDefault()
                            $('#comment_text_edit').toggle();
                            $('#comment_text').toggle();

                        })
                    </script>
                @endpush
            </form>

        </div><!-- /.container-fluid -->

    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#works" data-toggle="tab">Произведения
                                для участия</a></li>
                        <li class="nav-item"><a class="test nav-link" href="#info" data-toggle="tab">Информация</a></li>
                        <li class="nav-item"><a class="nav-link" href="#finance" data-toggle="tab">Финансы</a></li>
                        <li class="nav-item">
                            <a @if ($chat['chat_status_id'] == 1) style="padding-right: 22px;"
                               @endif  class="position-relative nav-link" href="#chat" data-toggle="tab">
                                @if ($chat['chat_status_id'] == 1)<span style="right: 5px; top:11px;"
                                                                        class="position-absolute right badge badge-danger">!</span>@endif
                                Чат
                            </a>
                        </li>
                    </ul>
                </div><!-- /.card-header -->

                <div class="card-body">
                    <div class="tab-content">
                        {{App::setLocale('ru')}}
                        <div class="tab-pane active" id="works">
                            @foreach($participation->participation_work as $work)
                                <h3>{{$loop->index + 1}}. {{$work->work['title']}}</h3>
                                <p style="color:grey">Загружено {{$work->work['upload_type']}}
                                    : {{ Date::parse($work->work['created_at'])->addHours(3)->format('j F Y') }}</p>
                                <p>{!! nl2br($work->work['text']) !!}</p>
                            @endforeach
                        </div>
                        <style>
                            @media screen and (max-width: 650px) {
                                .info_tables {
                                    flex-direction: column !important;
                                }

                                .info_tables .col-6 {
                                    max-width: 100% !important;
                                    flex: inherit !important;
                                    max-width: 100% !important;
                                }
                            }
                        </style>
                        <div class="tab-pane" id="info">
                            @if($participation['pat_status_id'] > 1)
                                <h4>Заявка была принята: {{$participation['approved_at']}}</h4>
                            @endif
                            @if($participation->printorder['track_number'] ?? null)
                                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                    <h4 style="margin: auto 0;">
                                        Трек номер:
                                        <a target="_blank" class="link-dark"
                                           href="https://www.pochta.ru/tracking#{{$participation->printorder['track_number']}}">
                                            {{$participation->printorder['track_number']}}
                                        </a>
                                    </h4>
                                    <p style="margin: 0 0 0 10px;">
                                        @if($participation->printorder['paid_at'])
                                            <span style="color:#00cd00;">(Доствка оплачена {{$participation->printorder['paid_at']}})</span>
                                        @else
                                            <span style="color:#e54c4c;">(Доствка НЕ оплачена)</span>
                                        @endif
                                    </p>
                                </div>
                            @endif

                            <div class="info_tables row align-items-start">
                                <div class="col-6">
                                    <h4>Участие</h4>
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td style="font-weight: bold">Фио</td>
                                            <td>
                                                {{$participation['name']}} {{$participation['surname']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Псевдоним</td>
                                            <td>
                                                {{$participation['nickname']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Количество работ</td>
                                            <td>
                                                {{$participation['works_number']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Строк</td>
                                            <td>
                                                {{$participation['rows']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Страниц</td>
                                            <td>
                                                {{$participation['pages']}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <h4>Печать</h4>

                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td style="font-weight: bold">ID Printorder</td>
                                            <td>
                                                {{$participation->printorder['id'] ?? 'Нет печати'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Экземпляров</td>
                                            <td>
                                                {{$participation->printorder['books_needed'] ?? 'Нет печати'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Имя</td>
                                            <td>
                                                {{$participation->printorder['send_to_name'] ?? 'Нет печати'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Адресс</td>
                                            <td>
                                                {{$participation->printorder['send_to_address'] ?? 'Нет печати'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Телефон</td>
                                            <td>
                                                {{$participation->printorder['send_to_tel'] ?? 'Нет печати'}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class=" tab-pane" id="finance">
                            <div class="d-flex justify-content-around flex-wrap">
                                <div>
                                    <h2>Общий расчет</h2>
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td style="font-weight: bold">Стоимость участия</td>
                                            <td>
                                                {{$participation['part_price']}} руб.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Стоимость экземпляров</td>
                                            <td>
                                                {{$participation['print_price']}} руб.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Стоимость проверки</td>
                                            <td>
                                                {{$participation['check_price']}} руб.
                                            </td>
                                        </tr>
                                        <tr class="bg-info">
                                            <td style="font-weight: bold">Получили средств</td>
                                            <td>
                                                {{$participation['total_price']}} руб.
                                            </td>
                                        </tr>
                                        <tr class="bg-info">
                                            <td style="font-weight: bold">Приблизительные затраты</td>
                                            <td>
                                                {{$participation->printorder['books_needed'] ?? 0 * 80}} руб.
                                            </td>
                                        </tr>
                                        <tr style="font-size: 22px;" class="bg-success">
                                            <td style="font-weight: bold">Итого рибыль</td>
                                            <td>
                                                {{$participation['total_price'] - ($participation->printorder['books_needed'] ?? 0 * 80)}}
                                                руб.
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
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
                                                <option @if($chat['chat_status_id'] == $chat_status['id']) selected
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
                                        @livewire('chat',['chat_id'=>$chat->id])
                                    </div>
                                </div>
                                {{-- // Чат книги --}}
                            </div>
                        </div>


                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </section>

    {{--    <script>--}}
    {{--        $('#change_pat_status_button').click(function () {--}}
    {{--            // alert(5);--}}
    {{--            $('#status_text').toggle();--}}
    {{--            $('#status_form').toggle();--}}

    {{--            if ($('#status_form').is(":visible")) {--}}
    {{--                $('#change_pat_status_button').html('<i class="mr-2 fa fa-times"></i> Отменить');--}}
    {{--            } else {--}}
    {{--                $('#change_pat_status_button').html('<i class="mr-2 fa fa-edit"></i>  Изменить статус');--}}
    {{--            }--}}
    {{--        })--}}
    {{--    </script>--}}


    {{--    <script>--}}
    {{--        $('#chat_add').click(function () {--}}
    {{--            $('.chat-create-admin').toggle();--}}

    {{--            if ($('.chat-create-admin').is(":visible")) {--}}
    {{--                $('#chat_add').html('<i class="mr-2 fa fa-times"></i> Отменить');--}}
    {{--            } else {--}}
    {{--                $('#chat_add').html(' <i class="mr-2 fa fa-plus"></i> Создать чат');--}}
    {{--            }--}}
    {{--        })--}}
    {{--    </script>--}}

@endsection


@section('page-js')



@endsection
