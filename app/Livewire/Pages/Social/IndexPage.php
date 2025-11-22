<?php

namespace App\Livewire\Pages\Social;

use App\Models\User\User;
use App\Models\Work\Work;
use Livewire\Component;

class IndexPage extends Component
{
    public $randomAuthors;
    public function render()
    {
        $this->randomAuthors = User::inRandomOrder()->with('media')->withCount('ownBooks', 'works', 'subscribers', 'participations')->limit(20)->get();
        return view('livewire.pages.social.index-page');
    }

    public function updateAuthors() {

    }
}
