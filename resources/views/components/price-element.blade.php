@props([
    'price' => 0,
    'oldPrice' => null,
    'label' => 'Стоимость',
    'direction' => 'column',
    'plus' => false,
    'color' => 'gray',
    'bigElement' => false
])

<div
    {{ $attributes->class([
        'flex items-center',
        'gap-2' => $direction === 'row',
        'flex-col' => $direction === 'column',
        '!text-dark-200' => $color === 'gray',
        '!text-green-400 !text-4xl' => $color === 'green',
        '!text-dark-100 !text-4xl' => $color === 'bright',
        '!text-brown-400 !text-4xl' => $color === 'yellow',
    ]) }}
>
    <span
        @class([
        'text text-2xl font-light',
        'order-2' => $direction === 'column',
    ])>{{$label}}</span>

    <div class="flex flex-col">
        @if($oldPrice)
            <span class="text-center line-through font-normal text-2xl">{{$oldPrice}}</span>
        @endif
        <span @class([
        "font-bold flex items-center gap-2 no-wrap",
        'text-5xl' => $color === 'gray' || $color === 'bright' || $color === 'green',
         'text-6xl' => $bigElement,
    ])>
        @if($plus)
                <span class="text-3xl font-light">+</span>
            @endif
            {{makeMoney($price)}}
    </span>
    </div>


    <span @class([
        'text text-2xl font-light',
        'hidden' => $direction === 'column',
    ])>руб.</span>
</div>
