@props(['direction' => 'top', 'text' => ''])
<div {{ $attributes->merge(['class' => 'relative inline-block group h-fit w-fit']) }}
     x-data="{ open: false }"
     @mouseenter="if(window.innerWidth >= 768) open = true"
     @mouseleave="if(window.innerWidth >= 768) open = false"
     @click="if(window.innerWidth < 768) open = !open"
     @click.away="if(window.innerWidth < 768) open = false"
>
    <!-- Иконка -->
    {{$slot}}

    <!-- Тултип -->
    <x-ui.tooltip
        direction="{{$direction}}"
        class="opacity-0 invisible group-hover:visible group-hover:opacity-100 transition"
    >
        {!! $text !!}
    </x-ui.tooltip>
</div>
