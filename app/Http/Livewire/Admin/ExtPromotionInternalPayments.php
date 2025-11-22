<?php

namespace App\Http\Livewire\Admin;

use App\Models\ext_promotion;
use App\Models\ext_promotion_internal_payments;
use Livewire\Component;

class ExtPromotionInternalPayments extends Component
{
    public $ext_promotions_to_pay;
    public $ext_promotions_to_pay_sum;
    public $payments;

    public function render()
    {
        $this->ext_promotions_to_pay = ext_promotion::whereIn('ext_promotion_status_id', [4, 9])
            ->where('executor_got_payment', False)
            ->get();
        $this->ext_promotions_to_pay_sum = $this->ext_promotions_to_pay->sum('price_executor');
        $this->payments = ext_promotion_internal_payments::all();

        return view('livewire.admin.ext-promotion-internal-payments');
    }

    public function mount()
    {

    }

    public function make_payment()
    {
//        dd($this->ext_promotions_to_pay_sum);
        $array_paid_for = $this->ext_promotions_to_pay->pluck('id')->toJson();

        ext_promotion_internal_payments::create([
            'paid_for' => $array_paid_for,
            'amount' => $this->ext_promotions_to_pay_sum
        ]);

        foreach ($this->ext_promotions_to_pay as $ext_promotion) {
            $ext_promotion->update([
                'executor_got_payment' => True
            ]);
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Отлично!',
            'text' => "Успешно перевели все {$this->ext_promotions_to_pay_sum}!",
        ]);
    }


}
