<div>
    <p>На Вашем балансе сейчас: <span
            style="color: var(--social_blue)">{{$user_wallet['cur_amount']}}</span> руб.
        @if($user_wallet['cur_amount'] === 0)
            Чтобы отправить донат, его сначала необходимо зачислить в Ваш личный кабинет:
        @endif

        @if($user_wallet['cur_amount'] > 0)
            <a id="show_input_points" type="submit"
               style="height: fit-content; margin-right:10px; max-width:250px;"
               class="link_social">
                Пополнить
            </a>
            <a  id="donate_back" style="display:none;" class="link_social">Назад</a>


    @endif

    <form style="margin-top: 15px;    flex-wrap: wrap;
        justify-content: center;  display:  @if($user_wallet['cur_amount'] > 0) none @else flex @endif; align-items: center; width: auto;"
          action="{{ route('payment.create_points_payment')}}"
          method="POST"
          enctype="multipart/form-data"
          id="input_points_form">
        @csrf
        <p>Сумма для пополнения:</p>
        <input value="{{route('social.user_page', $user['id'])}}"
               style="display: none"
               type="text" name="url_redirect"
               id="url_redirect">
        <input required style="margin-left: 10px; max-width: 80px; text-align: center; padding: 2px;" placeholder="сумма"
               type="number" name="amount"
               class="form-control_social"
               id="amount">

        <button type="submit"
                style="margin-left: 10px; height: fit-content; max-width:250px;"
                class="button_social">
            Пополнить
        </button>
    </form>


    <form style="margin-top: 15px;    flex-wrap: wrap;
        justify-content: center;     display: @if($user_wallet['cur_amount'] > 0) flex @else none @endif;
        align-items: center; width: auto;"
          wire:submit.prevent="make_donate(Object.fromEntries(new FormData($event.target)))"
          method="POST"
          enctype="multipart/form-data"
          id="donate_form">
        @csrf
        <p>Сумма для перевода:</p>
        <input style="display: none" value="{{$user['id']}}" placeholder="сумма" type="number"
               name="user_to"
               class="form-control"
               id="user_to">

        <input style="margin-left: 10px; max-width: 80px; text-align: center; padding: 2px;"
               placeholder="сумма"
               type="number" name="amount"
               class="form-control_social"
               id="amount">
        <button type="submit"
                style="margin-left: 10px; height: fit-content; max-width:250px;"
                class="show_preloader_on_click button_social">
            Отправить
        </button>
    </form>

    <script>

        document.addEventListener('close_form', function () {
            $('.cus-modal').fadeOut('fast');
        })
    </script>

    <script>
        $('#show_input_points').click(function () {
            $(this).hide();
            $('#input_points_form').css('display', 'flex');
            $('#donate_form').hide();
            $('#donate_back').show();

        })


        $('#donate_back').click(function () {
            $(this).hide();
            $('#input_points_form').css('display', 'none');
            $('#donate_form').show();
            $('#show_input_points').show()
        })

    </script>
</div>
