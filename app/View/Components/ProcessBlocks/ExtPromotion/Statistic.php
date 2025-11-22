<?php

namespace App\View\Components\ProcessBlocks\ExtPromotion;

use App\Enums\ExtPromotionStatusEnums;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use marineusde\LarapexCharts\Charts\AreaChart;
use marineusde\LarapexCharts\Options\XAxisOption;

class Statistic extends Component
{
    public $extPromotion;
    public $blockColor;

    public function __construct($extPromotion)
    {
        $this->extPromotion = $extPromotion;
        match ($this->extPromotion['status']) {
            ExtPromotionStatusEnums::REVIEW, ExtPromotionStatusEnums::NOT_ACTUAL, ExtPromotionStatusEnums::PAYMENT_REQUIRED, ExtPromotionStatusEnums::START_REQUIRED => $this->blockColor = 'gray',
            ExtPromotionStatusEnums::IN_PROGRESS => $this->blockColor = 'yellow',
            ExtPromotionStatusEnums::DONE => $this->blockColor = 'green'
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $chartColor = match ($this->blockColor) {
            'yellow' => '#FFA500',
            'green' => '#47af98',
            default => '#ffffff'
        };
        $parsedReaders = $this->extPromotion->parsedReaders;
        $data = $parsedReaders->pluck('readers_num')->toArray();
        $xAxis = [];
        foreach ($parsedReaders as $el) {
            $date = formatDate($el['checked_at'], 'j F H:i');
            $xAxis[] = $date;
        }
        $chart = (new AreaChart)
            ->setColors([$chartColor])
            ->setXAxisOption(new XAxisOption($xAxis))
            ->setDataset([
                [
                    'name' => 'Количество читателей',
                    'data' => $data
                ]
            ]);
        return view('components.process-blocks.ext-promotion.statistic', ['chart' => $chart]);
    }
}
