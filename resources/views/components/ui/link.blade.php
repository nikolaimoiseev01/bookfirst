@props([
    'color' => 'green',
    'navigate' => true,
])

@php
    $classes = match($color) {
        'white' => 'block text-white border h-fit text-xl border-white min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:bg-white hover:text-green-500',
        'green' => 'block border text-xl min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition text-green-500 border-green-500 hover:bg-green-500 hover:text-white',
        'yellow' => 'block border text-xl border-brown-400 min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition text-brown-400 hover:bg-brown-400 hover:text-white',
    };
@endphp

<a
    :class="[
        // первое условие
        ($store.global.social && '{{$color}}' != 'white')
            ? '!border-blue-500 !text-blue-500 !hover:bg-blue-500 hover:text-white'
            : '',

        // второе условие
        ($store.global.social && '{{$color}}' == 'white')
            ? 'hover:!text-blue-500'
            : ''
    ]"
   @if($navigate)  wire:navigate @endif
    {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
