<div>
    <div wire:key="like_for_work_{{$work_id}}" class="like_block">

        <i id="like_{{$work_id}}"
           href="."
           wire:click.prevent="new_like()"
           class="log_check @if ($like_check)like_icon__active @endif fa-regular like_icon fa-heart"
           style="color: var(--grey_font);">

        </i>

        <span >{{$like_number}}</span>
    </div>
</div>
