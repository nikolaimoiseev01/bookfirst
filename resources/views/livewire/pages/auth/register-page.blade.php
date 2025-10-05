<main class="flex-1 content mb-32">
    @section('title')
        Регистрация
    @endsection

    <div class="flex gap-8 mx-auto justify-center mb-8 flex-wrap">
        <x-ui.link-simple href="{{route('login')}}"
                          class="text-6xl  font-normal !text-dark-100 hover:!text-green-500 transition">
            Вход
        </x-ui.link-simple>
        <p class="text-6xl text-green-500 font-normal">Регистрация</p>

    </div>

    <form wire:submit="register" class="border border-green-500 rounded-2xl p-8 flex flex-col gap-4 max-w-2xl mx-auto">
        <x-ui.input.text
            name="name"
            label="Имя"
            wire:model="name"
        />
        <x-ui.input.text
            name="surname"
            label="Фамилия"
            wire:model="surname"
        />
        <x-ui.input.text
            name="nickname"
            label="Псевдоним"
            wire:model="nickname"
        />
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
        <x-ui.button class="mt-8">Зарегистрироваться</x-ui.button>
    </form>
</main>
