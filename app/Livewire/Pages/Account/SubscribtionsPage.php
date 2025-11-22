<?php

namespace App\Livewire\Pages\Account;

use App\Models\User\UserXUserSubscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscribtionsPage extends Component
{
    public $favAuthors;
    public function render()
    {
        return view('livewire.pages.account.subscribtions-page')->layout('layouts.account');
    }

    public function mount() {
        $this->favAuthors = Auth::user()->subscribedToUsers()->withCount('ownBooks', 'works', 'subscribers', 'participations')->get();
    }
}
