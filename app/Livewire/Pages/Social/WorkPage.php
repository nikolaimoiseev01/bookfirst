<?php

namespace App\Livewire\Pages\Social;

use App\Models\Work\Work;
use App\View\Components\Ui\WorkLikesButton;
use Livewire\Component;

class WorkPage extends Component
{
    public $work;
    public $workLikesCount;
    public $userHasLike;

    public function render()
    {
        return view('livewire.pages.social.work-page');
    }

    public function mount($id)
    {
        $this->work = Work::where('id', $id)->with('user')->withCount('workLikes')->first();
        $this->workLikesCount = $this->work['work_likes_count'];
        $this->userHasLike = auth()->id() ? $this->work->workLikes()->where('user_id', auth()->user()->id)->exists() : false;
    }
}
