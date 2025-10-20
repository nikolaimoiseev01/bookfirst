<header x-data="{mobileMenuOpen: false}"
        class="fixed w-full shadow-[0_1px_4px_#00000026] top-0 px-5 py-2 md:py-4 flex justify-between items-center z-50 bg-white dark:bg-dark_bg">
    <div class="flex gap-2 items-center">
        <div class="flex gap-2 items-center md:absolute md:left-1/2 md:-translate-x-1/2 md:top-1/2 md:-translate-y-1/2">
            <x-ui.application-logo class="w-12 h-12 mr-2"/>
            <a class="italic text-3xl font-light sm:hidden">Первая Книга</a>
        </div>
        <div class="w-px h-7 bg-gray-300 xl:hidden"></div>
        <a wire:navigate :class="$store.global.social ? 'text-dark-400 hover:text-green-500' : 'text-green-500'"
           class="font-light cursor-pointer italic text-xl xl:hidden" href="{{route('portal.index')}}">
            Независимое издательство
        </a>
        <div class="w-px h-7 bg-gray-300 xl:hidden"></div>

        <a wire:navigate :class="$store.global.social ? 'text-blue-500' : 'text-dark-400 hover:text-blue-500'"
           class="font-light cursor-pointer italic text-xl xl:hidden" href="{{route('social.index')}}">
            Социальная сеть
        </a>
    </div>
    <div class="flex gap-6 items-center text-2xl text-black-400 dark:text-white md:justify-between md:w-full">
        @foreach($links as $link)
            @if($link['routes'] ?? null)
                <div class="relative group md:hidden">
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
                   href="{{$link['route']}}" class="text-dark-400 md:hidden transition">{{$link['name']}}</a>
            @endif
        @endforeach
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
                class="hamburger hamburger--converge !hidden md:!block" type="button">
            <div class="inner">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </button>
        <div class="flex gap-2 items-center group cursor-pointer">
            <x-codicon-account
                x-bind:class="(window.location.href.includes('account') || window.location.href.includes('login')) || window.location.href.includes('register')
        ? 'text-green-500'
        : ''"
                class="w-6 h-auto group-hover:text-green-500 transition"
            />
            @auth
                <a :class="window.location.href.includes('account') ? 'text-green-500' : ''"
                   class="transition group-hover:text-green-500" wire:navigate
                   href="{{route('account.participations')}}">
                    Мой кабинет</a>
            @else
                <a
                    :class="window.location.href.includes('login')  || window.location.href.includes('register') ? 'text-green-500' : ''"
                    class="transition group-hover:text-green-500" wire:navigate href="{{route('login')}}">Войти</a>
            @endauth
        </div>


    </div>
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="flex-col gap-4 hidden md:flex fixed h-[calc(100vh-58px)] w-full max-w-3xl top-[58px] bg-white left-0 p-8">
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
