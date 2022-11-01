<div>
    <div class="like_block">

        <i id="like_{{$work_id}}"
           wire:ignore href="."
           wire:click.prevent="new_like()"
           class="log_check @if ($like_check)like_icon__active @endif fa-regular like_icon fa-heart"
           style="--fa-animation-duration: 0.1s;">

        </i>

        <span >{{$like_number}}</span>
    </div>
</div>
