<?php

namespace App\Livewire\Pages\Account\PurchasePrints;

use App\Enums\PrintOrderTypeEnums;
use App\Models\PrintOrder\PrintOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchasePrintsPage extends Component
{
    public $printOrders;

    public function render()
    {
        $this->printOrders = PrintOrder::query()->where('user_id', Auth::user()->id)->whereIn('type', [
            PrintOrderTypeEnums::COLLECTION_ONLY,
            PrintOrderTypeEnums::OWN_BOOK_ONLY,
        ])->orderByDesc('created_at')->with('model')->get();
        return view('livewire.pages.account.purchase-prints.purchase-prints-page')->layout('layouts.account');
    }
}
