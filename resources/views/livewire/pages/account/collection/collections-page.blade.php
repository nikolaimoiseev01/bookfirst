<div class="mb-16">
    @section('title')
        Участие в сборниках
    @endsection
    <x-ui.link href="{{route('portal.collections.actual')}}" class="mb-6 w-fit">Актуальные сборники для участия
    </x-ui.link>
    <div class="flex gap-6 flex-wrap">
        @forelse ($participations as $participation)
            <div class="container flex gap-4 p-4 w-fit max-w-3xl">
                <x-book2d :cover="$participation->collection->getFirstMediaUrl('cover_front')" class="w-32"/>
                <div class="flex flex-col">
                    <p class="font-semibold mb-4 text-3xl">{{$participation->collection['title']}}</p>
                    <div class="flex flex-col gap-2 mt-auto">
                        <p class="text-xl"><span
                                class="font-normal">Статус участия: </span>{{$participation->participationStatus['name']}}
                        </p>
                        <p class="text-xl"><span
                                class="font-normal">Статус сборника: </span> {{$participation->collection->collectionStatus['name']}}
                        </p>
                        <x-ui.link>Страница моего участия</x-ui.link>
                    </div>
                </div>
            </div>
        @empty
            <p class="italic">Еще не было сборников с вашим участием, но все еще впереди 🙂</p>
        @endforelse
    </div>
    {{ $participations->links() }}
</div>
