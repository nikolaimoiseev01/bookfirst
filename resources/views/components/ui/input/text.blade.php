@props([
    'label' => null,
    'color' => 'green',
    'id' => null,
])

@php
    $inputId = $id ?? $attributes->get('wire:model');
@endphp

<div
    {{ $attributes->merge([
    'class' =>
        'flex flex-col gap-1 w-full' ]) }}>
    @if($label)
        <label for="{{ $attributes->get('wire:model') }}" class="text-xl font-light">
            {{ $label }}
        </label>
    @endif

    <input
        {{$attributes->thatStartWith('wire:model')}}
        {{$attributes->thatStartWith('x-model')}}
        {{$attributes->thatStartWith('placeholder')}}
        autocomplete="{{$inputId}}"
        {{$attributes->thatStartWith('type')}}
        type="text"
        id="{{ $inputId }}"
        name="{{ $attributes->get('wire:model') }}"
        @class([
        'border-green-500' => $color === 'green',
        'border-brown-400' => $color === 'yellow',
        ])
        class="placeholder:text-dark-200 border rounded-md px-3 py-2 w-full focus:outline-none  {{($errors->has($attributes->get('wire:model')) ? 'border-red-500' : 'border-gray-300')}}"
    />

{{--    @error($attributes->get('wire:model'))--}}
{{--    <p class="text-sm text-red-500">{{ $message }}</p>--}}
{{--    @enderror--}}
</div>
