<div class="container flex gap-10 relative p-4 lg:flex-col lg:items-center md:pt-24 w-full max-w-full">
    <div class="min-w-[180px] max-w-[180px]  md:min-w-[140px]  md:max-w-[140px] relative">
        <x-book3d :cover="$collection->getFirstMediaUrl('cover_front', 'thumb')" class=" left-0 bottom-0"/>
    </div>
    <div class="flex flex-col gap-4 lg:items-center lg:text-center">
        <h3>{{$collection['title']}}</h3>
        <p>{{$collection['description']}}</p>
    </div>
    <div class="flex flex-col justify-center gap-4 lg:w-full">
        <x-ui.link href="{{route('portal.collection', ['slug' => $collection['slug']])}}">Подробнее</x-ui.link>
        <x-ui.link data-check-logged href="{{route('account.participation.create', ['collection_id' => $collection['id']])}}">Принять участие</x-ui.link>
    </div>
    <div class="absolute top-0 right-8 inline-block md:right-0 md:left-1/2 md:-translate-x-1/2 w-fit">
        <div
            class="bg-green-500 text-white text-center px-4 pb-4 text-lg flex flex-col
           [clip-path:polygon(0_0,100%_0,100%_100%,50%_85%,0_100%)]">
                    <span>Заявки до:</span>
                    <span>{{ \Carbon\Carbon::parse($collection['date_apps_end'])->translatedFormat('j F') }}</span>
        </div>
    </div>
</div>
