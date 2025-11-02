<?php

namespace App\Livewire\Pages\Social;

use App\Models\User\User;
use Livewire\Component;

class UserPage extends Component
{
    public $user;
    public $userStat;

    public function render()
    {
        return view('livewire.pages.social.user-page');
    }

    public function mount($id)
    {
        $this->user = User::where('id', $id)->first();
    }
}
