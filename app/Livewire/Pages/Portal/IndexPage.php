<?php

namespace App\Livewire\Pages\Portal;

use App\Enums\CollectionStatusEnums;
use App\Models\Collection\Collection;
use Livewire\Component;

class IndexPage extends Component
{
    public $collections_actual;
    public $ownBookApp;
    public $women;

    public function render()
    {
        $this->women = [
            'welcome' => '/fixed/mascots/' . (config('app.winter_mode') ? 'woman_welcome_winter.svg' : 'woman_welcome.svg'),
            'sitting' => '/fixed/mascots/' . (config('app.winter_mode') ? 'woman_sitting_winter.svg' : 'woman_sitting.svg')
        ];

        $this->collections_actual = Collection::where('status', CollectionStatusEnums::APPS_IN_PROGRESS)->with('media')->get();

        return view('livewire.pages.portal.index-page');
    }
}
