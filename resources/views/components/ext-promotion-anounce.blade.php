<div class="hidden alert_block_wrap ext_promotion_alert_block_wrap container">
    <div class="alert_wrap">
        <p class="desktop"><b>У нас появилась новая услуга: продвижение вашей странички в других соц. сетях!</b></p>
        <p class="desktop">Например, на сайте stihi.ru мы привлекаем <span style="color: #47AF98">до 400 новых реальных читателей</span> вашего творчества в день!</p>
        <p class="mobile"><b>Новая услуга: продвижение!</b></p>
        <a href="{{route('ext_promotion')}}" class="link">Подробнее</a>
        <a id="close_ext_promotion_alert" title="Закрыть навсегда" class="tooltip close_icon">
            <img src="/img/cancel.svg">
        </a>
    </div>

</div>

@push('page-js')
    <script>
        anim_duration = 1000

        cur_url = window.location.href

        function show_ext_promotion_alert(anim_duration) {
            $('.ext_promotion_alert_block_wrap').addClass('active')
            $(".ext_promotion_alert_block_wrap").animate({
                bottom: "10px",
            }, anim_duration);

        }

        function hide_ext_promotion_alert(anim_duration) {
            $(".ext_promotion_alert_block_wrap").animate({
                bottom: "-500px",
            }, anim_duration);
            setTimeout(function(anim_duration) {
                $('.ext_promotion_alert_block_wrap').addClass('hidden')
            }, anim_duration)
        }

        var cookie_show = getCookie('show_ext_promotion_alert');

        if(cookie_show === 'no' || cur_url.includes('ext_promotion')) {
            $('.ext_promotion_alert_block_wrap').addClass('hidden')
        } else {
            setTimeout(function(anim_duration) {
                show_ext_promotion_alert(anim_duration)
            }, 3000)

            $('#close_ext_promotion_alert').on('click', function() {
                setCookie('show_ext_promotion_alert','no',1);
                hide_ext_promotion_alert(anim_duration)
            })
        }
    </script>
@endpush
