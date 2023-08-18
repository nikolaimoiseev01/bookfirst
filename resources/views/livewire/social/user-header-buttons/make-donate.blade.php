<div class="donate_wrap">

    <a class="send_donate
    @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id) self_donate @endif
    @if(Auth::check() && !((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id)) show_modal @endif
        log_check
        "
       data-for-modal="modal_user_donate">
        <img src="/img/social/donate_icon.svg" alt="">
        <p> Отправить донат</p>
    </a>

    @if(Auth::check())
        <div style="display: none;" id="modal_user_donate" class="cus-modal-container donate_block_wrap modal_wrap">
            <h3>
                Отправить донат автору: <br>
                {{prefer_name($user['name'], $user['surname'], $user['nickname'])}}
            </h3>
            <div class="info_wrap">

                <div>


                    <p>На Вашем балансе сейчас: {{$user_wallet['cur_amount']}} руб.
                        @if($user_wallet['cur_amount'] === 0)
                            Чтобы отправить донат, его сначала необходимо зачислить в Ваш личный кабинет:
                        @endif
                    </p>

                    @if($user_wallet['cur_amount'] > 0)
                        <a class="show_topup link social">
                            Пополнить
                        </a>
                        <a style="display: none;" class="show_donate link social">Назад</a>
                    @endif
                </div>

                <form style="display: none;" class="topup_form" action="{{ route('payment.create_points_payment')}}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="input_points_form">
                    @csrf
                    <p>Сумма для пополнения:</p>
                    <input value="{{route('social.user_page', $user['id'])}}"
                           style="display: none"
                           type="text" name="url_redirect"
                           id="url_redirect">
                    <input required
                           placeholder="сумма"
                           type="number" name="amount"
                           class="form-control_social"
                           id="amount">

                    <button type="submit" class="button social">
                        Пополнить
                    </button>
                </form>


                @if($user_wallet['cur_amount'] > 0)
                    <form
                        class="send_donante_form"
                        wire:submit.prevent="make_donate(Object.fromEntries(new FormData($event.target)))"
                        method="POST"
                        enctype="multipart/form-data"
                        id="donate_form">
                        @csrf
                        <p>Сумма для перевода:</p>
                        <input value="{{$user['id']}}" placeholder="сумма" type="number"
                               name="user_to"
                               style="display: none;"
                               class="form-control"
                               id="user_to">

                        <input placeholder="сумма"
                               type="number" name="amount"
                               class="form-control_social"
                               id="amount">
                        <button type="submit" class="button social">
                            Отправить
                        </button>
                    </form>
                @endif
            </div>

        </div>
    @endif

    @push('page-js')

        <script>
            $('.self_donate').click(function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Что-то пошло не так',
                    icon: 'error',
                    html: "<p>Нельзя сделать донат самому себе :)</p>",
                    showConfirmButton: false,
                })
            })

            $('.show_topup, .show_donate').on('click', function () {
                $('.show_topup').toggle()
                $('.show_donate').toggle()
                $('.send_donante_form').toggle()
                $('.topup_form').toggle()
            })
        </script>

    @endpush
</div>
