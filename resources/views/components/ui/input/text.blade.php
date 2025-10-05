@props([
    'label' => null, // подпись над полем
])

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
        type="text"
        id="{{ $attributes->get('wire:model') }}"
        name="{{ $attributes->get('wire:model') }}"
        class="border rounded-md px-3 py-2 w-full focus:outline-none  {{($errors->has($attributes->get('wire:model')) ? 'border-red-500' : 'border-gray-300')}}"
    />

    @error($attributes->get('wire:model'))
    <p class="text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
