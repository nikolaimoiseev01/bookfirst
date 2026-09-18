@props([
    'participation',
])

<a wire:navigate
   href="{{route('portal.collection', ['slug' => $participation->collection['slug']])}}"
   {{ $attributes->merge(['class' => 'flex flex-col p-4 gap-3 container w-fit max-w-64 min-w-64 cursor-pointer hover:scale-[1.01] transition']) }}>
    <div class="w-full relative">
        <x-book3d :cover="$participation->collection->getFirstMediaUrl('cover_front', 'thumb')"/>
    </div>
    <p class="font-normal line-clamp-2">{{$participation->collection['title']}}</p>
    <span class="text-xl italic text-dark-200 line-clamp-1">{{$participation['author_name']}}</span>
</a>
