<div class="flex gap-2 flex-1 md:flex-col">
    @if($collection['status']->order() == 2)
        @if (!$currentVote)
            <p class="w-1/2 md:w-full">В рамках конкурса в данном сборнике сейчас идет голосование
                за лучшего
                автора.
                Вы можете ознакомиться со всеми авторами в предварительном варианте и проголосовать
                за лучшего на ваш взгляд.
                <b>Автор не может проголосовать сам за себя, поэтому Вы не видите себя в списке.</b>
            </p>
            <div class="flex flex-col gap-2 w-1/2 md:w-full">
                <div class="flex flex-col gap-4 max-h-96 overflow-auto">
                    @foreach($participations as $participation)
                        @if ($participation['id'] != $participationId)
                            <div class="flex gap-2 items-center">
                                <input type="radio" wire:model.live="participationChosen"
                                       name="participationChosen"
                                       id="participationChosen_{{$participation['id']}}"
                                       value="{{$participation['id']}}">
                                <label
                                    for="participationChosen_{{$participation['id']}}">{{$participation['author_name']}}</label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <x-ui.button color="yellow" wire:click="save()">Подтвердить выбор</x-ui.button>
            </div>
        @else
            <p>Вы успешно отдали свой голос за {{$authorChosen}}.</p>
            <x-ui.link-simple wire:click="confirmDeleteVote">Отменить выбор</x-ui.link-simple>
        @endif
    @else
        <div class="flex flex-col gap-4">
            @if($userWinnerPlace)
                <p><b>Поздравляем! 🎉</b><br>
                    <b>Вы заняли {{$userWinnerPlace}} место и тем самым стали призером сборника
                    '{{$collection['title']}}'.</b>
                    Сейчас нам очень важно получить от вас краткую информацию, как об авторе. Мы
                    добавляем информацию о призерах на первых страницах нашего сборника.
                    Пожалуйста, отправьте ее в чате сверху текущей страницы. Информация должна
                    содержать не более 1 страницы А4 12 шрифтом.
                </p>
            @endif
            <p>Спасибо всем авторам, принявшим участие в голосовании!
                Основываясь только на голосах от самих авторов, мы рады представить 3-х призёров
                сборника:</p>
            <div class="flex flex-col">
                @foreach($collection->winner_participations_ordered as $key => $winner)
                    <p>{{$key+1}} Место: {{$winner['author_name']}}</p>
                @endforeach
            </div>
            <p>За вас проголосовало авторов: {{$userVotes}}</p>
            @if($currentVote)
                <p>Вы успешно отдали свой голос за {{$authorChosen}}.</p>
            @endif
        </div>
    @endif
</div>
