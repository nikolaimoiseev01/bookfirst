<div class="user_header_buttons_wrap">
    @livewire('social.user-header-buttons.subscribe', ['user_to_subscribe' => $user->id])

    <a class="message_wrap log_check
       @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user->id) self_mes @endif"
       href="{{route('new_chat', $user->id)}}">
        <img src="/img/social/pen_icon.svg" alt="">
        <p>Написать</p>
    </a>

    @livewire('social.user-header-buttons.make-donate', ['user_to' => $user])


    @push('page-js')
        <script>
            $('.self_mes').click(function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Что-то пошло не так',
                    icon: 'error',
                    html: "<p>Нельзя написать сообщение самому себе :)</p>",
                    showConfirmButton: false,
                })
            })

        </script>
    @endpush
</div>
