<section class="flex gap-8 mb-8">
    <div class="container flex gap-8 p-4 w-1/2">
        <img src="{{getUserAvatar($user)}}" class="rounded w-20 h-20" alt="">
        <div class="flex flex-col justify-between">
            <div class="flex gap-2 items-center">
                <x-ui.link-simple href="{{route('social.user', $user['id'])}}"
                                  class="text-4xl font-normal">{{getUserName($user)}}</x-ui.link-simple>
                <span
                    class="border text-dark-350 border-dark-350 rounded-2xl px-4 py-0 text-nowrap">Не в сети</span>
            </div>
            <div x-data="{userIsSubscribed: @js($userIsSubscribed)}" class="flex gap-4 text-xl">
                <div @if(auth()->user()->id != $user['id'])
                         @click="userIsSubscribed = !userIsSubscribed"
                     @endif class="flex gap-2">
                    <x-bi-heart-fill x-show="userIsSubscribed" class="w-5 h-auto fill-red-300"/>
                    <x-bi-heart x-show="!userIsSubscribed" class="w-5 h-auto fill-red-300"/>
                    <a wire:click="subscribe"
                       :class="userIsSubscribed ? 'text-red-300' : ''"
                       data-check-logged
                       class=" hover:text-red-300 transition"
                       x-text="userIsSubscribed ? 'Отписаться' : 'Подписаться'"
                    >
                    </a>
                </div>

                <a wire:click="sendMessage" class="flex gap-2 hover:text-blue-500 transition">
                    <x-bi-pencil class="w-5 h-auto fill-blue-300"/>
                    Написать
                </a>
                {{--                <a class="flex gap-2">--}}
                {{--                    <x-bi-heart class="w-5 h-auto fill-red-300"/>--}}
                {{--                    Отправить донат--}}
                {{--                </a>--}}
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
