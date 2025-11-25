@props([
    'cover3d' => true
])
<a wire:navigate
   href="{{route('portal.own_book', ['slug' => $ownBook['slug']])}}" {{ $attributes->merge(['class' => 'flex flex-col p-4 gap-2 container w-fit max-w-64 min-w-64 cursor-pointer hover:scale-[1.01] transition']) }} ">

        <div class="w-full relative">
            @if($cover3d)
            <x-book3d :cover="$ownBook->getFirstMediaUrl('cover_front', 'thumb')"/>
                @else
                <img src="{{$ownBook->getFirstMediaUrl('cover_front')}}" class="w-56 rounded-lg" alt="">
            @endif
        </div>

<p class="font-normal line-clamp-2">{{$ownBook['title']}}</p>
<span class="text-xl italic text-dark-200 mt-auto">{{$ownBook['author']}}</span>
</a>
