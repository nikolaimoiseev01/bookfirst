@props([
    'color' => 'green',
])

@php
    $classes = match($color) {
        'white' => 'block text-white border text-xl border-white min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:text-white',
        'green' => 'block border text-xl min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:text-white',
        'yellow' => 'block border text-xl border-brown-400 min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:bg-brown-400 hover:text-white',
    };
@endphp

<a :class="$store.global.social ? ' border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white' : ' border-green-500 text-green-500 hover:bg-green-500 hover:text-white'"  wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
