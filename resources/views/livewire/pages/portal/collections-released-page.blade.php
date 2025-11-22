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
            <x-ui.cards.card-collection-small :collection="$collection"/>
        @empty
            <h3 class="text-6xl font-bold text-dark-100 mx-auto text-nowrap text-center col-span-3">Ничего не найдено</h3>
        @endforelse
    </div>
</main>
