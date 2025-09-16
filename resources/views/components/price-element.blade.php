@props([
    'price' => 0,
    'label' => 'Стоимость',
    'direction' => 'column',
    'plus' => false,
    'color' => 'gray'
])

<div
    {{ $attributes->class([
        'flex items-center',
        'gap-2' => $direction === 'row',
        'flex-col' => $direction === 'column',
        '!text-dark-200' => $color === 'gray',
        '!text-green-400 !text-4xl' => $color === 'green',
    ]) }}
>
    <span
        @class([
        'text text-2xl font-light',
        'order-2' => $direction === 'column',
    ])>{{$label}}</span>

    <span @class([
        "font-bold flex items-center gap-2",
        'text-5xl' => $color === 'gray',
        'text-6xl' => $color === 'green',
    ])>
        @if($plus)<span class="text-3xl font-light">+</span>@endif
        {{makeMoney($price)}}
    </span>

    <span @class([
        'text text-2xl font-light',
        'hidden' => $direction === 'column',
    ])>руб.</span>
</div>
