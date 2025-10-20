<main class="flex-1">
    <section class="content flex gap-8">
        <div class="container flex gap-8 p-4 w-1/2">
            <img src="{{getAvatarUrl($user)}}" class="rounded w-20 h-20" alt="">
            <div class="flex flex-col justify-between">
                <div class="flex gap-2 items-center">
                    <h2 class="text-4xl font-normal">{{getUserName($user)}}</h2>
                    <span class="border text-dark-350 border-dark-350 rounded-2xl px-4 py-0 text-nowrap">Не в сети</span>
                </div>
                <div class="flex gap-4 text-xl">
                    <a class="flex gap-2">
                        <x-bi-heart class="w-5 h-auto fill-red-300"/>
                        Подписаться
                    </a>
                    <a class="flex gap-2">
                        <x-bi-heart class="w-5 h-auto fill-red-300"/>
                        Написать
                    </a>
                    <a class="flex gap-2">
                        <x-bi-heart class="w-5 h-auto fill-red-300"/>
                        Отправить донат
                    </a>
                </div>
            </div>
        </div>
        <div class="flex gap-4 flex-1">
            @foreach($userStat as $stat)
                <div class="container flex flex-col justify-center items-center text-center flex-1 relative">
                    <span class="text-blue-500 font-semibold text-3xl">{{$stat['value']}}</span>
                    <span class="text-dark-350 text-xl font-light">{{$stat['title']}}</span>
                    <x-bi-book-half class="w-5 h-auto absolute top-4 right-4 fill-dark-350"/>
                </div>
            @endforeach
        </div>
    </section>

    <section class="flex justify-between">
        <div class="flex flex-col">
            <div class="flex gap-4">

            </div>
        </div>
    </section>
</main>
