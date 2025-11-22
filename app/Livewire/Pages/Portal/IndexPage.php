<?php

namespace App\Livewire\Pages\Portal;

use App\Enums\CollectionStatusEnums;
use App\Models\Collection\Collection;
use Livewire\Component;

class IndexPage extends Component
{
    public $collections_actual;
    public $ownBookApp;

    public function render()
    {
        $this->collections_actual = Collection::where('status', CollectionStatusEnums::APPS_IN_PROGRESS)->with('media')->get();

        $ownBookApp = collect([
            ''
        ]);
        return view('livewire.pages.portal.index-page');
    }
}
