<main class="flex-1 content">
    <div class="flex gap-4 mx-auto justify-center mb-16 flex-wrap">
        <x-ui.link-simple href="{{route('portal.collections.actual')}}" class="text-6xl  font-normal !text-dark-100 hover:!text-green-500 transition">Актуальные</x-ui.link-simple>
        <p class="text-6xl text-green-500 font-normal">Выпущенные</p>
    </div>
    <div class="gap-8 flex-wrap grid grid-cols-3">
        @foreach($collections as $collection)
            <div class="flex gap-4 container flex-1 p-4">
                <div class="min-w-[140px] max-w-[140px]  md:min-w-[140px]  md:max-w-[140px] relative">
                    <x-book3d :cover="$collection->getFirstMediaUrl('cover_2d')" class=" left-0 bottom-0"/>
                </div>
                <div class="flex flex-col gap-2">
                    <p class="font-normal text-3xl">{{$collection['name']}}</p>
                    <div class="flex flex-col w-full">
                        <x-ui.link href="{{route('portal.collection', ['slug' => $collection['slug']])}}">Подробнее</x-ui.link>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</main>
