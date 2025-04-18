<header class="fixed w-full shadow-[0_1px_4px_#00000026] px-5 py-2 flex justify-between items-center">
    <div class="flex gap-2 items-center">
        <x-ui.application-logo class="w-12 h-12 mr-2"/>
        <a class="italic text-3xl font-light">Первая Книга</a>
        <div class="w-px h-7 bg-gray-300"></div>
        <x-ui.link-simple class="italic text-xl" :href="route('portal.index')">Независимое издательство
        </x-ui.link-simple>
        <div class="w-px h-7 bg-gray-300"></div>
        <x-ui.link-simple class="social italic text-xl" :social="true" :href="route('portal.index')">Социальная сеть
        </x-ui.link-simple>
    </div>
    <div class="flex gap-6 items-center text-2xl text-black-400">
        @foreach($links as $link)
            @if($link['routes'] ?? null)
                <div class="relative group">
                    <p class="cursor-pointer">{{$link['name']}}</p>
                    <div
                        class="absolute left-1/2 -translate-x-1/2 top-1/2 mt-2 w-max invisible opacity-0 scale-y-95 transform transition-all duration-300 ease-out group-hover:opacity-100 group-hover:visible group-hover:scale-y-100 group-hover:top-3/4"
                    >
                        @foreach($link['routes'] as $route)
                            <a wire:navigate href="{{$route['link']}}" class="block px-4 py-2 bg-white hover:bg-gray-100 rounded shadow text-center">
                                {{$route['name']}}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{$link['route']}}">{{$link['name']}}</a>
            @endif
        @endforeach
        <a wire:navigate href="">Мой кабинет</a>
    </div>
</header>
