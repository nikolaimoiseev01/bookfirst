<div style="display: inherit;">

    <style>

        .sub_block {
            color: #47AF98;
            background: white;
            border: 1px #47AF98 solid;
            border-radius: 7px;
            transition: all .3s;
            position: relative;
            width: 158px;
            height: 31px;
            overflow: hidden;
        }

        .not_sub_yet:hover {

            background: #47AF98;
            transition: all .3s;
            cursor: pointer;
        }

        .not_sub_yet:hover span {
            color: white;
        }

        .not_sub_yet:hover i {
            color: white;
        }


        .not_sub_yet, .sub_yet {
            font-size: 18px;
            position: absolute;

        }

        .not_sub_yet {
            right: 0;
            width: 100%;
            padding: 3px 20px;
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
        }

        .fa-xmark:hover {
            cursor: pointer;
        }

        .sub_yet > div:first-child {
            padding: 3px 20px;
        }

        .sub_yet > div:nth-child(2) {
            display: flex;
            align-items: center;
            flex: 1;
            margin-left: auto;
            border-left: 1px solid var(--green);
            justify-content: center;
            color: #ff6b6b;
        }

    </style>

    <div wire:ignore title="tst" class="log_check sub_block">
        <div wire:click.prevent="subscribe()" class="not_sub_yet">
            <span>Подписаться</span> <i class="fa-regular fa-heart"></i>
        </div>

        <div class="sub_yet">
            <div>
                <span>Подписан</span>
            </div>
            <div>
                    <span title="Отписаться" class="tooltip">
                    <i wire:click.prevent="unsubscribe()" class="fa-solid fa-xmark"></i>
                        </span>
            </div>
        </div>
        </a>
    </div>

    {{--        <a href="" wire:click.prevent="subscribe()" style="box-shadow: none" class="log_check subscribed-button button">--}}
    {{--            В избранном--}}
    {{--            <i class="fa-regular fa-heart"></i>--}}
    {{--        </a>--}}




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
