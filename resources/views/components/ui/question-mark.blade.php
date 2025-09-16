@props(['direction' => 'top'])
<div {{ $attributes->merge(['class' => 'relative inline-block group h-fit w-fit']) }}
     x-data="{ open: false }"
     @mouseenter="if(window.innerWidth >= 768) open = true"
     @mouseleave="if(window.innerWidth >= 768) open = false"
     @click="if(window.innerWidth < 768) open = !open"
     @click.away="if(window.innerWidth < 768) open = false"
>
    <!-- Иконка -->
    <x-bi-question-circle class="text-green-500 w-6 h-auto cursor-pointer"/>

    <!-- Тултип -->
    <x-ui.tooltip
        direction="{{$direction}}"
        class="opacity-0 invisible group-hover:visible group-hover:opacity-100 transition"
    >
        {{$slot}}
    </x-ui.tooltip>
</div>
