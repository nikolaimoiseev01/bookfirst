@extends('layouts.admin_layout')
@section('title', 'Транзакции')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Транзакции пользователей</h1>
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
                    <style>
                        td {
                            vertical-align: inherit !important;
                        }
                    </style>
                    <table id="participants_table" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="text-align: center;">id</th>
                            <th style="text-align: center;">Пользователь</th>
                            <th style="text-align: center;">Сумма</th>
                            <th style="text-align: center;">Статус</th>
                            <th style="text-align: center;">Назначение</th>
                            <th style="text-align: center;">Метод оплаты</th>
                            <th style="text-align: center;">Последнее изменение</th>
                            <th style="width:15%; text-align: center;">Yokassa_id</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{App::setLocale('ru')}}
                        @foreach($transactions as $transaction)

                            <tr>
                                <td style="text-align: center;">
                                    {{$transaction['id']}}
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{route('user_page', $transaction['user_id'])}}">{{$transaction->user['name']}} {{$transaction->user['surname']}}</a>
                                </td>
                                <td style="text-align: center;">
                                    {{round($transaction['amount'])}} руб.
                                </td>

                                <td style="
                                    color:
                                @if($transaction['status'] === 'CONFIRMED') #09c73a
                                @elseif($transaction['status'] === 'CREATED') #ff2929
                                    @endif;
                                    text-align: center;">
                                    {{$transaction['status']}}
                                </td>

                                <td style="text-align: center;">
                                    @if ($transaction['participation_id'] > 0)
                                        <a href="{{route('user_participation', $transaction['participation_id'])}}">
                                            {{$transaction['description']}}
                                        </a>
                                    @elseif ($transaction['own_book_payment_type'] === 'Without_print')
                                        <a href="{{route('own_books_page', $transaction['own_book_id'])}}">
                                            Оплата за макеты книги
                                        </a>

                                    @elseif ($transaction['own_book_payment_type'] === 'Print_only')
                                        <a href="{{route('own_books_page', $transaction['own_book_id'])}}">
                                            Оплата за макеты книги
                                        </a>
                                    @else
                                        {{$transaction['description']}}
                                    @endif

                                </td>
                                <td style="text-align: center;">
                                    {{$transaction['payment_method']}}
                                </td>
                                <td style="text-align: center;">

                                    {{ Date::parse($transaction['updated_at'])->format('j F, G:i') }}
                                </td>
                                <td style="width:15%; text-align: center;">

                                    {{($transaction['yoo_id']) }}
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
    </section>
    <!-- /.content -->
@endsection
