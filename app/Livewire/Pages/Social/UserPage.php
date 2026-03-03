<?php

namespace App\Livewire\Pages\Social;

use App\Enums\OwnBookStatusEnums;
use App\Models\User\User;
use App\Models\Work\Work;
use Livewire\Component;

class UserPage extends Component
{
    public $user;
    public $userStat;
    public $randomWorks;

    public function render()
    {
        return view('livewire.pages.social.user-page');
    }

    public function mount($id)
    {
        $this->user = User::where('id', $id)->with(
            ['ownBooks' => function ($query) {
                $query->where('status_general', OwnBookStatusEnums::DONE);
            },
                'media', 'awards', 'awards.awardType', 'awards.awardType.media'])->withCount('works', 'awards', 'subscribers', 'subscribedToUsers')->first();
        $this->randomWorks = Work::inRandomOrder()->with('media', 'user')->limit(5)->get();
    }
}
