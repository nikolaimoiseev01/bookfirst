<?php

namespace App\Http\Livewire;

use App\Models\user_subscription;
use App\Models\Work;
use App\Models\work_like;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LikeButton extends Component
{
    public $work_id;
    public $like_check;
    public $like_number;
    public $user_id_of_work;

    public function render()
    {
        if (Auth::user()->id ?? 0 > 0) {
            $this->like_check = work_like::
                where('user_id', Auth::user()->id)
                    ->where('work_id', $this->work_id)
                    ->value('id') > 0;
        } else {
            $this->like_check = false;
        }



        $this->like_number = work_like::where('work_id', $this->work_id)->count();
        return view('livewire.like-button', [
            'work_id' => $this->work_id,
            'like_check' => $this->like_check,
            'like_number' => $this->like_number
        ]);
    }

    public function new_like()
    {

        if (Auth::user()->id ?? 0 > 0) {
            $this->user_id_of_work = Work::where('id', $this->work_id)->first('user_id')['user_id'];

            if(!$this->like_check) {
                $new_work_like = new work_like();
                $new_work_like->user_id = Auth::user()->id;
                $new_work_like->work_id = $this->work_id;
                $new_work_like->user_id_of_work = $this->user_id_of_work;
                $new_work_like->save();
            }
            else {
                work_like::
                where('user_id', Auth::user()->id)
                    ->where('work_id', $this->work_id)
                    ->delete();
            }

            $this->like_check = work_like::
                where('user_id', Auth::user()->id)
                    ->where('work_id', $this->work_id)
                    ->value('id') > 0;


            $this->like_number = work_like::where('work_id', $this->work_id)->count();

        }
    }
}
