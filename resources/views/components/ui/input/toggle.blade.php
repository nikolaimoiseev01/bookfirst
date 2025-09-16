@props([
    'options' => [],   // ['yes' => 'Да', 'no' => 'Нет'] или больше
    'disabled' => false,
    'boolean' => false,
    'model' => '',
    'size' => 'md',    // sm | md | lg
])
<div class="flex rounded-2xl border border-green-500 w-fit overflow-hidden min-h-fit">
    @foreach($options as $value => $label)
        <label
            for="{{ $model }}_{{ $value }}"
            class="cursor-pointer px-4 text-xl rounded-xl
               has-checked:bg-green-500
               has-checked:text-white
               transition"
        >
            <input
                @if($boolean)
                    wire:model.boolean="{{$model}}"
                @else
                    wire:model="{{$model}}"
                @endif
                x-model="{{$model}}"
                class="hidden"
                type="radio"
                id="{{ $model }}_{{ $value }}"
                value="{{ $value }}"
                name="{{ $model }}"
            >
            {{ $label }}
        </label>
    @endforeach
</div>
