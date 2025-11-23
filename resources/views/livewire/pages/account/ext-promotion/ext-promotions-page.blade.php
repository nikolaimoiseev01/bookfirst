<div class="mb-16">
    @section('title')
        Мои продвижения
    @endsection
    <x-ui.link
        href="{{route('account.ext_promotion.create')}}"
        class="mb-6 w-fit sm:mx-auto">
        Подать заявку на продвижение
    </x-ui.link>
    <div class="flex gap-6 flex-wrap">
        @forelse ($extPromotions as $extPromotion)
            <div
                class="container flex flex-col gap-2 p-4 w-fit max-w-2xl">
                <p class="font-semibold mb-2 text-3xl">Продвижение на сайте {{$extPromotion['site']}}</p>
                <div class="flex flex-col mt-auto">
                    <p class="text-xl"><span
                            class="font-normal">Создан: </span>{{formatDate($extPromotion['created_at'], 'j F')}}
                    </p>
                    <p class="text-xl"><span
                            class="font-normal">Статус: </span> {{$extPromotion['status']}}
                    </p>
                </div>
                <x-ui.link
                    href="{{route('account.ext_promotion.index', $extPromotion['id'])}}">
                    Страница продвижения
                </x-ui.link>
            </div>
        @empty
            <p class="italic">Еще не было продвижений через
                наш портал, но все еще впереди 🙂</p>
        @endforelse
    </div>
</div>
