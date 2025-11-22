<?php

namespace App\Livewire\Components\Social;

use App\Models\Work\WorkLike;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WorkCard extends Component
{
    public $work;
    public $workLikesCount;
    public $userHasLike;
    public $workCreatedAt;
    public function render()
    {
        return view('livewire.components.social.work-card');
    }

    public function mount($work) {
        $this->work = $work;
        $this->workLikesCount = $work['likes_count'];
        $this->userHasLike = auth()->id() ? $work->likes()->where('user_id', auth()->user()->id)->exists() : false;
        $this->workCreatedAt = Carbon::parse($this->work['created_at'])->translatedFormat('j F Y H:i');
    }

    public function addRemoveLike()
    {
        DB::transaction(function () {
            $userId = auth()->id();
            $workId = $this->work['id'];
            if ($this->userHasLike) {
                WorkLike::where([
                    'user_id' => $userId,
                    'work_id' => $workId,
                ])->delete();

                $this->userHasLike = false;
                $this->workLikesCount--;
            } else {
                WorkLike::firstOrCreate([
                    'user_id' => $userId,
                    'work_id' => $workId,
                ]);
                $this->userHasLike = true;
                $this->workLikesCount++;
            }
        });
    }
}
