<div wire:key="like_for_work_{{$work_id}}" class="like_button_wrap">


    <span id="like_{{$work_id}}"
       wire:click.prevent="new_like()"
       class="log_check @if ($like_check) active @endif fa-regular like_icon fa-heart">
    </span>

    <p>{{$like_number}}</p>

</div>

