<?php

namespace App\Livewire\Pages\Account\Collection;

use App\Models\Collection\Collection;
use App\Models\Work\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class ParticipationCreatePage extends Component
{
    public $collection;
    public $userWorks;
    public $selectedWorks = [];
    public $name;
    public $surname;
    public $nickname;

    public $needCheck;
    public $needPrint=true;

    public function render()
    {
        return view('livewire.pages.account.collection.participation-create-page')->layout('layouts.account');
    }

    public function mount($collection_id)
    {
        Session::put('cameFromAppUrl', URL::current());
        $this->collection = Collection::where('id', $collection_id)->first();
        $this->userWorks = Work::where('user_id', Auth::user()->id)->get(['id', 'title']);
    }

    public function saveApplication()
    {
        dd($this->selectedWorks);
    }
}
