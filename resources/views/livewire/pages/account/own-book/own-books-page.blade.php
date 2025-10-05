<div class="mb-16">
    @section('title')
        Собственные книги
    @endsection
    <div class="flex gap-4">
        <x-ui.link href="{{route('account.own_book.create')}}" class="mb-6 w-fit">Издать новую книгу
        </x-ui.link>
        <x-ui.link-simple href="{{route('portal.own_book.application')}}" class="mb-6 w-fit">Рассчитать стоимость издания
            и печати
        </x-ui.link-simple>
    </div>

    <div class="flex gap-6 flex-wrap">
        @forelse ($own_books as $own_book)
            <div class="container flex flex-col gap-4 p-4 w-fit max-w-2xl">
                <div class="flex gap-4">
                    <x-book2d :cover="$own_book->getFirstMediaUrl('cover_front')" class="w-32 min-w-32"/>
                    <div class="flex flex-col">
                        <p class="font-semibold mb-4 text-3xl line-clamp-2">{{$own_book['title']}}</p>
                        <p class="text-2xl font-normal">Общий статус: {{$own_book->ownBookStatus['name']}}
                        </p>
                        <p class="text-2xl"><span
                                class="font-normal">Статус ВБ: </span> {{$own_book->ownBookInsideStatus['name']}}
                        </p>
                        <p class="text-2xl"><span
                                class="font-normal">Статус обложки: </span> {{$own_book->ownBookCoverStatus['name']}}
                        </p>
                    </div>
                </div>
                <x-ui.link href="{{route('account.own_book.index', $own_book['id'])}}">Страница издания</x-ui.link>
            </div>
        @empty
            <p class="italic">Вы еще не издавали у нас книги, но все еще впереди 🙂</p>
        @endforelse
    </div>
    {{ $own_books->links() }}
</div>
