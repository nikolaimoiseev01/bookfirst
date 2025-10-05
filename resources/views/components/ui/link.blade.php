@props([
    'color' => 'green',
])

@php
    $classes = match($color) {
        'white' => 'block text-white border text-xl border-white min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:bg-white hover:text-green-500',
        'green' => 'block text-green-500 border text-xl border-green-500 min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:bg-green-500 hover:text-white',
        'yellow' => 'block text-brown-400 border text-xl border-brown-400 min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:bg-brown-400 hover:text-white',
    };
@endphp

<a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
