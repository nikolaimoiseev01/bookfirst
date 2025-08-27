@props(['direction' => 'top'])
@php
    $classes = match($direction) {
        'top' => "bottom-[110%] left-1/2 -translate-x-1/2
            after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
            after:w-0 after:h-0
            after:border-l-[6px] after:border-l-transparent
            after:border-r-[6px] after:border-r-transparent
            after:border-t-[6px] after:border-t-dark-600",
        'bottom' => "        top-[110%] left-1/2 -translate-x-1/2
            after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-top-1
            after:w-0 after:h-0
            after:border-l-[6px] after:border-l-transparent
            after:border-r-[6px] after:border-r-transparent
            after:border-b-[6px] after:border-b-dark-600",
        'left' => "top-1/2 -translate-y-1/2 right-[110%]
            after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:-right-1
            after:w-0 after:h-0
            after:border-t-[6px] after:border-t-transparent
            after:border-b-[6px] after:border-b-transparent
            after:border-l-[6px] after:border-l-dark-600",
        'right' => "top-1/2 -translate-y-1/2 left-[110%]
            after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:-left-1
            after:w-0 after:h-0
            after:border-t-[6px] after:border-t-transparent
            after:border-b-[6px] after:border-b-transparent
            after:border-r-[6px] after:border-r-dark-600",

    };
@endphp
<div {{ $attributes->merge(['class' => 'absolute bg-dark-600 text-white p-4 rounded text-center w-max max-w-60 z-20 ' . $classes]) }}
">
    {{$slot}}
</div>
