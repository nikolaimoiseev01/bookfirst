<div>
    @if ($sub_check == 0)
        <a
            @guest
            onclick="type_email()"
            @else
            wire:click.prevent="make_subscription('{{ Auth::user()->email }}')"
            @endif
            id="subscribe_button" class="button">
            Подписаться на новости
        </a>
    @endif

    <script>

        function type_email() {
            Swal.fire({
                title: 'Введите Email',
                input: 'email',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Подписаться',
                cancelButtonText: 'Отмена',
                showLoaderOnConfirm: true,
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage(
                            '<p>Введите Email</p>'
                        )
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('make_subscription', result.value);
                }
            })
        }


    </script>

</div>
