<div class="mb-16">
    @section('title')
        Произведения
    @endsection
    <div class="flex w-full justify-between mb-6 sm:flex-col sm:justify-center gap-4">
        <div class="flex gap-4">
            <div x-data="{ open: false }" class="relative inline-block text-left sm:mx-auto">
                <button @click="open = !open"
                        class="text-green-500 border text-xl border-green-500 min-w-max flex gap-2 items-center justify-center w-full rounded-lg py-1 px-8 cursor-pointer transition hover:bg-green-500 hover:text-white">
                    Добавить
                </button>

                <div @click.away="open = false" x-show="open" x-transition
                     class="absolute mt-2 rounded-lg shadow-lg bg-white ring-1 ring-black/5 z-50">
                    <x-ui.link-simple href="{{route('account.works.create.manual')}}"
                       class="w-full flex gap-2 px-4 py-2 font-light items-center hover:bg-gray-100">
                        <span class="text-xl text-dark-400">Вручную</span>
                        <x-ui.question-mark class="text-lg w-4 h-4">Заполнить заголовок и текст для конкретного произведения
                        </x-ui.question-mark>
                    </x-ui.link-simple>


                    <x-ui.link-simple href="{{route('account.works.create.file')}}"
                                      class="flex font-light items-center gap-2 px-4 py-2 hover:bg-gray-100">
                        <span class="text-xl text-nowrap text-dark-400">Из файла</span>
                        <x-ui.question-mark class="text-lg w-4 h-4">Создать несколько произведений сразу, загрузив файл, отредактируемый по правилам
                        </x-ui.question-mark>
                    </x-ui.link-simple>
                </div>
            </div>
        </div>
        <x-ui.input.search-bar class="sm:mx-auto"/>
    </div>
    <div class="flex flex-col">
        <div class="flex gap-6 flex-wrap">
            @forelse ($works as $work)
                <div class="container p-4 flex flex-col w-fit sm:w-full">
                    <x-ui.link-simple href="{{route('social.work', $work['id'])}}" class="truncate w-52 text-xl">{{$work['title']}}</x-ui.link-simple>
                    <p class="text-base">
                        Опубликовано: {{ \Carbon\Carbon::parse($work['created_at'])->translatedFormat('j F') }}</p>
                    <div class="h-px w-full bg-dark-100 my-2"></div>
                    <div class="flex">
                        <div class="flex items-center gap-1">
                            <span class="text-dark-200 text-xl">{{$work->likes->count()}}</span>
                            <x-bi-heart class="w-5 h-5 text-dark-200 pt-1"/>
                        </div>
                        <div class="flex items-center gap-2 ml-auto">
                            <x-ui.link-simple href="{{route('account.work.edit', $work['id'])}}"  class="flex">
                                <x-ui.tooltip-wrap text="Редактировать">
                                    <x-lucide-edit class="w-5 h-5 text-green-400"/>
                                </x-ui.tooltip-wrap>
                            </x-ui.link-simple>
                            <button class="flex" wire:click="deleteConfirm({{$work['id']}})">
                                <x-ui.tooltip-wrap text="Удалить">
                                    <x-bi-trash wire:loading.remove wire:target="deleteConfirm({{ $work['id'] }})" class="w-5 h-6 text-red-300"/>
                                    <x-ui.spinner wire:loading wire:target="deleteConfirm({{ $work['id'] }})" class="w-4 h-auto"/>
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
