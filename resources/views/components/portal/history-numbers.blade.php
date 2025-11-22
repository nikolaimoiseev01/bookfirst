<div
    class="container flex justify-between w-full max-w-4xl"
    id="history-container"
>
    <div class="flex flex-col items-center m-8">
        <h3 class="text-5xl font-medium"><span id="c1">0</span>+</h3>
        <p class="text-3xl">Изданных сборников</p>
    </div>
    <div class="flex flex-col items-center m-8">
        <h3 class="text-5xl font-medium"><span id="c2">0</span>+</h3>
        <p class="text-3xl">Авторов</p>
    </div>
    <div class="flex flex-col items-center m-8">
        <h3 class="text-5xl font-medium"><span id="c3">0</span>+</h3>
        <p class="text-3xl">Изданных книг</p>
    </div>
</div>

@push('scripts')
    <script type="module">
        function animateValue(obj, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        $(window).on("scroll", function () {
            if (parseInt($('#c1').text()) < 1 && $(this).scrollTop() > $('#history-container').offset().top - $(window).height()) {
                animateValue(document.getElementById("c1"), 0, 120, 3000);
                animateValue(document.getElementById("c2"), 0, 4050, 3000);
                animateValue(document.getElementById("c3"), 0, 300, 3000);
            }

        })
   </script>
@endpush
