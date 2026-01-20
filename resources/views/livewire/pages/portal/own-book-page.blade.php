<main class="flex-1 content mb-32">
    @section('title')
        {{$ownBook['title'] ?? 'Нет такой книги'}}
    @endsection
    @if($ownBook)
        <nav class="flex gap-2 mb-12 flex-wrap">
            <x-ui.link-simple href="{{route('portal.own_books.released')}}">Собственные книги
            </x-ui.link-simple>
            <p>/</p>
            <p>{{$ownBook['title']}}</p>
        </nav>
        <section class="mb-24 flex gap-12 lg:flex-col lg:text-center lg:items-center">
            <div class="w-60 min-w-60">
                <x-book3d cover="{{$ownBook->getFirstMediaUrl('cover_front')}}" class=""/>
            </div>
            <div class="flex flex-col gap-4 lg:items-center">
                <h3 class="border-b border-b-dark-600 w-fit">{{$ownBook['title']}}</h3>
                <div class="flex gap-2 items-center">
                    <img src="{{getUserAvatar($ownBook->user)}}" class="w-8 h-8 rounded-full"
                         alt="">
                    <x-ui.link-simple
                        href="{{route('social.user', ['id' => $ownBook['user_id']])}}">{{$ownBook['author']}}</x-ui.link-simple>
                </div>
                @if($ownBook['annotation'])
                    <p>{{$ownBook['annotation']}}</p>
                @else
                    <p>Здесь скоро появится аннотация книги.</p>
                @endif
                <div class="flex flex-wrap gap-8 md:gap-4 md:flex-col md:justify-center">
                    @foreach($ownBook['selling_links'] ?? [] as $name => $link)
                        <a href="{{$link}}" target="_blank"
                           class="flex gap-4 border text-xl border-dark-100 rounded px-4 py-2 hover:bg-green-500 hover:text-white transition">
                            <img src="/fixed/logo-{{$name}}.png" class="w-16" alt="">
                            {{$name}}
                        </a>
                    @endforeach
                    <a wire:click="createPayment(100)" data-check-logged target="_blank"
                       class="flex gap-4 border text-xl border-dark-100 rounded px-4 py-2 hover:bg-green-500 hover:text-white transition">
                        Электронная версия (100 руб.)
                    </a>
                </div>
            </div>
            <div class="container ml-auto flex flex-col w-fit px-4 h-fit lg:!mx-auto">
                @foreach($info as $key => $value)
                    <div class="border-b border-b-dark-100 py-4">
                        <p class="font-normal text-nowrap text-xl">{{$key}}: <span
                                class="font-light">{{$value}}</span></p>
                    </div>
                @endforeach
            </div>
        </section>
        <section x-data="{ tab: '{{ $tabs['default'] ?? 'read_part' }}' }"
                 class="container p-4 transition-all min-w-full">
            <nav class="flex flex-wrap md:justify-center md:flex-col gap-8 md:gap-4 text-4xl relative z-[1]
                                after:absolute after:left-0 after:right-0 after:bottom-0 after:block
                                after:w-full after:h-[2px] after:z-10 after:bg-dark-100 after:rounded
                                after:m-auto after:content-['']
                                after:transition-all after:duration-400">
                @foreach($tabs['tabs'] as $key => $value)
                    <button @click="tab = '{{$key}}'"
                            :class="tab === '{{$key}}' ? 'text-green-500 after:w-full' : 'text-dark-100'"
                            class="cursor-pointer transition
                                relative pb-4
                                after:absolute after:left-0 after:right-0 after:bottom-0 after:block
                                after:w-0 after:h-[2px] after:z-20 after:bg-green-500 after:rounded
                                after:m-auto after:content-['']
                                after:transition-all after:duration-400
                                hover:text-green-500
                        "
                    >{{$value}}</button>
                @endforeach
                <a wire:navigate href="{{route('portal.help.own_book')}}"
                   class="text-dark-100  pb-4 hover:text-green-500 transition ml-auto md:mx-auto">Инструкция</a>
            </nav>

            <section x-show="tab === 'read_part'" class="p-4">
                <iframe src="{{$ownBook->getFirstMediaUrl('inside_file_preview')}}"
                        width="100%" height="600px"></iframe>
            </section>

        </section>
    @else
        <p>Такой книги не существует</p>
    @endif

</main>
