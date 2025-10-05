<?php

namespace App\Livewire\Pages\Account\Collection;

use App\Models\Chat\Chat;
use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use App\Models\Collection\ParticipationWork;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Promocode;
use App\Models\Work\Work;
use App\Services\CalculateParticipationService;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use phpDocumentor\Reflection\Types\Integer;

class ParticipationCreatePage extends Component
{

    public $collection;


    public function render()
    {
        return view('livewire.pages.account.collection.participation-create-page')->layout('layouts.account');
    }

    public function mount($collection_id)
    {
        $this->collection = Collection::find($collection_id)->first();
    }

}
