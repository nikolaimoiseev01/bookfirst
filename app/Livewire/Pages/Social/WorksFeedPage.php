<?php

namespace App\Livewire\Pages\Social;

use App\Models\Work\Work;
use Livewire\Component;

class WorksFeedPage extends Component
{
    public $totalWorksCount;
    public function render()
    {
        $this->totalWorksCount = Work::count();
        return view('livewire.pages.social.works-feed-page');
    }
}
