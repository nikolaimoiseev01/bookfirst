<?php

namespace App\Livewire\Pages\Social;

use App\Models\Work\Work;
use App\Models\Work\WorkComment;
use App\Models\Work\WorkLike;
use App\Traits\WithCustomValidation;
use App\View\Components\Ui\WorkLikesButton;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WorkPage extends Component
{
    use WithCustomValidation;

    public $work;
    public $workLikesCount;
    public $userHasLike;
    public $workComments;

    public $text;
    public $isSending;
    public $showAddComment = false;

    public function render()
    {
        $this->workComments = WorkComment::where('work_id', $this->work['id'])->orderBy('created_at', 'desc')->get();
        return view('livewire.pages.social.work-page');
    }

    public function rules()
    {
        return [
            'text' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'Сообщение обязательно для заполнения',
        ];
    }

    public function mount($id)
    {
        $this->work = Work::where('id', $id)
            ->with([
                'user',
                'comments.user',
            ])
            ->withCount('likes')
            ->first();
        $this->workLikesCount = $this->work['likes_count'];
        $this->userHasLike = auth()->id() ? $this->work->likes()->where('user_id', auth()->user()->id)->exists() : false;
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

    public function sendMessage()
    {
        if ($this->customValidate()) {
            WorkComment::create([
                'user_id' => Auth::user()->id,
                'work_id' => $this->work['id'],
                'text' => $this->text,
            ]);
            $this->text = null;
            $this->showAddComment = false;
        }
        $this->isSending = false;
    }
}
