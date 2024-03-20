<?php

namespace App\Http\Livewire\Account\ExtPromotion\PartPageBlocks;

use App\Charts\ExtPromotionStat;
use App\Service\ExtPromotionStatUpdateService;
use App\Service\PartPageBlockStatus;
use Livewire\Component;

class ProcessBlock extends Component
{
    public $ext_promotion;
    public $status_color;
    public $status_icon;
    public $page_style;

    private $chart;

    public $chart_container;
    public $chart_script;
    public $chart_cdn;

    protected $listeners = ['refreshProcessBlock' => '$refresh'];

    public function render()
    {
        return view('livewire.account.ext-promotion.part-page-blocks.process-block');
    }

    public function mount(PartPageBlockStatus $status, ExtPromotionStat $chart)
    {

        $this->chart = $chart->build($this->ext_promotion);

        $this->chart_container = str($this->chart->container());
        $this->chart_script = str($this->chart->script());
        $this->chart_cdn = str($this->chart->cdn());


        if ($this->ext_promotion['ext_promotion_status_id'] < 4) {
            $this->status_color = 'grey';
        } elseif ($this->ext_promotion['ext_promotion_status_id'] === 4) {
            $this->status_color = 'blue';
        } elseif ($this->ext_promotion['ext_promotion_status_id'] === 9) {
            $this->status_color = 'green';
        } else {
            $this->status_color = 'grey';
        };

        $found_status = $status->get_status('.process_block_wrap', $this->status_color);

        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];
    }

    public function update_stat(ExtPromotionStatUpdateService $stat, ExtPromotionStat $chart)
    {
        if ($stat->check_max($this->ext_promotion)) {
            $stat->add_new_time($this->ext_promotion);

            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Успешно добавили текущие данные!');
            session()->flash('alert_text', 'Следующий раз данные обновятся автоматически в полночь. При обновлении вручную должен быть перерыв более часа.');
            return redirect()->to(url()->previous());

        } else {

            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'error');
            session()->flash('alert_title', 'Упс!');
            session()->flash('alert_text', 'При обновлении вручную должен быть перерыв более часа, пожалуйста, подождите.');
            return redirect()->to(url()->previous());

        }
    }
}
