@extends('layouts.admin_layout')
@section('title', 'Продвижения')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div style="gap: 20px; align-items: flex-start;" class="mb-3 row d-flex gap-3 mb-2">
                <div class="d-flex">
                    <h1 class="m-0">Продвижения</h1>
                </div><!-- /.col -->
                <a href="{{route('admin_ext_promotions_all')}}" class="ml-3 btn btn-outline-info">Все продвижения без
                    фильтра</a>

                <a id="payments_button" class="ml-3 btn btn-outline-info" href="">Все переводы исполнителю</a>

                <script>
                    // console.log("START")

                    $('#payments_button').on("click", function(event) {
                        event.preventDefault()
                        payments_table = $('#payments_table')
                        payments_table.toggle()
                        if(payments_table.is(":visible")) {
                            $(this).text('Скрыть')
                        } else {
                            $(this).text('Все переводы исполнителю')
                        }
                    })
                </script>

            </div><!-- /.row -->

            <livewire:admin.ext-promotion-internal-payments></livewire:admin.ext-promotion-internal-payments>

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
                            <th scope="col" style="text-align: center;">ID</th>
                            <th scope="col" style="text-align: center;">Статус</th>
                            <th scope="col" style="text-align: center;">Автор</th>
                            <th scope="col" style="text-align: center;">Сайт</th>
                            <th scope="col" style="text-align: center;">Дней</th>
                            <th scope="col" style="text-align: center;">Промокод</th>
                            <th scope="col" style="text-align: center;">Исполнитель</th>
                            <th scope="col" style="text-align: center;">Издательство</th>
                            <th scope="col" style="text-align: center;">Общая сумма</th>
                            <th scope="col" style="text-align: center;">Оплачен?</th>
                            <th scope="col" style="text-align: center;">Создан</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{App::setLocale('ru')}}
                        @foreach($ext_promotions as $ext_promotion)

                            <tr style="
                                @if(in_array($ext_promotion['ext_promotion_status_id'], [1,3]) )
                                    background: #ffe7e7; color: black;
                                @elseif(in_array($ext_promotion['ext_promotion_status_id'], [9]) )
                                    background: #c3fdd2; color: black;
                                @elseif(in_array($ext_promotion['ext_promotion_status_id'], [4]) )
                                    background: #c3f7ff; color: black;
                                @elseif(in_array($ext_promotion['ext_promotion_status_id'], [99]) && $ext_promotion->chat['chat_status_id'] == 1)
                                    background: #98c3fc; color: black;
                                @endif
                                "
                                onclick="document.location = '' + '{{route('admin_ext_promotion', $ext_promotion['id'])}}' + ''">

                                <td data-label="ID" style="text-align: center;">
                                    {{$ext_promotion['id']}}
                                </td>

                                <td data-label="Статус" class="position-relative" style="text-align: center;">
                                    @if($ext_promotion->chat['chat_status_id'] == 1)
                                        <span style="left: 5px; top:5px;"
                                              class="mr-2 badge badge-danger">
                                            @if(in_array($ext_promotion['ext_promotion_status_id'], [99]))
                                                Есть ответ!
                                            @else
                                                Есть вопрос!
                                            @endif

                                    </span>
                                    @endif
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

                                <td data-label="Промокод" style="text-align: center;">
                                    {{$ext_promotion->promocode['promocode']}}
                                </td>

                                <td data-label="Исполнитель" style="text-align: center;">
                                    {{$ext_promotion['price_executor']}} руб.
                                </td>

                                <td data-label="Издательство" style="text-align: center;">
                                    {{$ext_promotion['price_our']}} руб.
                                </td>
                                <td data-label="Общая сумма" style="text-align: center;">
                                    {{$ext_promotion['price_total']}} руб.
                                </td>
                                <td data-label="Оплачен исполнителю?" style="text-align: center;">
                                    @if(in_array($ext_promotion['ext_promotion_status_id'], [4,5]))
                                        @if($ext_promotion['executor_got_payment'])
                                            <span class="bg-green"
                                                  style="border-radius: 10px; padding: 2px 20px; background: green">Да</span>
                                        @else
                                            <span class="bg-danger"
                                                  style="border-radius: 10px; padding: 2px 20px; background: red">Нет</span>
                                        @endif
                                    @else
                                        Еще не время
                                    @endif
                                </td>
                                <td data-label="Создан" style="text-align: center;">
                                    {{ Date::parse($ext_promotion['created_at'])->addHours(3)->format('j F H:i') }}
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
