<div class="mb-16">
    @section('title')
        Собственные книги
    @endsection
    <div class="flex gap-4 items-center flex-wrap mb-6 sm:justify-center sm:text-center">
        <x-ui.link href="{{route('account.own_book.create')}}" class="w-fit">Издать новую книгу
        </x-ui.link>
        <x-ui.link-simple href="{{route('portal.own_book.application')}}" class="w-fit">Рассчитать стоимость издания
            и печати
        </x-ui.link-simple>
    </div>

    <div class="flex gap-6 flex-wrap sm:justify-center">
        @forelse ($own_books as $own_book)
            <div class="container flex flex-col gap-4 p-4 w-fit max-w-2xl">
                <div class="flex gap-4 md:flex-col md:justify-center md:text-center">
                    <x-book2d :cover="$own_book->getFirstMediaUrl('cover_front')" class="w-32 min-w-32 md:mx-auto"/>
                    <div class="flex flex-col">
                        <p class="font-semibold mb-4 text-3xl line-clamp-2">{{$own_book['title']}}</p>
                        <p class="text-xl font-normal">Общий статус: {{$own_book['status_general']}}
                        </p>
                        <p class="text-xl"><span
                                class="font-normal">Статус ВБ: </span> {{$own_book['status_inside']}}
                        </p>
                        <p class="text-xl"><span
                                class="font-normal">Статус обложки: </span> {{$own_book['status_cover']}}
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
