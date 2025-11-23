@props([
    'collection',
    'showEpurchase' => false
])
<div class="flex gap-4 container flex-1 p-4 md:flex-col md:justify-center md:text-center">
    <div class="min-w-[140px] max-w-[140px]  md:min-w-[140px]  md:max-w-[140px] relative md:mx-auto">
        <x-book3d :cover="$collection->getFirstMediaUrl('cover_front')" class=" left-0 bottom-0"/>
    </div>
    <div class="flex flex-col gap-2">
        <p class="font-normal text-3xl">{{$collection['title']}}</p>
        <div class="flex flex-col w-full mt-auto gap-4">
            <div x-data="{ open: false }" class="relative inline-block text-left">
                @if((count(($collection['selling_links'] ?? [])) > 0) || $showEpurchase)
                    <button @click="open = !open"
                            class="text-green-500 border text-xl border-green-500 min-w-max flex gap-2 items-center justify-center w-full rounded-lg py-1 px-8 cursor-pointer transition hover:bg-green-500 hover:text-white">
                        Варианты покупки
                    </button>
                    <div @click.away="open = false" x-show="open" x-transition
                         class="absolute mt-2 rounded-xl shadow-lg bg-white ring-1 ring-black/5 z-50">
                        @foreach($collection['selling_links'] ?? [] as $name => $link)
                            <a href="{{$link}}"
                               class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                                <img src="/fixed/logo-{{$name}}.png" class="w-12" alt=""> {{$name}}
                            </a>
                        @endforeach
                        @if($showEpurchase)
                            <a wire:click="createPayment({{$collection['id']}}, 100)"
                               class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                                Электронная версия (100 руб.)
                            </a>
                        @endif
                    </div>
                @endif
            </div>
            <x-ui.link href="{{route('portal.collection', ['slug' => $collection['slug']])}}">
                Подробнее
            </x-ui.link>
        </div>
    </div>
</div>
