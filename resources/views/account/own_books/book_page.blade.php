@extends('layouts.app')

@section('page-style')

@endsection

@section('page-tab-title')
    Страница издания
@endsection

@section('page-title')

    <div class="account-header">
        <h1>{{$own_book['author']}}: {{$own_book['title']}}</h1>
    </div>
@endsection

@section('content')
    @php
        $part_not_available = "#cbcbcb";
        $part_action_needed="#ffa500";
        $part_all_good="#47AF98";
    @endphp

    {{-- Общая информация о книге--}}
    <div class="part_index_page_wrap own_book_account_page_wrap">

        <a target="_blank" href="{{route('help_own_book')}}#application_pay" class="help_link link">
            Инструкция по этой странице
        </a>

        <div class="legend_wrap">
            <div class="left">
                <p>Общий статус: <b><i>{{$own_book->own_book_status['status_title']}}</i></b></p>
                <p>Статус обложки: <b><i>{{$own_book->own_book_cover_status['status_title']}}</i></b></p>
                <p>Статус ВБ: <b><i>{{$own_book->own_book_inside_status['status_title']}}</i></b></p>
            </div>

            <div class="right">
                <div class="legend-row">
                    <div style="background:{{$part_not_available}}" class="legend-circle"></div>
                    <p style="font-size: 19px">Пункт недоступен</p>
                </div>
                <div class="legend-row">
                    <div style="background:{{$part_action_needed}}" class="legend-circle"></div>
                    <p style="font-size: 19px">Необходимо действие</p>
                </div>
                <div class="legend-row">
                    <div style="background:{{$part_all_good}}" class="legend-circle"></div>
                    <p style="font-size: 19px">Успешно выполнено</p>
                </div>
            </div>

        </div>

        {{-- Чат книги --}}
        @if ($chat_id > 0)
            <div class="chat_block_wrap">
                <a id="chat_button" class="button">
                    Чат по моему изданию
                </a>
                @if($chat['flg_chat_read'] === 0)
                    @livewire('account.chat.chat-question-check',['chat_id'=>$chat->id])
                @endif
                <div id="book_chat">
                    <div class="container">
                        @livewire('account.chat.chat',['chat_id'=>$chat->id, 'new_chat_user_id'=>null])
                    </div>
                </div>
            </div>
        @endif


        <div class="participation-wrap">
            <livewire:account.own-book.book-page-blocks.general-info :own_book="$own_book"/>
            <livewire:account.own-book.book-page-blocks.pay-block :own_book="$own_book"/>
            <livewire:account.own-book.book-page-blocks.preview-block :own_book="$own_book"/>
            <livewire:account.own-book.book-page-blocks.track-block :own_book="$own_book"/>
            <livewire:account.own-book.book-page-blocks.sales-block :own_book="$own_book"/>
        </div>

    </div>

@endsection

@push('page-js')
    <script>
        $('#chat_button').click(function () {

            // $('#book_chat').slideToggle(5000)
            $('#book_chat').slideToggle(function () {
                if ($('#book_chat').is(":visible")) {
                    $('#chat_button').html('Свернуть чат');
                } else {
                    $('#chat_button').html('Чат по моему изданию');
                }
            });
        });
    </script>
@endpush
