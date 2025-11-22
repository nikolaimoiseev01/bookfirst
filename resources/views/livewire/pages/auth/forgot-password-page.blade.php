<main class="flex-1 content mb-32">
    @section('title')
        Восстановление пароля
    @endsection

    <div class="flex gap-8 mx-auto justify-center mb-8 flex-wrap">
        <p class="text-6xl text-green-500 font-normal">Восстановление пароля</p>
    </div>

    <form wire:submit="sendPasswordResetLink" class="border border-green-500 rounded-2xl p-8 flex flex-col gap-4 max-w-2xl mx-auto">
        <x-ui.input.text
            name="email"
            label="Email"
            wire:model="email"
        />
        <div class="flex gap-4 mt-8 w-full">
            <x-ui.button class="flex-1">Напомнить пароль</x-ui.button>
            <x-ui.link-simple href="{{route('login')}}">Войти</x-ui.link-simple>
        </div>
        @if (session('status'))
            <p class="text-green-500"><b>{{ __('Письмо с инструкцией восстановления отправлена на Email!') }}</b></p>
        @endif
    </form>
</main>
