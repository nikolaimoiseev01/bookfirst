<?php

namespace App\Livewire\Pages\Account\Collection;

use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ParticipationsPage extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.pages.account.collection.participations-page', [
            'participations' => Auth::user()->participations()->with('collection')->with('collection.media')->simplePaginate(4)
        ])->layout('layouts.account');
    }

//    public function mount() {
//        $this->participations = ;
//    }
}
