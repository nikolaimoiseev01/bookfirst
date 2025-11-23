<main class="flex-1 content mb-32">
    @section('title')
        Выпущенные сборники
    @endsection
    <div class="flex gap-8 mx-auto justify-center mb-8 flex-wrap">
        <x-ui.link-simple href="{{route('portal.collections.actual')}}"
                          class="text-6xl  font-normal !text-dark-100 hover:!text-green-500 transition">
            Актуальные
        </x-ui.link-simple>
        <p class="text-6xl text-green-500 font-normal">Выпущенные</p>
    </div>
    <div class="flex justify-between items-center mb-8 flex-wrap gap-8 md:justify-center">
        <x-ui.input.search-bar class=""/>
        <div class="flex gap-4 ml-auto items-center w-fit md:mx-auto">
            <span>{{$take}} / {{$totalCnt}}</span>
        </div>
    </div>

    <div class="gap-8 flex-wrap grid grid-cols-3 xl:grid-cols-2 sm:!grid-cols-1">
        @foreach($collections as $collection)
            <x-ui.cards.card-collection-small :showEpurchase="true" :collection="$collection"/>
        @endforeach
    </div>
    @if($take < $totalCnt)
        <div class="text-center mt-10">
            <x-ui.load-more-button/>
        </div>
    @endif
</main>
