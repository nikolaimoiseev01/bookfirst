<nav x-data="" class="px-4 flex flex-col gap-2 py-4 border-r border-r-dark-100">
    @foreach($links as $link)
        <div
            :class="window.location.href.includes('{{$link['url']}}') ? 'bg-green-500' : ''"
            class="flex gap-2 group px-4 py-1 rounded-lg items-center cursor-pointer w-full">
            <x-dynamic-component :component="$link['icon']" class="w-6 h-6 text-dark-100 transition
             {{ str_contains(url()->current(), $link['url'])
                ? 'text-white group-hover:!text-white'
                : 'group-hover:text-green-500' }}"/>
            <a wire:navigate href="{{ $link['url'] }}"
               :class="window.location.href.includes('{{$link['url']}}') ? 'text-white  group-hover:text-white' : 'group-hover:text-green-500'"
               class="text-dark-100 font-semibold text-xl  transition text-nowrap">{{ $link['name'] }}</a>
        </div>
    @endforeach
</nav>
