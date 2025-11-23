<div class="mb-16">
    @section('title')
        Мои покупки
    @endsection
    <div class="flex gap-6 flex-wrap">
        @forelse ($purchases as $purchase)
            <div
                class="container flex flex-col gap-2 p-4 w-fit max-w-2xl">
                <p class="font-semibold mb-2 text-3xl">{{$purchase->model['title']}}</p>
                <p class="text-gray-400 text-xl italic">Дата покупки: {{formatDate($purchase->created_at)}}</p>
                <x-ui.link
                    download="{{$purchase->model['title']}}"
                    :navigate="false"
                    href="{{$purchase->model->getFirstMediaUrl('inside_file')}}">
                    Скачать
                </x-ui.link>
            </div>
        @empty
            <p class="italic">Еще не было покупок через
                наш портал, но все еще впереди 🙂</p>
        @endforelse
    </div>
</div>
