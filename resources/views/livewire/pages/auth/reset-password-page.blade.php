<main class="flex-1 content mb-32">
    @section('title')
        Восстановление пароля
    @endsection

    <div class="flex gap-8 mx-auto justify-center mb-8 flex-wrap">
        <p class="text-6xl text-green-500 font-normal">Восстановление пароля</p>
    </div>

    <form wire:submit="resetPassword" class="border border-green-500 rounded-2xl p-8 flex flex-col gap-4 max-w-2xl mx-auto">
        <x-ui.input.text
            name="email"
            label="Email"
            wire:model="email"
        />
        <x-ui.input.password
            name="password"
            label="Пароль"
            wire:model="password"
        />
        <x-ui.input.password
            name="password_confirmation"
            label="Повторите пароль"
            wire:model="password_confirmation"
        />
        <x-ui.button class="flex-1 mt-8">Сохранить пароль</x-ui.button>
        @if (session('status'))
            <p class="text-green-500"><b>{{ __('Письмо с инструкцией восстановления отправлена на Email!') }}</b></p>
        @endif
    </form>
</main>
