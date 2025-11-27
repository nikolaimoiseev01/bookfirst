<main class="flex-1 content mb-32">
    @section('title')
        Поиск по сайту
    @endsection
    <h3 class="mb-4">Результаты поиска по запросу: {{$search_request}}</h3>

    <div class="flex flex-col mb-8" x-data="{show: true}">
        <div class="flex gap-4">
            <h4 class="mb-2">Сборники</h4>
            <x-bi-chevron-down @click="show = !show" x-bind:class="show ? 'rotate-180' : ''"
                               class="w-8 h-auto cursor-pointer transition fill-dark-300"/>
        </div>
        <div class="flex flex-col gap-4 flex-wrap" x-show="show" x-cloak x-collapse.duration.400ms>
            <div class="gap-8 grid grid-cols-3 lg:grid-cols-2 md:!grid-cols-1">
                @forelse($collections->take(5) as $collection)
                    <x-ui.cards.card-collection-small :collection="$collection"/>
                @empty
                    <p class="text-dark-300">Ничего не найдено</p>
                @endforelse
            </div>
            @if(count($collections) > 5)
                <p>Показаны не все результаты.</p>
                <x-ui.link-simple
                    href="{{route('portal.collections.released', ['searchText' => $search_request])}}">
                    Смотреть все
                </x-ui.link-simple>
            @endif
        </div>
    </div>
    <div class="flex flex-col mb-8" x-data="{show: true}">
        <div class="flex gap-4">
            <h4 class="mb-2">Собтсвенные книги</h4>
            <x-bi-chevron-down @click="show = !show" x-bind:class="show ? 'rotate-180' : ''"
                               class="w-8 h-auto cursor-pointer transition fill-dark-300"/>
        </div>
        <div class="flex flex-col gap-4" x-show="show" x-cloak x-collapse.duration.400ms>
            <div class="flex gap-8 flex-wrap">
                @forelse($ownBooks->take(5) as $ownBook)
                    <x-ui.cards.card-own-book class="!min-w-48 !max-w-48" :ownbook="$ownBook"/>
                @empty
                    <p class="text-dark-300">Ничего не найдено</p>
                @endforelse
            </div>
            @if(count($ownBooks) > 5)
                <p>Показаны не все результаты.</p>
                <x-ui.link-simple
                    href="{{route('portal.own_books.released', ['searchText' => $search_request])}}">
                    Смотреть все
                </x-ui.link-simple>
            @endif
        </div>
    </div>

    <div class="flex flex-col mb-8" x-data="{show: true}">
        <div class="flex gap-4">
            <h4 class="mb-2">Пользователи</h4>
            <x-bi-chevron-down @click="show = !show" x-bind:class="show ? 'rotate-180' : ''"
                               class="w-8 h-auto cursor-pointer transition fill-dark-300"/>
        </div>
        <div x-show="show" x-cloak x-collapse.duration.400ms class="flex gap-8 flex-wrap">
            @forelse($users as $author)
                <x-ui.cards.author-card :author="$author"/>
            @empty
                <p class="text-dark-300">Ничего не найдено</p>
            @endforelse
        </div>
    </div>

</main>
