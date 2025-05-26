<?php

namespace App\Livewire\Pages\Portal;

use App\Models\Collection\Collection;
use Livewire\Component;

class IndexPage extends Component
{
    public $collections_actual;

    public function render()
    {
        $this->collections_actual = Collection::where('collection_status_id', 1)->get();
        return view('livewire.pages.portal.index-page');
    }
}
