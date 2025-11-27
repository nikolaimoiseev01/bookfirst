<nav x-data="" class="h-full fixed top-16 left-0 px-4 flex flex-col gap-2 py-4 border-r bg-white border-dark-100 sm:border-r-0 sm:border-t sm:fixed sm:bottom-0 sm:left-0 sm:w-full sm:flex-row sm:max-w-svw sm:h-fit sm:top-auto sm:overflow-auto sm:py-2 scrollbar-none sm:z-50 sm:justify-between">
    @foreach($links as $link)
        <a wire:navigate href="{{ $link['url'] }}"
            :class="window.location.href.includes('{{$link['url']}}') ? 'bg-green-500' : ''"
            class="flex gap-2 group px-4 py-1 rounded-lg items-center cursor-pointer w-full sm:min-w-10 sm:min-h-10 sm:h-10 sm:w-10 sm:!px-1 sm:justify-center">
            <x-dynamic-component :component="$link['icon']" class="w-6 h-6 text-dark-100 transition
             {{ str_contains(url()->current(), $link['url'])
                ? 'text-white group-hover:!text-white'
                : 'group-hover:text-green-500' }}"/>
            <span
               :class="window.location.href.includes('{{$link['url']}}') ? 'text-white  group-hover:text-white' : 'group-hover:text-green-500'"
               class="text-dark-100 font-semibold text-xl  transition text-nowrap md:hidden">{{ $link['name'] }}</span>
        </a>
    @endforeach
</nav>
