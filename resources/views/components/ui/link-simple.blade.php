@props([
    'color' => 'green',
    'isLivewire' => true
])

@php
    $classes = 'flex items-center text-2xl font-light cursor-pointer ' . (($color == 'yellow')
                ? 'text-brown-400 hover:text-brown-500'
                : 'text-green-500 0 hover:text-green-600');
@endphp

<a @if($isLivewire)
       wire:navigate
   @endif :class="$store.global.social ? ' !text-blue-500 !hover:text-blue-600' : ''" {{ $attributes->merge(['class' => 'block w-fit ' . $classes])}}>
    {{$slot}}
</a>
