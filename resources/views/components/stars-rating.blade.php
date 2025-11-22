<div class="star-rating">
    @if($input_rating == 0)
        {{-- Если это звезды для выбора--}}
            @for ($i = 1; $i <= 5; $i++)
                <input class="radio-input" type="radio" wire:model="{{$model}}" id="star{{$i}}" name="star-input"
                       value="{{6 - $i}}"/>
                <label class="radio-label" for="star{{$i}}" title="{{$i}} stars">{{$i}} stars</label>
            @endfor
    @else
        {{-- Если это звезды для показа--}}
        <div class="static">
            @for ($i = 1; $i <= 5; $i++)
                <span class="@if($input_rating >= $i) active @endif"></span>
            @endfor
        </div>
    @endif
</div>
