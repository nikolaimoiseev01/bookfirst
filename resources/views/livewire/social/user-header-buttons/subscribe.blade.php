<div class="subscribe_wrap">

    <div
        wire:click.prevent="subscribe()"
        class="not_sub_yet log_check
        @if(\Illuminate\Support\Facades\Auth::user()->id ?? 0 === 0) not_signed @endif
        @if(!$subscription_check) active @endif
        @if((\Illuminate\Support\Facades\Auth::user()->id ?? 0) === $user_to_subscribe) self_sub @endif">
        <span class="material-symbols-outlined heart">favorite</span>
        <p>Подписаться</p>
    </div>

    <div class="sub_yet @if($subscription_check) active @endif">
        <p>Подписан</p>
        <span
            wire:click.prevent="unsubscribe()"
            class="material-symbols-outlined close tooltip" title="Отписаться">close</span>
    </div>


    @push('page-js')

        <script>
            $('.not_sub_yet, .sub_yet').not('.self_sub').not('.not_signed').on('click', function () {
                $('.not_sub_yet').toggleClass('active')
                $('.sub_yet').toggleClass('active')
            })
        </script>
    @endpush
</div>
