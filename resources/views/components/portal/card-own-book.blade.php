<a wire:navigate href="{{route('portal.own_book', ['slug' => $ownBook['slug']])}}" class="flex flex-col p-4 gap-2 container w-fit max-w-64 min-w-64 cursor-pointer hover:scale-[1.01] transition">
    <div class="w-full relative">
        <x-book3d :cover="$ownBook->getFirstMediaUrl('cover_front')"/>
    </div>
    <p class="font-normal line-clamp-2">{{$ownBook['title']}}</p>
    <span class="text-xl italic text-dark-200 mt-auto">{{$ownBook['author']}}</span>
</a>
