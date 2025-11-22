@extends('layouts.app')

@section('page-tab-title')
    Страница продвижения
@endsection

@section('page-title')
    <div class="account-header">
        <h1>Продвижение на сайте</h1>
    </div>
@endsection

@section('content')
    @php
        $part_not_available = "#cbcbcb";
        $part_action_needed="#ffa500";
        $part_all_good="#47AF98";
        $part_process_ongoing="#4775af";
    @endphp

    <div class="ext_promotion_index_page_wrap part_index_page_wrap">

        {{-- Общая информация о заявке--}}
        <div class="legend_wrap">
            <div class="left">
                <div>
                    <p>Статус заявки: <b style="color:
                    @if($ext_promotion['ext_promotion_status_id'] == 2)
                    {{$part_action_needed}}
                    @elseif($ext_promotion['ext_promotion_status_id'] == 4)
                    {{$part_process_ongoing}}
                    @endif
                    "><i>{{$ext_promotion->ext_promotion_status['title']}}</i></b></p>
                </div>
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
                    <div style="background:{{$part_process_ongoing}}" class="legend-circle"></div>
                    <p style="font-size: 19px">Идет процесс</p>
                </div>
                <div class="legend-row">
                    <div style="background:{{$part_all_good}}" class="legend-circle"></div>
                    <p style="font-size: 19px">Успешно выполнено</p>
                </div>
            </div>

        </div>
        {{-- // Общая информация о заявке--}}
        @if($ext_promotion->chat ?? null)
            <div class="chat_block_wrap">
                <div class="buttons_wrap">
                    <a id="chat_button" class="button">
                        Чат по моему продвижению
                    </a>
                    @if($ext_promotion->chat['flg_chat_read'] === 0)
                        @livewire('account.chat.chat-question-check',['chat_id'=>$ext_promotion->chat->id])
                    @endif
                </div>
                <div id="book_chat">
                    <div class="container">
                        @livewire('account.chat.chat',['chat_id'=>$ext_promotion->chat->id ?? null,
                        'new_chat_user_id'=>null])
                    </div>
                </div>
            </div>
        @endif


        <div class="participation-wrap">
            <livewire:account.ext-promotion.part-page-blocks.general-info :ext_promotion="$ext_promotion"/>

            <livewire:account.ext-promotion.part-page-blocks.pay-block :ext_promotion="$ext_promotion"/>

            <livewire:account.ext-promotion.part-page-blocks.process-block :ext_promotion="$ext_promotion"/>

            {{--            <livewire:account.collection-participation.part-page-blocks.preview-block :participation="$participation"/>--}}

            {{--            <livewire:account.collection-participation.part-page-blocks.vote-block :participation="$participation"/>--}}

            {{--            <livewire:account.collection-participation.part-page-blocks.track-block :participation="$participation"/>--}}

        </div>
    </div>


    </div>

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

@endsection
