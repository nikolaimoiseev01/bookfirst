<div class="mb-16">
    @section('title')
        Произведения
    @endsection
    <div class="flex w-full justify-between mb-6">
        <div class="flex gap-4">
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <button @click="open = !open"
                        class="text-green-500 border text-xl border-green-500 min-w-max flex gap-2 items-center justify-center w-full rounded-lg py-1 px-8 cursor-pointer transition hover:bg-green-500 hover:text-white">
                    Добавить
                </button>

                <div @click.away="open = false" x-show="open" x-transition
                     class="absolute mt-2 rounded-lg shadow-lg bg-white ring-1 ring-black/5 z-50">
                    <div class="flex gap-2 px-4 py-2 items-center">
                        <a href="" class="text-2xl hover:bg-gray-10 font-light items-center">
                            Вручную
                        </a>
                        <x-ui.question-mark class="w-4 h-4">Заполнить заголовок и текст вручную</x-ui.question-mark>
                    </div>

                    <a href="" class="flex text-2xl font-light items-center gap-2 px-4 py-2 hover:bg-gray-100">
                        Из файла
                    </a>
                </div>
            </div>
        </div>
        <x-ui.input.search-bar/>
    </div>
    <div class="flex flex-col">
        <div class="flex gap-6 flex-wrap">
            @forelse ($works as $work)
                <div class="container p-4 flex flex-col w-fit">
                    <x-ui.link-simple class="truncate w-52 text-xl">{{$work['title']}}</x-ui.link-simple>
                    <p class="text-base">
                        Опубликовано: {{ \Carbon\Carbon::parse($work['created_at'])->translatedFormat('j F') }}</p>
                    <div class="h-px w-full bg-dark-100 my-2"></div>
                    <div class="flex">
                        <div class="flex items-center gap-1">
                            <span class="text-dark-200 text-xl">{{$work->workLikes->count()}}</span>
                            <x-bi-heart class="w-5 h-5 text-dark-200 pt-1"/>
                        </div>
                        <div class="flex items-center gap-2 ml-auto">
                            <a class="flex">
                                <x-ui.tooltip-wrap text="Редактировать">
                                    <x-lucide-edit class="w-5 h-5 text-green-400"/>
                                </x-ui.tooltip-wrap>
                            </a>
                            <button class="flex">
                                <x-ui.tooltip-wrap text="Удалить">
                                    <x-bi-trash class="w-5 h-6 text-red-300"/>
                                </x-ui.tooltip-wrap>
                            </button>
                        </div>

                    </div>
                </div>
            @empty
                <p class="italic">Вы еще не размещали у нас произведения, но все еще впереди 🙂</p>
            @endforelse
        </div>
        {{ $works->links() }}
    </div>
</div>
