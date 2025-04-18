@props([
    'social',
])

@php
    $classes = ($social ?? false)
                ? 'block text-blue-500 font-light'
                : 'block text-green-500 font-light';
@endphp

<a {{ $attributes->merge(['type' => 'submit', 'class' => $classes])}}>
   {{$slot}}
</a>
