<div id="question_check_block" style="width: 40px;">
    <style>
        .tooltip_custom_block {
            position: relative;
        }

        .tooltip_custom_shown {
            position: absolute;
            opacity: 0;
            background: #333;
            background: rgba(0, 0, 0, .8);
            font-family: "Futura PT Light", serif;
            font-size: 17px;
            max-width: 180px;
            line-height: 22px;
            border-radius: 5px;
            bottom: 150%;
            color: #fff;
            content: attr(title);
            text-decoration: none;
            padding: 5px 10px;
            visibility: hidden;
            left: 50%;
            z-index: -5;
            -webkit-transform: translateX(-50%);
            -moz-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            -o-transform: translateX(-50%);
            transform: translateX(-50%);
            transition: .3s;
        }

        .tooltip_custom_block:hover .tooltip_custom_shown {
            visibility: visible;
            opacity: 1;
            z-index: 59990;

        }
    </style>



    <div  class=" tooltip_custom_block">
        <span class="tooltip_custom" title="Есть вопросы от издательства" style="color: #ffffff;
        padding: 0px 8px;
        background: #e16464;
        border-radius: 20px;
        margin-left: 10px; ">!
        </span>

        <div class="tooltip_custom_shown">
            Есть вопросы от издательства
            <a wire:click.prevent="hide_question()" style="font-size: 15px;" class="link">Скрыть</a>
        </div>
    </div>

    <script>
        document.addEventListener('hide_question_chat', function () {
            $('#question_check_block').hide();
            $('#chat_button').css('width', '100%');
        });
    </script>
</div>
