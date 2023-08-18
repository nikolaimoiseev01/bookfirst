@extends('layouts.admin_layout')
@section('title', $user['surname'] . ' ' . $user['name'])
@section('content')
    <!-- Main content -->
    <section class="pt-4 content">
        <div class="col-md-12">
            <div class="card">
                <div class="d-flex align-items-center bg-gradient-info card-header">
                    <h1 style="margin-right: 20px; font-size: 25px;" class="card-title"><i>{{$user['name']}} {{$user['surname']}} @if($user['nickname'] <> '') (Псевдоним: {{$user['nickname']}}) @endif</i></h1>
                    @if(Cache::has('is_online' . $user['id']))
                        <span style="padding: 0 10px; border: 1px #51ff3e  solid; color: #51ff3e;"
                              class="user_now">В сети</span>
                    @else
                        <span style="padding: 0 10px; border: 1px white solid; color: white;" class="user_now"> Не в сети</span>
                    @endif
                    <a style="margin-left: auto;" href="{{route('login_as', $user['id'])}}" class="btn btn-primary"> Войти в его аккаунт</a>
                </div>



                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#info" data-toggle="tab">Общая
                                информация</a>
                        </li>
                        <li class="nav-item"><a class="nav-link " href="#works" data-toggle="tab">Произведения
                                автора</a></li>

                        <li class="nav-item"><a class="nav-link" href="#participations" data-toggle="tab">Участие в
                                сборниках</a></li>
                        <li class="nav-item"><a class="nav-link" href="#own_books" data-toggle="tab">Книги автора</a></li>
                        <li class="nav-item"><a class="nav-link" href="#awards" data-toggle="tab">Награды</a></li>
                        <li class="nav-item"><a class="nav-link" href="#chats" data-toggle="tab">Чаты</a></li>
                    </ul>
                </div><!-- /.card-header -->

                <style>
                    th {
                        vertical-align: middle !important;
                    }
                </style>

                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane active" id="info">
                            <div class="row align-items-start">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td scope="col"  style="font-weight: bold">Фио</td>
                                            <td>
                                                {{$user['name']}} {{$user['surname']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="col"  style="font-weight: bold">Псевдоним</td>
                                            <td>
                                                {{$user['nickname']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="col"  style="font-weight: bold">Email</td>
                                            <td>
                                                {{$user['email']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="col"  style="font-weight: bold">Аккаунт создан</td>
                                            <td>
                                                {{$user['created_at']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold">Загружено работ</td>
                                            <td>
                                                {{count($user->work)}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight: bold">Reg_utm_source</td>
                                            <td>
                                                {{$user['reg_utm_source']}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight: bold">Reg_utm_medium</td>
                                            <td>
                                                {{$user['reg_utm_medium']}}
                                            </td>
                                        </tr>



                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <form style="gap: 20px;" class="p-3 border align-items-center mt-2 gap-2" action="{{ route('add_user_comment',$user['id']) }}" method="POST"
                                          enctype="multipart/form-data"
                                    >
                                        @csrf
                                        <h4>Комментарий: </h4>
                                        <div id="comment_text">
                                            {!! $user['comment'] !!}
                                        </div>
                                        <div style="display: none;" id="comment_text_edit">
                            <textarea name="comment" id="summernote"
                                      name="editordata">{{$user['comment']}}</textarea>
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
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="works">
                            {{App::setLocale('ru')}}
                            @if(count($user->work)>0)
                                <h2 class="text-info mb-4">Всего у автора <b>{{count($user->work)}}</b> произведений.
                                </h2>
                                @foreach($user->work as $work)

                                    <h3>{{$loop->index + 1}}. {{$work['title']}}</h3>
                                    <p style="color:grey">Загружено {{$work['upload_type']}}: {{ Date::parse($work['created_at'])->addHours(3)->format('j F Y') }}</p>
                                    <p>{!! nl2br($work['text']) !!}</p>
                                @endforeach
                            @else
                                У автора еще нет произведений :(
                            @endif

                        </div>

                        <div class=" tab-pane" id="participations">
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table style="max-width: 900px;" class="table table-hover table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center">Сборник</th>
                                        <th style="text-align: center">Статус участия</th>
                                        <th style="text-align: center">Страниц</th>
                                        <th style="text-align: center">Экземпляров</th>
                                        <th style="text-align: center">Промокод</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->participation as $participation)
                                        <tr class="row_hover" onclick="document.location = '{{route('user_participation', ['participation_id' => $participation['id']])}}';">
                                            <td style="text-align: center">{{$participation->collection['title']}}</td>

                                            <td style="text-align: center">
                                                {{$participation->pat_status['pat_status_title']}}
                                            </td>
                                            <td style="text-align: center">
                                                {{$participation['pages']}}
                                            </td>
                                            <td style="text-align: center">
                                                {{$participation->printorder['books_needed'] ?? 0}}
                                            </td>
                                            <td style="text-align: center">
                                                {{$participation['promocode']}}
                                            </td>


                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.tab-content -->

                        <div class="tab-pane" id="own_books">
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table style="max-width: 900px;" class="table table-hover table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center">Название</th>
                                        <th style="text-align: center">Статус</th>
                                        <th style="text-align: center">Сумма издания</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->own_book as $own_book)
                                        <tr class="row_hover" onclick="document.location = '{{route('own_books_page', $own_book['id'])}}';">
                                            <td style="text-align: center">{{$own_book['title']}}</td>

                                            <td style="text-align: center">
                                                {{$own_book->own_book_status['status_title']}}
                                            </td>
                                            <td style="text-align: center">
                                                {{round($own_book['total_price'])}} руб.
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.tab-content -->


                        <div class="tab-pane" id="awards">
                            {{App::setLocale('ru')}}
                            @if(count($awards)>0)
                                <h2 class="text-info mb-4">Всего у автора <b>{{count($awards)}}</b> наград.
                                </h2>
                                @foreach($awards as $award)
                                    <h3>{{$loop->index + 1}}. {{$award['name']}}</h3>
                                @endforeach
                            @else
                                У автора еще нет наград :(
                            @endif

                            <div class="mt-2 d-flex align-items-center">

                                <div style="display: none" id="change_user_award_form_wrap">
                                    <form class="d-flex ml-3" style="align-items: center;"
                                          action="{{ route('add_user_award',['user_id' => $user['id']]) }}" method="POST"
                                          enctype="multipart/form-data"
                                    >
                                        @csrf

                                        <select style="padding: 0 0 0 10px; height: 33px; width: fit-content;"
                                                id="award_id_to_update" class="form-control"
                                                name="award_id_to_update">
                                            @foreach($awards_types as $awards_type)
                                                <option value="{{$awards_type['id']}}">{{$awards_type['name']}}</option>
                                            @endforeach
                                        </select>

                                        <button id="btn-submit" type="submit"
                                                style="height: fit-content; max-height: 30px; max-width:150px;"
                                                data-status-from="{{'123'}}"
                                                class="change_status ml-3 d-flex align-items-center justify-content-center btn btn-outline-primary"
                                        >
                                            Добавить
                                        </button>
                                    </form>
                                </div>

                                <button style="display: flex; border: none; width: auto; padding: 3px 10px;max-width:150px"
                                        data-form="change_user_award" type="button"
                                        class="change_status_button ml-1 btn btn-outline-info btn-block btn-sm"
                                >
                                    <span style="margin-right: 20px;">Добавить награду</span>
                                    <i style="font-size: 20px;" class="fa fa-edit"></i>

                                </button>
                            </div>

                        </div>
                        <!-- /.tab-content -->


                        <div class=" tab-pane" id="chats">

                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table style="max-width: 900px;" class="table table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center">Тема</th>
                                        <th style="text-align: center">Статус</th>
                                        <th style="text-align: center">К чату</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($chats as $chat)
                                        <tr>
                                            <td style="text-align: center">{{$chat['title']}}</td>

                                            <td style="text-align: center">
                                                {{$chat->chat_status['status']}}
                                            </td>

                                            <td style="text-align: center">
                                                <a
                                                    @if ($chat['collection_id'] > 0)
                                                    href="/admin_panel/collections/participation/{{\App\Models\Participation::where('user_id', $chat['user_created'])->where('collection_id', $chat['collection_id'])->value('id')}}#chat"
                                                    @else
                                                    href="{{route('admin_chat', $chat['id'])}}"
                                                    @endif
                                                >
                                                    <i class="fas fa-share"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div style="display:none" class="p-3 mt-3 border chat-create-admin">
                                    @livewire('account.chat.chat-create',['chat_title' => '', 'collection_id' => 0, 'own_book_id' => 0, 'user_to' => $user->id])
                                </div>
                                <a id="chat_add" style="width:200px;" class="mt-3 btn btn-outline-secondary">
                                    <i class="mr-2 fa fa-plus"></i> Создать чат</a>
                            </div>
                        </div><!-- /.tab-content -->

                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
    </section>


@section('page-js')
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
@endsection

@endsection
