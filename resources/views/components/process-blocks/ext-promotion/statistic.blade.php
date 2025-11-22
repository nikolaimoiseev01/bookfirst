<x-process-blocks.template status="{{$blockColor}}" title="Процесс продвижения">
    <div class="p-4">
        @if($extPromotion['status']->order() < 4)
            <p class="text-gray-400">Продвижение еще не началось. Как только начнется, здесь будет
                статистика процесса</p>
        @elseif($extPromotion['status'] == \App\Enums\ExtPromotionStatusEnums::IN_PROGRESS || $extPromotion['status'] == \App\Enums\ExtPromotionStatusEnums::DONE)
            <livewire:components.account.ext-promotion.ext-promotion-stat-chart :extPromotion="$extPromotion" :blockColor="$blockColor"/>
        @endif
    </div>
</x-process-blocks.template>
