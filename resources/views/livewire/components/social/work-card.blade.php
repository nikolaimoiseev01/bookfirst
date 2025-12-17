<div
    x-data="{ expanded: false }"
    class="flex flex-col container p-4 min-w-full w-full"
>
    <div class="flex gap-4 items-center mb-4 md:flex-col md:justify-center">
        <div class="flex gap-4">
            <img src="{{ getUserAvatar($work->user) }}" class="w-10 h-10 rounded-xl" alt="">
            <x-ui.link-simple href="{{route('social.user', $work['user_id'])}}"
                              class="text-blue-500 text-3xl font-medium">
                {{ getUserName($work->user) }}:
            </x-ui.link-simple>
        </div>
        <x-ui.link-simple href="{{route('social.work', $work['id'])}}"
                          class="text-3xl">{{ \Illuminate\Support\Str::limit($work['title'], 25) }}</x-ui.link-simple>
        <span
            class="text-dark-350 ml-auto md:!mx-auto">{{ $work->workType['name'] }} / {{ $work->workTopic['name'] }}</span>
    </div>

    <div class="relative mb-4">
        <p
            x-ref="text"
            x-bind:class="expanded ? 'line-clamp-none' : 'line-clamp-10'"
            class="transition-all duration-500 ease-in-out text-dark-600"
        >
        {!! nl2br(e($work['text'])) !!}
        </p>

        <div class="mt-2">
            <button
                x-show="$refs.text.scrollHeight > $refs.text.clientHeight || expanded"
                @click="expanded = !expanded"
                class="text-blue-500 hover:underline text-xl font-light"
                x-text="expanded ? 'Скрыть' : 'Показать больше'"
            ></button>
        </div>
    </div>

    <div class="flex gap-4">
        <x-ui.work-likes-button :workLikesCount="$workLikesCount" :userHasLike="$userHasLike"/>
        <x-ui.link-simple href="{{route('social.work', $work['id'])}}"
                          class="flex gap-1 items-center">
            <x-bi-chat class="w-5 h-5 fill-dark-350"/>
            <span class="text-xl text-dark-350">
                        {{ $work->comments_count }}
                    </span>
        </x-ui.link-simple>
        <div
            x-data="{ open: false }"
            class="relative flex items-center"
            @mouseenter="open = true"
            @mouseleave="open = false"
        >
            <x-bi-share class="w-5 h-5 fill-dark-350 cursor-pointer"/>

            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-2"
                class="absolute left-full ml-2 bg-white border border-dark-350 rounded-md px-2 py-1 text-dark-600 shadow-sm"
            >
                <a> <img src="/fixed/icons/vk.svg" class="w-6 h-auto max-w-max" alt=""> </a>
            </div>
        </div>
        <span class="text-dark-350 ml-auto font-light">{{ $workCreatedAt }}</span>
    </div>
</div>
