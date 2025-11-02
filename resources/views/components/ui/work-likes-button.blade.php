@props([
    'workLikesCount',
    'workLikesCount'
])

<div
    class="flex items-center gap-1 select-none"
    x-data="{ liked: @entangle('userHasLike'), animate: false }"
>
    <button
        data-check-logged
        @click="
            $wire.addRemoveLike();
            animate = true;
            setTimeout(() => animate = false, 400);
        "
        class="relative w-7 h-7 flex items-center justify-center"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            class="w-6 h-6 transition-all duration-300 ease-out"
            :class="[
                liked ? 'fill-red-500' : 'fill-gray-400',
                animate ? 'scale-125' : 'scale-100'
            ]"
        >
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                     2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81
                     14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4
                     6.86-8.55 11.54L12 21.35z"/>
        </svg>

        <!-- Вспышка при клике -->
        <div
            x-show="animate"
            x-transition.scale.70.duration.200ms
            class="absolute inset-0 bg-red-400/40 rounded-full"
        ></div>
    </button>

    <span class="text-xl text-dark-350 transition-colors duration-300"
          :class="liked ? 'text-red-500' : 'text-dark-350'">
        {{ $workLikesCount }}
    </span>
</div>
