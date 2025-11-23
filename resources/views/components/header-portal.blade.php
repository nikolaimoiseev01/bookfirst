<header x-data="{mobileMenuOpen: false}"
        class="fixed w-full shadow-[0_1px_4px_#00000026] top-0 px-5 py-2 md:py-4 flex justify-between items-center z-50 bg-white dark:bg-dark_bg">
    <x-search-modal/>
    <div class="flex gap-2 items-center">
        <a wire:navigate href="{{route('portal.index')}}"
            class="flex gap-2 items-center lg:absolute lg:left-1/2 lg:-translate-x-1/2 lg:top-1/2 lg:-translate-y-1/2">
            <x-ui.application-logo class="w-12 h-12 mr-2"/>
            <a class="italic text-3xl font-light sm:hidden">Первая Книга</a>
        </a>
        <div class="w-px h-7 bg-gray-300 xl:hidden"></div>
        <a wire:navigate
           :class="$store.global.social ? 'text-dark-400 hover:text-green-500' : 'text-green-500'"
           class="font-light cursor-pointer italic text-xl xl:hidden"
           href="{{route('portal.index')}}">
            Независимое издательство
        </a>
        <div class="w-px h-7 bg-gray-300 xl:hidden"></div>

        <a wire:navigate
           :class="$store.global.social ? 'text-blue-500' : 'text-dark-400 hover:text-blue-500'"
           class="font-light cursor-pointer italic text-xl xl:hidden"
           href="{{route('social.index')}}">
            Социальная сеть
        </a>
    </div>

    <div
        class="flex gap-6 items-center text-2xl text-black-400 dark:text-white lg:justify-between lg:w-full">
        <style>
            button.hamburger {
                width: 35px;
                height: 30px;
            }

            .hamburger span.bar {
                height: 1px;
            }
        </style>
        <button @click="mobileMenuOpen = !mobileMenuOpen" :class="mobileMenuOpen ? 'active' : ''"
                class="hamburger hamburger--converge !hidden lg:!block" type="button">
            <div class="inner">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </button>
        <div class="flex gap-6">
            <x-heroicon-c-magnifying-glass @click="$dispatch('open-modal', 'searchModal')"
                                           class="w-6 h-auto transition hover:fill-green-500 cursor-pointer"/>
            @foreach($links as $link)
                @if($link['routes'] ?? null)
                    <div class="relative group lg:hidden">
                        <p
                            :class="window.location.href.includes('{{$link['url_part']}}') ? 'text-green-500' : ''"
                            class="cursor-pointer font-normal">{{$link['name']}}</p>
                        <div
                            class="absolute left-1/2 -translate-x-1/2 top-1/2 mt-2 w-max invisible opacity-0 scale-y-95 transform transition-all duration-300 ease-out group-hover:opacity-100 group-hover:visible group-hover:scale-y-100 group-hover:top-3/4"
                        >
                            @foreach($link['routes'] as $route)
                                <a wire:navigate href="{{$route['link']}}"
                                   class="block px-4 py-2 bg-white hover:bg-gray-100 hover:text-green-500 rounded shadow text-center">
                                    {{$route['name']}}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a wire:navigate :class="[
                                        $store.global.social
                                            ? 'hover:text-blue-500'
                                            : 'hover:text-green-500',
                                        window.location.href.includes('{{ $link['url_part'] }}')
                                            ? ($store.global.social ? '!text-blue-500' : '!text-green-500')
                                            : ''
                                    ]"
                       href="{{$link['route']}}"
                       class="text-dark-400 lg:hidden transition">{{$link['name']}}</a>
                @endif
            @endforeach
            <div class="flex gap-2 items-center group cursor-pointer">
                @auth
                    <a :class="window.location.href.includes('account') ? 'text-green-500' : ''"
                       class="transition group-hover:text-green-500 flex gap-2"
                       href="{{route('account.participations')}}">
                        <x-codicon-account
                            x-bind:class="(window.location.href.includes('account') || window.location.href.includes('login')) || window.location.href.includes('register')
        ? 'text-green-500'
        : ''"
                            class="w-6 h-auto group-hover:text-green-500 transition"
                        />
                        <span class="md:hidden">Мой кабинет</span>
                        </a>
                @else
                    <a
                        :class="window.location.href.includes('login')  || window.location.href.includes('register') ? 'text-green-500' : ''"
                        class="transition group-hover:text-green-500" wire:navigate
                        href="{{route('login')}}">Войти</a>
                @endauth
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="flex-col gap-4 hidden lg:flex fixed h-[calc(100vh-58px)] w-full max-w-3xl top-[58px] bg-white left-0 p-8">
        <div class="flex w-ful gap-2 border-b border-gray-200 pb-2 justify-between">
            <a class="text-2xl font-medium" :class="$store.global.social ? 'text-blue-500' : 'text-dark-200 order-2'" href="{{route('social.index')}}">Социальная сеть</a>
            <a class="text-2xl font-medium" :class="!$store.global.social ? 'text-green-500 order-1' : 'text-dark-200'" href="{{route('portal.index')}}">Издательство</a>
        </div>
        @foreach($links as $link)
            @if($link['routes'] ?? null)
                <div class="relative">
                    <p class="font-normal">{{$link['name']}}</p>
                    <div class="flex flex-col gap-2">
                        @foreach($link['routes'] as $route)
                            <x-ui.link-simple wire:navigate href="{{$route['link']}}"
                                              class="block ">
                                {{$route['name']}}
                            </x-ui.link-simple>
                        @endforeach
                    </div>
                </div>
            @else
                <x-ui.link-simple href="{{$link['route']}}">{{$link['name']}}</x-ui.link-simple>
            @endif
        @endforeach
    </div>
</header>
<script type="module">
    Alpine.store('global', {
        social: window.location.href.includes('social'),
    })
</script>
