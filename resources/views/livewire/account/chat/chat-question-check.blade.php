<div id="question_check_block" style="">
    <style>
        .tooltip_custom_block {
            position: relative;
            width: fit-content;
        }

        .tooltip_custom_shown {
            position: absolute;
            opacity: 0;
            background: rgb(255 255 255);
            font-family: "Futura PT Light", serif;
            font-size: 17px;
            max-width: 180px;
            line-height: 22px;
            border-radius: 5px;
            bottom: 5px;
            content: attr(title);
            text-decoration: none;
            padding: 1px 5px;
            visibility: hidden;
            left: 280%;
            border: 1px #d2d2d2 solid;
            z-index: -5;
            -webkit-transform: translateX(-50%);
            -moz-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            -o-transform: translateX(-50%);
            transform: translateX(-50%);
            transition: .3s;
        }


        .tooltip_custom_shown a {
            color: var(--black_default);
        }

        .tooltip_custom_block:hover .tooltip_custom_shown {
            visibility: visible;
            opacity: 1;
            z-index: 59990;

        }
    </style>



    <div id="mes_{{$mes_id}}" class=" tooltip_custom_block">
        <span class="tooltip_custom unread_mark">
        </span>

        <div class="tooltip_custom_shown">
{{--            Последнее сообщение - от издательства--}}
            <a wire:click.prevent="hide_message()" style="font-size: 16px;" class="link">Прочитать</a>
        </div>
    </div>

    <script>
        document.addEventListener('hide_mes_notification', event => {
            $('#mes_' + event.detail.mes_id).hide();
            $('#chat_button').css('width', '100%');
        });
    </script>
</div>
