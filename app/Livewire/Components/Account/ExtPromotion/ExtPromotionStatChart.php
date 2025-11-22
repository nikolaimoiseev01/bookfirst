<?php

namespace App\Livewire\Components\Account\ExtPromotion;

use App\Services\ExtPromotionStatUpdateService;
use Livewire\Component;
use marineusde\LarapexCharts\Charts\AreaChart;
use marineusde\LarapexCharts\Options\XAxisOption;

class ExtPromotionStatChart extends Component
{
    public $extPromotion;
    public $blockColor;

    public function render()
    {
        $chartColor = match ($this->blockColor) {
            'yellow' => '#FFA500',
            'green' => '#47af98'
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
        return view('livewire.components.account.ext-promotion.ext-promotion-stat-chart', [
            'chart' => $chart
        ]);
    }

    public function addStat() {
        if ((new ExtPromotionStatUpdateService($this->extPromotion))->checkLastUpdate()) {
            if ((new ExtPromotionStatUpdateService($this->extPromotion))->addNewStat()) {
                $this->dispatch('swal', type: 'success', text: 'Статистика добавлена ecпешно добавлена');
                return redirect()->to(url()->previous());
            } else {
                $this->dispatch('swal', type: 'error', text: 'Что-то пошло не так! Обратитесь в поддержку');
            }
        } else {
            $this->dispatch('swal', type: 'error', text: 'При обновлении вручную должен быть перерыв более часа. Пожалуйста, подождите.');
        }
    }
}
