<div class="mb-16">
    @section('title')
        Мои заказы на печать
    @endsection
        <p class="text-xl text-dark-400 italic mb-6">Здесь показываются индивидуальные заказы печатных экземпляров (не в рамках издания) </p>
        <div class="flex gap-4 mb-6">
            <div x-data="{ open: false }" class="relative inline-block text-left sm:mx-auto">
                <button @click="open = !open"
                        class="text-green-500 border text-xl border-green-500 min-w-max flex gap-2 items-center justify-center w-full rounded-lg py-1 px-8 cursor-pointer transition hover:bg-green-500 hover:text-white">
                    Заказать печать
                </button>

                <div @click.away="open = false" x-show="open" x-transition
                     class="absolute mt-2 rounded-lg shadow-lg bg-white ring-1 ring-black/5 z-50">
                    <x-ui.link-simple href="{{route('portal.own_books.released')}}"
                                      class="flex gap-2 px-4 py-2 font-light items-center hover:bg-gray-100">
                        <span class="text-xl text-dark-400">Книги</span>
                        <x-ui.question-mark class="text-lg w-4 h-4">Собственные книги наших авторов
                        </x-ui.question-mark>
                    </x-ui.link-simple>


                    <x-ui.link-simple href="{{route('portal.collections.released')}}"
                                      class="flex font-light items-center gap-2 px-4 py-2 hover:bg-gray-100">
                        <span class="text-xl text-nowrap text-dark-400">Сборники</span>
                        <x-ui.question-mark class="text-lg w-4 h-4">Сборники современных авторов
                        </x-ui.question-mark>
                    </x-ui.link-simple>
                </div>
            </div>
        </div>


    <div class="flex gap-6 flex-wrap">
        @forelse ($printOrders as $printOrder)
            <div
                class="container flex flex-col gap-2 p-4 w-fit max-w-2xl">
                <p class="font-semibold mb-2 text-3xl">{{$printOrder->model['title']}}</p>
                <div class="flex flex-col mt-auto">
                    <p class="text-xl"><span
                            class="font-normal">Создан: </span>{{formatDate($printOrder['created_at'], 'j F')}}
                    </p>
                    <p class="text-xl"><span
                            class="font-normal">Количество: </span> {{$printOrder['books_cnt']}}
                    </p>
                    <p class="text-xl"><span
                            class="font-normal">Статус: </span> {{$printOrder['status']}}
                    </p>
                </div>
                <x-ui.link
                    href="{{route('account.purchase-print.index', $printOrder['id'])}}">
                    Подробнее
                </x-ui.link>
            </div>
        @empty
            <p class="italic">Еще не было продвижений через
                наш портал, но все еще впереди 🙂</p>
        @endforelse
    </div>
</div>
