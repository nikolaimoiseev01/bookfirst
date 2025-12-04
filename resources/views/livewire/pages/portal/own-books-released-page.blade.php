<main class="flex-1 content mb-32">
    @section('title')
        Книги выпущенные
    @endsection
    <h1 class="mb-8">Выпущенные книги</h1>
    <div class="flex justify-between items-center mb-8 gap-4 flex-wrap md:justify-center">
        <x-ui.input.search-bar class=""/>
        <div class="flex gap-4 ml-auto items-center w-fit md:!mx-auto">
            <span>{{$take}} / {{$totalCnt}}</span>
            @if($take < $totalCnt)
                <x-ui.load-more-button/>
            @endif
        </div>
    </div>

    <div class="flex gap-8 flex-wrap justify-evenly">
        @forelse($ownBooks as $ownBook)
            <x-ui.cards.card-own-book :ownbook="$ownBook"/>
        @empty
            <h3 class="text-6xl font-bold text-dark-100 mx-auto text-nowrap text-center col-span-3">
                Ничего не найдено</h3>
        @endforelse
    </div>

    <div class="mx-auto text-center mt-8">
        @if($take < $totalCnt)
            <x-ui.load-more-button/>
        @endif
    </div>


</main>
