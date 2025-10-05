<?php

namespace App\Livewire\Pages\Account\Work;

use Livewire\Component;

class WorkEditPage extends Component
{
    public $work_id;

    public function render()
    {
        return view('livewire.pages.account.work.work-edit-page')->layout('layouts.account');
    }

    public function mount($work_id) {
        $this->work_id = $work_id;
    }
}
