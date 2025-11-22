<div wire:ignore>
    <div class="flex flex-col gap-2">
        <p>Дата начала продвижения: {{formatDate($extPromotion['start_date'], 'j F H:i')}}</p>
        <p>Дата окончания
            продвижения: {{formatDate($extPromotion['start_date'], 'j F', $extPromotion['days'])}}
            21:00 МСК</p>
        <p>Статистика читателей на сайте {{$extPromotion['site']}}</p>
        @if($extPromotion == \App\Enums\ExtPromotionStatusEnums::IN_PROGRESS)
            <x-ui.link-simple wire:click="addStat()">Обновить статистику</x-ui.link-simple>
        @endif
    </div>
    {!! $chart->container() !!}
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
</div>
