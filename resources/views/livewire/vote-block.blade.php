<div class="voting-wrap">
    @if($voted_to['user_id'] ?? 0 <> 0)
        <p>Вы отдали свой голос за:
            @if($voted_to['nickname'])
                {{$voted_to['nickname']}}!
            @else
                {{Str::limit($voted_to['name'] . ' ' . $voted_to['surname'], 30)}}!
            @endif
        </p>
        <a wire:click.prevent="delete_vote()" style="margin-left: auto; padding: 0 20px; box-shadow: none" class="button">Изменить</a>
    @else
        <div class="voting-left">
            <p>В рамках конкурса в данном сборнике сейчас идет голосование за лучшего автора.
                Вы можете ознакомиться со всеми авторами в предвариетльном варианте и проголосовать за лучшего на ваш
                взгляд.</p>
        </div>

        <div class="voting-right">
            <h2>Выберите лучшего автора:</h2>


            <div style="    height: 100%; overflow: auto; padding-bottom: 10px;">
                @foreach($participants as $participant)
                    <div style="margin-top: 10px;" class="check-block">
                        <label for="vote_for_{{$participant['user_id']}}">
                            <p style="margin:0;">
                                @if($participant['nickname'])
                                    {{$participant['nickname']}}
                                @else
                                    {{Str::limit($participant['name'] . ' ' . $participant['surname'], 30)}}
                                @endif
                            </p>
                        </label>
                        <input wire:model="vote_to" value="{{$participant['user_id']}}" style="margin-left: 10px;"
                               name="vote_to"
                               id="vote_for_{{$participant['user_id']}}"
                               type="radio">
                    </div>
                @endforeach
            </div>

            <a wire:click.prevent="make_vote()" style="text-align: center" class="button">Проголосовать</a>
        </div>
    @endif
</div>