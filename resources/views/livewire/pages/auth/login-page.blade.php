<main class="flex-1 content mb-32">
    @section('title')
        Вход
    @endsection

    <div class="flex gap-8 mx-auto justify-center mb-8 flex-wrap">
        <p class="text-6xl text-green-500 font-normal">Вход</p>
        <x-ui.link-simple href="{{route('register')}}"
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
            autocomplete="password"
            wire:model="password"
        />
        <div class="flex gap-4 mt-8 w-full">
            <x-ui.button class="flex-1">Войти</x-ui.button>
            <x-ui.link-simple href="{{route('auth.password.request')}}">Восстановить пароль</x-ui.link-simple>
        </div>
        <div class="flex gap-2">
            <p>Войти через соц.сети:</p>
            <a href="{{route('auth.social.redirect', 'vkontakte')}}">
                <img src="/fixed/icons/logo_vk.svg" class="w-8 h-8" alt="">
            </a>
            <a href="{{route('auth.social.redirect', 'google')}}">
                <img src="/fixed/icons/logo_google.svg" class="w-8 h-8" alt="">
            </a>
            <a href="{{route('auth.social.redirect', 'yandex')}}">
                <img src="/fixed/icons/logo_yandex.svg" class="w-8 h-8" alt="">
            </a>
        </div>

    </form>
</main>
