@props([
    'social',
])

@php
    $classes = 'text-2xl font-light' . (($social ?? false)
                ? 'block text-blue-500 font-light'
                : 'block text-green-500 font-light');
@endphp

<a wire:navigate {{ $attributes->merge(['type' => 'submit', 'class' => $classes])}}>
   {{$slot}}
</a>
