@props([
    'model' => ''
])
<div class="flex flex-1 gap-2 items-center">
    <input wire:model.live="{{$model}}" type="number" id="{{$model}}" min="1" max="100">
    <input wire:model.live="{{$model}}" type="range" id="{{$model}}" min="1" max="100">
</div>
