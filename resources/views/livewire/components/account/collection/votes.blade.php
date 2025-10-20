<div class="flex flex-col gap-2 flex-1">
    @if (!$currentVote)
        <p>Сблок сверстан, голосуйте!</p>
        <div class="flex flex-col gap-4">
            @foreach($participations as $participation)
                <div class="flex gap-2 items-center">
                    <input type="radio" wire:model.live="participationChosen" name="participationChosen"
                           id="participationChosen_{{$participation['id']}}" value="{{$participation['id']}}">
                    <label for="participationChosen_{{$participation['id']}}">{{$participation['author_name']}}</label>
                </div>
            @endforeach
        </div>
        <x-ui.button color="yellow" wire:click="save()">Подтвердить выбор</x-ui.button>
    @else
        <p>Вы успешно отдали свой голос за {{$authorChosen}}.</p>
        <x-ui.link-simple wire:click="confirmDeleteVote">Отменить выбор</x-ui.link-simple>
    @endif
</div>
