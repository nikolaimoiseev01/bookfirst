<?php

namespace App\Livewire\Pages\Account;

use App\Models\DigitalSale;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchasesPage extends Component
{
    public $purchases;
    public function render()
    {
        $this->purchases = DigitalSale::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->with(['model', 'model.media'])->get();
        return view('livewire.pages.account.purchases-page')->layout('layouts.account');
    }
}
