<?php

namespace App\Livewire\Pages\Portal;

use App\Enums\CollectionStatusEnums;
use App\Models\Collection\Collection;
use Livewire\Component;

class CollectionsActualPage extends Component
{
    public $collections;
    public function render()
    {
        $this->collections = Collection::where('status', CollectionStatusEnums::APPS_IN_PROGRESS)->get();
        return view('livewire.pages.portal.collections-actual-page');
    }
}
