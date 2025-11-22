<main class="flex-1 mb-32">
    @section('title')
        Актуальные сборинки
    @endsection
    <div class="flex gap-8 mx-auto justify-center mb-16 flex-wrap">
        <p class="text-6xl text-green-500 font-normal">Актуальные</p>
        <x-ui.link-simple href="{{route('portal.collections.released')}}" class="text-6xl  font-normal !text-dark-100 hover:!text-green-500 transition">Выпущенные</x-ui.link-simple>
    </div>
    <div class="flex flex-col gap-16 content items-center">
        @foreach($collections as $collection)
            <x-ui.cards.card-collection-wide :collection="$collection"/>
        @endforeach
    </div>
</main>
