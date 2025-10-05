<?php

namespace App\Livewire\Pages\Account\Work;

use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use App\Models\Work\Work;
use App\Models\Work\WorkTopic;
use App\Models\Work\WorkType;
use App\Services\WorkStatService;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class WorkCreateManualPage extends Component
{

    public function render()
    {
        return view('livewire.pages.account.work.work-create-manual-page')->layout('layouts.account');
    }

    public function mount()
    {

    }
}
