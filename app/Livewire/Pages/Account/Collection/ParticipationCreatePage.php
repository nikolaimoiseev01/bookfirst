<?php

namespace App\Livewire\Pages\Account\Collection;

use App\Models\Collection\Collection;
use Livewire\Component;

class ParticipationCreatePage extends Component
{

    public $collection;


    public function render()
    {
        return view('livewire.pages.account.collection.participation-create-page')->layout('layouts.account');
    }

    public function mount($collection_id)
    {
        $this->collection = Collection::where('id', $collection_id)->first();
    }

}
