@props([
    'price' => 0,
    'label' => 'Стоимость',
    'direction' => 'column',
    'color' => 'gray'
])

<div
    @class([
        'flex items-center',
        'gap-2' => $direction === 'row',
        'flex-col' => $direction === 'column',
        '!text-dark-200' => $color === 'gray',
        '!text-green-400' => $color === 'green',
    ])
>
    <span
        @class([
        'text text-2xl font-light',
        'order-2' => $direction === 'column',
    ])>{{$label}}</span>

    <span class="price text-5xl font-bold">{{$price}}</span>

    <span @class([
        'text text-2xl font-light',
        'hidden' => $direction === 'column',
    ])>руб.</span>
</div>
