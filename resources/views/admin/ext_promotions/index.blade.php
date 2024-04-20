@extends('layouts.admin_layout')
@section('title', "Продвижение {$ext_promotion['id']}")
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">


            <h1 class="mt-2">
                Страница автора:
                @role('admin')
                <a href="{{route('user_page', $ext_promotion['user_id'])}}">{{$ext_promotion->user['name']}} {{$ext_promotion->user['surname']}}</a>
                @else
                    {{$ext_promotion->user['name']}} {{$ext_promotion->user['surname']}}
                    @endrole
            </h1>

            <div class="mt-2 d-flex align-items-center">

                <h1 class="mt-2">
                    На сайте: <i>{{$ext_promotion['site']}}</i>
                </h1>
            </div>

            <div class="mt-2 d-flex align-items-center">
                <h1 style="margin-bottom: 0 !important;" class="">Статус продвижения:
                    <i id="change_pat_status_text">{{$ext_promotion->ext_promotion_status['title']}}</i>
                </h1>
                <div style="display: none" id="change_pat_status_form_wrap">
                    <form class="d-flex ml-3" style=" align-items: center;"
                          action="{{ route('change_ext_promotion_status',$ext_promotion['id']) }}"
                          method="POST"
                          enctype="multipart/form-data"
                    >
                        @csrf

                        <input value="{{$ext_promotion['user_id']}}" type="text" name="user_id"
                               style="display:none" class="form-control"
                               id="user_id">

                        <input value="{{$ext_promotion['id']}}" type="text" name="ext_promotion_id"
                               style="display:none" class="form-control"
                               id="ext_promotion_id">
                        <select id="ext_promotion_status_id" class="form-control" name="ext_promotion_status_id">
                            @foreach($ext_promotion_statuses as $ext_promotion_status)
                                <option
                                    @if($ext_promotion['ext_promotion_status_id'] === $ext_promotion_status['id']) selected
                                    @endif value="{{$ext_promotion_status['id']}}">{{$ext_promotion_status['title']}}</option>
                            @endforeach
                        </select>

                        <button id="btn-submit" type="submit"
                                style="height: fit-content; max-height: 30px; max-width:150px;"
                                data-status-from="{{$ext_promotion->ext_promotion_status['title']}}"
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

            <form style="gap: 20px;" class="d-flex flex-wrap  align-items-center mt-2 gap-2"
                  action="{{ route('add_ext_promotion_comment',$ext_promotion['id']) }}" method="POST"
                  enctype="multipart/form-data"
            >
                @csrf
                <h1>Комментарий: </h1>
                <div id="comment_text">
                    {!! $ext_promotion['comment'] !!}
                </div>
                <div style="display: none;" id="comment_text_edit">
                            <textarea name="comment" id="summernote"
                                      name="editordata">{{$ext_promotion['comment']}}</textarea>
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
                                    // [groupName, [list.blade.php of button]]
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
                        <li class="nav-item"><a class="test nav-link active" href="#info"
                                                data-toggle="tab">Информация</a></li>
                        <li class="nav-item"><a class="test nav-link" href="#finance" data-toggle="tab">Финансы</a></li>
                        <li class="nav-item">
                            <a @if ($chat['chat_status_id'] == 1) style="padding-right: 22px;"
                               @endif  class="position-relative nav-link" href="#chat" data-toggle="tab">
                                @if ($chat['chat_status_id'] == 1)
                                    <span style="right: 5px; top:11px;"
                                          class="position-absolute right badge badge-danger">!</span>
                                @endif
                                Чат
                            </a>
                        </li>
                        <li class="nav-item"><a class="test nav-link ml-1" href="#stat" data-toggle="tab">Статистика</a></li>


                    </ul>
                </div><!-- /.card-header -->

                <div class="card-body">
                    <div class="tab-content">
                        {{App::setLocale('ru')}}

                        <div class="tab-pane active" id="info">
                            <h4>Заявка была
                                создана: {{Date::parse($ext_promotion['created_at'])->addHours(3)->format('j F H:i')}}</h4>
                            <h4>Заявка была
                                обновлена: {{Date::parse($ext_promotion['updated_at'])->addHours(3)->format('j F H:i')}}</h4>
                            @if($ext_promotion['started_at'])
                                <h4>Время начала
                                    продвижения: {{Date::parse($ext_promotion['started_at'])->format('j F H:i')}}</h4>
                                <h4>Плановый конец
                                    продвижения: {{Date::parse($ext_promotion['started_at'])->addDays($ext_promotion['days'])->format('j F')}}
                                    21:00</h4>
                            @endif

                            <div class="info_tables row align-items-start">
                                <div class="col-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td style="font-weight: bold">Фио</td>
                                            <td>
                                                {{$ext_promotion->user['name']}} {{$ext_promotion->user['surname']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Псевдоним</td>
                                            <td>
                                                {{$ext_promotion->user['nickname']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Логин</td>
                                            <td>
                                                <a target="_blank" href="https://{{$ext_promotion['site']}}.ru/avtor/{{$ext_promotion['login']}}">{{$ext_promotion['login']}}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Пароль</td>
                                            <td>
                                                {{$ext_promotion['password']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Промокод</td>
                                            <td>
                                                {{$ext_promotion->promocode['promocode'] ?? 'Без промокода'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Количество дней</td>
                                            <td>
                                                {{$ext_promotion['days']}}
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
                                            <td style="font-weight: bold">Получаем средств</td>
                                            <td>
                                                {{$ext_promotion['price_total']}} руб.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Доля издательства</td>
                                            <td>
                                                {{$ext_promotion['price_our']}} руб.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Передать исполнителю</td>
                                            <td>
                                                {{$ext_promotion['price_executor']}} руб.
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div>
                                    <h2>Транзакции по этому продвижению</h2>
                                    <div>
                                        @if(count($transactions) > 0)
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
                                        @else
                                            <h4>Еще не было</h4>
                                        @endif
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
                                <div id="book_chat" style="margin: 0 0 30px 0; width: 100%; max-width: 2000px;"
                                     class="chat">
                                    <div style="margin: 0; width: 100%; max-width: 2000px;" class="container">
                                        @livewire('account.chat.chat',['chat_id'=>$chat->id, 'new_chat_user_id'=>null])
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane" id="stat">
                            @if(count($ext_promotion->ext_promotion_parsed_reader) > 0)
                                {!! $chart->container() !!}
                                <script src="{{ $chart->cdn() }}"></script>

                                {{ $chart->script() }}
                            @else
                                <h4>Пока данных нет. Они появятся при обновлении каждый вечер в 21:30 МСК или, если
                                    автор сам вручную. подтянет стату со страницы продвижения</h4>
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
