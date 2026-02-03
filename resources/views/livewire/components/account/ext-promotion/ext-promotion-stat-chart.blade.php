<div wire:ignore>
    <div class="flex flex-col gap-2">
        <p>Дата начала продвижения: {{formatDate($extPromotion['start_date'], 'j F H:i')}}</p>
        <p>Дата окончания
            продвижения: {{formatDate($extPromotion['start_date'], 'j F', $extPromotion['days'])}}
            21:00 МСК</p>
        <p>Статистика читателей на сайте {{$extPromotion['site']}}</p>
        @if($extPromotion->status == \App\Enums\ExtPromotionStatusEnums::IN_PROGRESS)
            <x-ui.link-simple wire:click="addStat()">Обновить статистику</x-ui.link-simple>
        @endif
    </div>

    {!! $chart->container() !!}


    @push('scripts')
        <script>
            (() => {
                const CDN = @json($chart->cdn());
                let initialized = false;

                function loadScriptOnce(src) {
                    return new Promise((resolve, reject) => {
                        if (document.querySelector(`script[src="${src}"]`)) {
                            return resolve();
                        }

                        const s = document.createElement('script');
                        s.src = src;
                        s.async = true;
                        s.onload = resolve;
                        s.onerror = reject;
                        document.head.appendChild(s);
                    });
                }

                async function initChart() {
                    if (initialized) return;
                    initialized = true;

                    await loadScriptOnce(CDN);

                    // выполняем JS чарта без <script>
                    {!! trim(
                        preg_replace('/<script[^>]*>|<\/script>/', '', $chart->script())
                    ) !!}
                }

                document.addEventListener('livewire:navigated', initChart);
                document.addEventListener('DOMContentLoaded', initChart);
            })();
        </script>
    @endpush


</div>
