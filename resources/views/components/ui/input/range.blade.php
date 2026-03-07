@props([
    'model' => '',
    'min' => 1
])
<div class="flex flex-1 gap-2 items-center">
    <input wire:model.live="{{$model}}" type="number" id="{{$model}}" min="{{$min}}" max="300">
    <input wire:model.live="{{$model}}" type="range" id="{{$model}}" min="{{$min}}" max="300">
</div>
