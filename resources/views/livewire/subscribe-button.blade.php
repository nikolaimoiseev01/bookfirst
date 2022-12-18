<div style="display: inherit;">

    <style>

        .sub_block {
            background: white;
            transition: all .3s;
            position: relative;
            width: 135px;
            height: 30px;
            overflow: hidden;
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .sub_block span {
            font-size: 20px;
            font-family: "Futura PT medium", serif;
            color: #424242;
            transition: 0.1s all ease-in-out;
        }

        .not_sub_yet:hover {
            transition: all .3s;
            cursor: pointer;
        }

        .not_sub_yet:hover span {
            color: #ED5E3F;
            transition: 0.1s all ease-in-out;
        }

        .not_sub_yet, .sub_yet {
            font-size: 18px;
            position: absolute;

        }

        .not_sub_yet {
            right: 0;
            width: 100%;
            /*padding: 3px 20px;*/
            transition: .3s all ease-in-out;
            display: flex;
            align-items: center;
            justify-content: space-between;

        }

        .sub_yet {
            left: -100%;
            width: 100%;
            transition: .3s all ease-in-out;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .fa-xmark {
            color: #ff6b6b;
        }

        .fa-xmark:hover {
            cursor: pointer;
        }


        .sub_yet > div:nth-child(2) {
            padding-top: 2px;
            padding-left: 15px;

        }

    </style>

    <div wire:ignore title="tst" class="log_check sub_block">
        <div wire:click.prevent="subscribe()" class="not_sub_yet">
            <img src="/img/heart-regular.svg" alt="">
            <span>Подписаться</span>

        </div>

        <div class="sub_yet">

            <div style="padding-left: 5px;">
                <span style="color: var(--green) !important;">Подписан</span>
            </div>
            <div>
                    <span style="font-size: 24px;" title="Отписаться" class="tooltip">
                    <i wire:click.prevent="unsubscribe()" class="fa-solid fa-xmark"></i>
                        </span>
            </div>
        </div>
        </a>
    </div>

{{--    <a href="">--}}
{{--        <img src="/img/heart-regular.svg" alt="">--}}
{{--        <p>Подписаться</p>--}}
{{--    </a>--}}





    @if($subscription_check)
        <script>
            $('.not_sub_yet').css('right', '-100%');
            $('.sub_yet').css({'left': '0'});
            setTimeout(function () {
                $(".not_sub_yet").css({display: 'none'});
                $(".sub_block").css('overflow', 'inherit');
            }, 300);
        </script>
    @endif

    <script>


        document.addEventListener('subscribe', function () {
            $('.not_sub_yet').css('right', '-100%');
            $('.sub_yet').css({'left': '0'});
            setTimeout(function () {
                $(".not_sub_yet").css({display: 'none'});
                $(".sub_block").css('overflow', 'inherit');
            }, 300);
        });

        document.addEventListener('unsubscribe', function () {
            $(".sub_block").css('overflow', 'hidden');
            $('.not_sub_yet').css('display', 'inherit');

            setTimeout(function () {

                $('.not_sub_yet').css('right', '0');
                $('.sub_yet').css('left', '-100%');
            }, 300);

        });
    </script>
</div>
