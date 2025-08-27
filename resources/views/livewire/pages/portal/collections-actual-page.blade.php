<main class="flex-1">
    <div class="flex gap-4 mx-auto justify-center mb-16 flex-wrap">
        <p class="text-6xl text-green-500 font-normal">Актуальные</p>
        <x-ui.link-simple href="{{route('portal.collections.released')}}" class="text-6xl  font-normal !text-dark-100 hover:!text-green-500 transition">Выпущенные</x-ui.link-simple>
    </div>
    <div class="flex flex-col gap-16 content items-center">
        @foreach($collections as $collection)
            <x-portal.card-collection-wide :collection="$collection"/>
        @endforeach
    </div>
</main>
