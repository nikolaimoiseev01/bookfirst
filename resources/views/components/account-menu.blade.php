<nav x-data=""
     class="shrink-0  self-stretch top-16 left-0 px-4 flex flex-col gap-2 py-4 border-r bg-white border-dark-100 sm:border-r-0 sm:border-t sm:fixed sm:bottom-0 sm:left-0 sm:w-full sm:flex-row sm:max-w-svw sm:h-fit sm:top-auto sm:overflow-auto sm:py-2 scrollbar-none sm:!z-[100] sm:justify-between">
    @foreach($links as $link)
        <div class="flex items-center relative">
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
            @if($link['new'])
                <div
                    :class="window.location.href.includes('{{$link['url']}}') ? 'hidden' : ''"
                    class="relative ml-auto
               px-2 py-0.5 text-xs font-semibold rounded-full md:p-0 md:w-3 md:h-3 md:min-w-3 md:absolute md:top-0 md:right-0
               bg-red-300 text-white">
                    <span class="md:hidden"> Новое!</span>
                </div>
            @endif
        </div>

    @endforeach
    @hasrole('admin')
    <a href="/admin"
       class="flex gap-2 group px-4 py-1 rounded-lg items-center cursor-pointer w-full sm:min-w-10 sm:min-h-10 sm:h-10 sm:w-10 sm:!px-1 sm:justify-center">
        <x-dynamic-component :component="$link['icon']"
                             class="group-hover:text-green-500 w-6 h-6 text-dark-100 transition"/>
        <span
            class="group-hover:text-green-500 text-dark-100 font-semibold text-xl  transition text-nowrap md:hidden">Админка</span>
    </a>
    @endhasrole
</nav>

