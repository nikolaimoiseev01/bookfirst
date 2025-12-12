@props([
    'color' => 'green',
])

@php
    $classes = match($color) {
        'white' => 'block text-white border text-xl border-white min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:bg-white hover:text-green-500 active:bg-white active:text-green-500',
        'green' => 'block text-green-500 border text-xl border-green-500 min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:bg-green-500 hover:text-white active:bg-green-500 active:text-white',
        'yellow' => 'block text-brown-400 border text-xl border-brown-400 min-w-max flex gap-2 items-center justify-center rounded-lg py-1 px-8 cursor-pointer transition hover:bg-brown-400 hover:text-white active:bg-brown-400 active:text-white'
    };
@endphp

<style>
    .submitButton.loading {
        svg {
            display: block !important;
        }
        span {
            display: none !important;
        }
    }
</style>
<button {{ $attributes->merge(['class' => 'submitButton ' . $classes]) }} wire:ignore>
    <span wire:loading.remove>{{ $slot }}</span>
    <x-ui.spinner wire:loading class="w-7 h-7 !fill-{{$color}}"/>
</button>
