@extends('layouts.app')

@section('page-tab-title')
    Страница участия
@endsection

@section('page-title')
    <div class="account-header">
        <h1>Мое участие в сборнике {{$collection['title']}}</h1>
    </div>
@endsection

@section('content')
    @php
        $part_not_available = "#cbcbcb";
        $part_action_needed="#ffa500";
        $part_all_good="#47AF98";
    @endphp

    <div class="part_index_page_wrap">

        <a target="_blank" href="{{route('help_collection')}}#application_pay" class="help_link link">
            Инструкция по этой странице
        </a>

        {{-- Общая информация о заявке--}}
        <div class="legend_wrap">
            <div class="left">
                <div>
                    <p>Мой статус участия: <b><i>{{$participation->pat_status['pat_status_title']}}</i></b></p>
                </div>
                <div>
                    <p>Статус издания сборника: <b><i>{{$collection->col_status['col_status']}}</i></b></p>
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
                    <div style="background:{{$part_all_good}}" class="legend-circle"></div>
                    <p style="font-size: 19px">Успешно выполнено</p>
                </div>
            </div>

        </div>
        {{-- // Общая информация о заявке--}}
        <div class="chat_block_wrap">
            <a id="chat_button" class="button">
                Чат по моему изданию
            </a>
            {{--            @if($chat_question_check)--}}
            {{--                <div style="margin-left: 20px;">--}}
            {{--                    @livewire('account.chat.chat-question-check',['mes_id'=>$last_mes_id])--}}
            {{--                </div>--}}
            {{--            @endif--}}

            <div id="book_chat">
                <div class="container">
                    @livewire('account.chat.chat',['chat_id'=>$chat_id, 'new_chat_user_id'=>null])
                </div>
            </div>
        </div>


        <div class="participation-wrap">
            <livewire:account.collection-participation.part-page-blocks.general-info :participation="$participation"/>

            <livewire:account.collection-participation.part-page-blocks.pay-block :participation="$participation"/>

            <livewire:account.collection-participation.part-page-blocks.preview-block :participation="$participation"/>

            <livewire:account.collection-participation.part-page-blocks.vote-block :participation="$participation"/>

            <livewire:account.collection-participation.part-page-blocks.track-block :participation="$participation"/>

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

        <script>
            $('.amazon_link_error').on('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Упс, ссылка указана неверно.',
                    icon: 'error',
                    html: '<p>Пожалуйста, напишите нам в чате (наверху этой страницы), и мы быстро решим проблему!</p>',
                    showConfirmButton: false,
                })
            })
        </script>
    @endpush

@endsection
