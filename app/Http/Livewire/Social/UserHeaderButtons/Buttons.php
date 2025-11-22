<?php

namespace App\Http\Livewire\Social\UserHeaderButtons;

use Livewire\Component;

class Buttons extends Component
{
    public $user;

    public function render()
    {
        return view('livewire.social.user-header-buttons.buttons');
    }

    public function mount($user)
    {
        $this->user = $user;
    }
}
