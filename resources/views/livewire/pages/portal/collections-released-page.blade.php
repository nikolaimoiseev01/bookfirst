<main class="flex-1 content mb-32">
    @section('title')
        Выпущенные сборники
    @endsection
    <div class="flex gap-8 mx-auto justify-center mb-8 flex-wrap">
        <x-ui.link-simple href="{{route('portal.collections.actual')}}"
                          class="text-6xl  font-normal !text-dark-100 hover:!text-green-500 transition">Актуальные
        </x-ui.link-simple>
        <p class="text-6xl text-green-500 font-normal">Выпущенные</p>
    </div>
    <div class="flex justify-between items-center mb-8">
        <x-ui.input.search-bar class=""/>
        <div class="flex gap-4 ml-auto items-center w-fit">
            <span>{{$take}} / {{$totalCnt}}</span>
            @if($take < $totalCnt)
                <x-ui.load-more-button/>
            @endif
        </div>
    </div>

    <div class="gap-8 flex-wrap grid grid-cols-3">
        @forelse($collections as $collection)
            <div class="flex gap-4 container flex-1 p-4">
                <div class="min-w-[140px] max-w-[140px]  md:min-w-[140px]  md:max-w-[140px] relative">
                    <x-book3d :cover="$collection->getFirstMediaUrl('cover_front')" class=" left-0 bottom-0"/>
                </div>
                <div class="flex flex-col gap-2">
                    <p class="font-normal text-3xl">{{$collection['title']}}</p>
                    <div class="flex flex-col w-full mt-auto gap-4">
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open"
                                    class="text-green-500 border text-xl border-green-500 min-w-max flex gap-2 items-center justify-center w-full rounded-lg py-1 px-8 cursor-pointer transition hover:bg-green-500 hover:text-white">
                                Варианты покупки
                            </button>

                            <div @click.away="open = false" x-show="open" x-transition
                                 class="absolute mt-2 rounded-xl shadow-lg bg-white ring-1 ring-black/5 z-50">
                                @foreach($collection['selling_links'] ?? [] as $name => $link)
                                    <a href="{{$link}}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                                        <img src="/fixed/logo-{{$name}}.png" class="w-12" alt=""> {{$name}}
                                    </a>
                                @endforeach
                                <a href="" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                                    Электронная версия (100 руб.)
                                </a>
                            </div>
                        </div>
                        <x-ui.link href="{{route('portal.collection', ['slug' => $collection['slug']])}}">Подробнее
                        </x-ui.link>
                    </div>
                </div>
            </div>
        @empty
            <h3 class="text-6xl font-bold text-dark-100 mx-auto text-nowrap text-center col-span-3">Ничего не найдено</h3>
        @endforelse
    </div>
</main>
