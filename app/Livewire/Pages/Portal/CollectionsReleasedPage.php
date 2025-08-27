<?php

namespace App\Livewire\Pages\Portal;

use App\Models\Collection\Collection;
use Livewire\Component;

class CollectionsReleasedPage extends Component
{
    public $collections;

    public function render()
    {
        $this->collections = Collection::where('collection_status_id', '<>', 1)->get();
        return view('livewire.pages.portal.collections-released-page');
    }
}
