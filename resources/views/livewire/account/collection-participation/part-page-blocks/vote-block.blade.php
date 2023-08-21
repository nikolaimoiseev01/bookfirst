<div class="part vote_block_wrap">
    {!! $page_style !!}
    <div class="line"></div>
    {!! $status_icon !!}
    <div class="block_wrap container">
        <div class=hero_wrap>
            <h2>{{$page_title}}</h2>
        </div>
        <div class="info_wrap">
            @if ($collection['col_status_id'] === 1) {{-- Если сборник еще не завелся --}}
            <p class="no-access">
                В данном сборнике проводится конкурс на лучшего автора!
                Когда наступит этап предварительной проверки,
                у Вас будет возможность прочитать все блоки сборника и проголосовать за лучшего на Ваш взгляд.
            </p>

            @elseif ($collection['col_status_id'] >= 2 && ($participation['pat_status_id'] <= 2 || $participation['pat_status_id'] === 99)) {{-- Если сборник поехал, но клиент не прошел оплату --}}
            <p class="no-access">Из-за отсутствия оплаты Вы не были включены в список участников.</p>

            @elseif($collection['col_status_id'] >= 2) {{-- Если сборник в предварительной проверке --}}

            @if(count($winners) === 0) {{-- Если еще не выбраны победители--}}

            @if(!($voted_to['user_id'] ?? null)) {{-- Если еще не проголосовал --}}
            <div class="voting-left">
                <p>В рамках конкурса в данном сборнике сейчас идет голосование за лучшего автора.
                    Вы можете ознакомиться со всеми авторами в предварительном варианте и проголосовать за
                    лучшего на
                    ваш взгляд.
                    <b>Автор не может проголосовать сам за себя, поэтому Вы не видите себя в списке.</b>
                </p>
            </div>
            <div class="voting-right">
                <h2>Выберите лучшего автора:</h2>

                <div class="author_list_wrap">
                    @foreach($participants as $participant)
                        <div class="check-block">
                            <label for="vote_for_{{$participant['user_id']}}">
                                <p>
                                    {{prefer_name($participant['name'],  $participant['surname'], $participant['nickname'])}}
                                </p>
                            </label>
                            <input wire:model="vote_to" value="{{$participant['user_id']}}"
                                   name="vote_to"
                                   class="{{$color}}"
                                   id="vote_for_{{$participant['user_id']}}"
                                   type="radio">
                        </div>
                    @endforeach
                </div>

                <a wire:click.prevent="make_vote()"
                   class="button show_preloader_on_click {{$color}}">Проголосовать</a>
            </div>

            @else {{-- Если уже проголосовал --}}
            <p>Вы отдали свой голос за:
                {{prefer_name($voted_to['name'],  $voted_to['surname'], $voted_to['nickname'])}}
            </p>
            <a wire:click.prevent="delete_vote()"
               class="button show_preloader_on_click {{$color}}">Изменить</a>
            @endif
            @else {{-- Если уже выбраны победители --}}
            <div class="winners_wrap">
                <p>Спасибо всем авторам, принявшим участие в голосовании!
                    Основываясь только на голосах от самих авторов, мы рады представить 3-х призёров
                    сборника:
                </p>
                <div class="winners">
                    @foreach($winners as $winner)
                        <p><span>{{ $loop->index + 1}}-е место:</span>
                            {{prefer_name($winner->participation['name'],  $winner->participation['surname'], $winner->participation['nickname'])}}
                        </p>
                    @endforeach
                </div>
                <p style="margin-top: 10px;">{{$participation['name']}}, спасибо Вам за участие!
                    @if($voted_to ?? null)
                        Вы отдали свой голос за автора
                        {{prefer_name($voted_to['name'],  $voted_to['surname'], $voted_to['nickname'])}}.
                    @endif
                    В этом сборнике за Вас проголосовало человек: <span
                        style="color:#47AF98">{{$votes_for_me + 2}}</span></p>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
