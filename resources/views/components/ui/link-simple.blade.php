@props([
    'social',
])

@php
    $classes = 'text-2xl font-light cursor-pointer ' . (($social ?? false)
                ? 'block font-light'
                : 'block font-light');
@endphp

<a wire:navigate :class="$store.global.social ? ' text-blue-500 hover:text-blue-600' : ' text-green-500 0 hover:text-green-600'" {{ $attributes->merge(['class' => $classes])}}>
   {{$slot}}
</a>
