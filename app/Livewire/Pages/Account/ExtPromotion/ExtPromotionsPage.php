<?php

namespace App\Livewire\Pages\Account\ExtPromotion;

use App\Models\ExtPromotion\ExtPromotion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ExtPromotionsPage extends Component
{
    public $extPromotions;

    public function render()
    {
        $this->extPromotions = ExtPromotion::where('user_id', Auth::user()->id)->get();
        return view('livewire.pages.account.ext-promotion.ext-promotions-page')->layout('layouts.account');
    }
}
