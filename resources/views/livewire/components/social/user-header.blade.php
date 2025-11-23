<section x-data="{userHasAwards: @js($userHasAwards)}" class="flex gap-8 mb-8 md:flex-col">
    <div class="flex gap-4" :class="userHasAwards ? 'flex-col w-1/2 md:w-full' : 'w-full md:flex-col'">
        <div :class="userHasAwards ? '' : 'w-1/2 md:w-full'" class="container flex gap-8 p-4 md:min-w-full" x-data="{userOnline: @js($userOnline)}">
            <img src="{{getUserAvatar($user)}}" class="rounded w-20 h-20" alt="">
            <div class="flex flex-col justify-between md:gap-4">
                <div class="flex gap-4 items-center flex-wrap">
                    <x-ui.link-simple href="{{route('social.user', $user['id'])}}"
                                      class="text-4xl font-normal">{{getUserName($user)}}</x-ui.link-simple>
                    <span
                        x-text="userOnline ? 'В сети' : 'Не в сети'"
                        :class="userOnline ? 'border-green-500 text-green-500' : 'border-dark-350 text-dark-350'"
                        class="border rounded-2xl px-4 py-0 text-nowrap">
                </span>
                </div>
                <div x-data="{userIsSubscribed: @js($userIsSubscribed)}" class="flex gap-4 text-xl flex-wrap">
                    <div
                        @if((auth()->user()->id ?? 0 > 0) && auth()->user()->id ?? 0 != $user['id'])
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

                    <a @if((auth()->user()->id ?? 0 > 0) && auth()->user()->id ?? 0 != $user['id'])
                           wire:click="sendMessage"
                       @endif data-check-logged class="flex gap-2 hover:text-blue-500 transition">
                        <x-bi-pencil class="w-5 h-auto fill-blue-300"/>
                        Написать
                    </a>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-4 flex-1 md:grid-cols-2 sm:!grid-cols-2">
            @foreach($userStat as $stat)
                <div
                    class="container flex flex-col justify-center items-center text-center flex-1 relative py-2">
                    <span class="text-blue-500 font-semibold text-3xl">{{$stat['value']}}</span>
                    <span class="text-dark-350 text-xl font-light">{{$stat['title']}}</span>
                    <x-dynamic-component
                        :component="$stat['icon']"
                        class="w-5 h-auto absolute top-4 right-4 fill-dark-350"
                    />
                </div>
            @endforeach
        </div>
    </div>
    <div x-show="userHasAwards" class="flex flex-col flex-1 container p-4 gap-4 md:min-w-full">
        <div class="flex gap-2 items-baseline">
            <h3 class="text-4xl font-medium text-blue-500">Награды
                <span class="text-3xl font-normal">({{$user->awards->count()}})</span>
            </h3>
            @if($user->awards->count() > 4)
                <x-ui.modal name="awardsModal">
                    <div class="flex flex-col gap-4 p-4" >
                        <h3 class="text-3xl font-normal mb-4">
                            Все награды пользователя
                            <span class="text-3xl font-normal">({{$user->awards->count()}})</span>
                        </h3>
                        <div class="flex flex-wrap gap-4">
                            @foreach($user->awards as $award)
                                <div class="flex flex-col gap-2 justify-center items-center text-center">
                                    <img src="{{$award->awardType->getFirstMediaUrl('image')}}" class="w-16" alt="">
                                    <p class="text-xl">{{$award->awardType['name']}}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-ui.modal>
                <p class="text-blue-500" @click="$dispatch('open-modal', 'awardsModal')">Показать все</p>
            @endif
        </div>
        <div class="flex gap-4 justify-evenly">
            @foreach($user->awards->take(4) as $award)
                <div class="flex flex-col gap-2 justify-center items-center text-center">
                    <img src="{{$award->awardType->getFirstMediaUrl('image')}}" class="w-16" alt="">
                    <p class="text-xl">{{$award->awardType['name']}}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
