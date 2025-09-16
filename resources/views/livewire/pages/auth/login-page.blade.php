<main class="flex-1 content mb-32">
    @section('title')
        Вход
    @endsection

    <div class="flex gap-8 mx-auto justify-center mb-8 flex-wrap">
        <p class="text-6xl text-green-500 font-normal">Вход</p>
        <x-ui.link-simple href="{{route('auth.register')}}"
                          class="text-6xl  font-normal !text-dark-100 hover:!text-green-500 transition">
            Регистрация
        </x-ui.link-simple>
    </div>

    <form wire:submit="login" class="border border-green-500 rounded-2xl p-8 flex flex-col gap-4 max-w-2xl mx-auto">
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
        <div class="flex gap-4 mt-8 w-full">
            <x-ui.button class="flex-1">Войти</x-ui.button>
            <x-ui.link-simple href="{{route('auth.password.request')}}">Восстановить пароль</x-ui.link-simple>
        </div>

    </form>
</main>
