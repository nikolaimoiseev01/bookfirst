<?php

namespace App\Livewire\Pages\Account;

use App\Models\Promocode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class FriendInvitePage extends Component
{
    public $userPromocode;
    public $user;
    public $sumToCheckout;

    public function render()
    {
        $this->userPromocode = Promocode::where('user_id', $this->user->id)->with('stats')->first();
        $this->sumToCheckout = 400 * $this->userPromocode?->stats->where('is_paid', true)->count() ?? 0;
        return view('livewire.pages.account.friend-invite-page')->layout('layouts.account');
    }

    public function mount() {
        $this->user = Auth::user();
    }

    public function getPromocode() {
        $rnd = Str::random(3);
        Promocode::create([
            'name' => 'FRIEND_' . $rnd . '_' . $this->user->id,
            'discount' => 25,
            'user_id' => $this->user->id,
            'group' => Promocode::TYPE_FRIEND_INVITE
        ]);
        $this->userPromocode = $this->user->promocode;
        $this->dispatch('swal', type: 'success', title: 'Успешно!', text: 'Вы выпустили свой промокод. Делитесь им и получайте вознаграждение :)');
    }
}
