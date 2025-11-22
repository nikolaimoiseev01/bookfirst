<?php

namespace App\Http\Livewire\Social;

use App\Models\award;
use App\Models\User;
use App\Models\user_subscription;
use App\Models\Work;
use App\Models\work_comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WorkComments extends Component
{
    public $work;
    public $user;
    public $comments;
    public $replies_check;
    public $replies;
    public $comment_amt = 10;
    public $all_comments_amt;


    public function render()
    {
        return view('livewire.social.work-comments', [
            'comments' => $this->comments,
            'replies' => $this->replies,
            'replies_check' => $this->replies_check
        ]);
    }

    public function mount($work_id)
    {
        $this->work = Work::where('id', $work_id)->first();
        $this->user = User::where('id', $this->work['user_id'])->first();

        $this->all_comments_amt = work_comment::where('work_id', $work_id)
            ->wherenull('reply_to_comment_id')
            ->orderBy('created_at', 'desc')
            ->count();

        $this->comments = work_comment::where('work_id', $work_id)
            ->wherenull('reply_to_comment_id')
            ->orderBy('created_at', 'desc')
            ->take($this->comment_amt)
            ->get();

        $this->replies_check = DB::table('work_comments as a')

            ->select('a.parent_comment_id', DB::raw('count(a.id) AS replies_to_comment'))
            ->groupBy('a.parent_comment_id')
            ->where('a.work_id', $work_id)
            ->whereNotNull('a.reply_to_comment_id')
            ->get();


        $this->replies = DB::table('work_comments as a')
            ->where('a.work_id', $work_id)
            ->leftJoin('work_comments as b', function ($join) {
                $join->on('a.reply_to_comment_id', '=', 'b.id');
            })
            ->leftJoin('users as u', function ($join) {
                $join->on('a.user_id', '=', 'u.id');
            })
            ->select('a.*', 'u.avatar', 'u.name', 'u.surname', 'u.nickname', 'b.text as reply_to_text')
            ->whereNotNull('a.reply_to_comment_id')
            ->get();

    }

    public function create_comment($formData)
    {


        if ($formData['comment_text'] == '' || $formData['comment_text'] === null) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => 'Комментарий не может быть пустым.']);
        } else {


            $work_id = $formData['work_id'];
            $comment_text = $formData['comment_text'];
            $reply_to_comment_id = $formData['reply_to_comment_id'] ?? null;
            $reply_to_user_id = $formData['reply_to_user_id'] ?? null;
            $parent_comment_id = $formData['parent_comment_id'] ?? null;


            $new_comment = new work_comment();
            $new_comment->work_id = $work_id;
            $new_comment->text = $comment_text;
            $new_comment->user_id = Auth::user()->id;
            $new_comment->reply_to_comment_id = $reply_to_comment_id;
            $new_comment->reply_to_user_id = $reply_to_user_id;
            $new_comment->parent_comment_id = $parent_comment_id;
            $new_comment->save();

            // Заново грузим все ответы

            $this->comments = work_comment::where('work_id', $work_id)
                ->wherenull('reply_to_comment_id')
                ->orderBy('created_at', 'desc')
                ->take($this->comment_amt)
                ->get();

            $this->replies_check = DB::table('work_comments as a')

                ->select('a.parent_comment_id', DB::raw('count(a.id) AS replies_to_comment'))
                ->groupBy('a.parent_comment_id')
                ->where('a.work_id', $work_id)
                ->whereNotNull('a.reply_to_comment_id')
                ->get();


            $this->replies = DB::table('work_comments as a')
                ->where('a.work_id', $work_id)
                ->leftJoin('work_comments as b', function ($join) {
                    $join->on('a.reply_to_comment_id', '=', 'b.id');
                })
                ->leftJoin('users as u', function ($join) {
                    $join->on('a.user_id', '=', 'u.id');
                })
                ->select('a.*', 'u.avatar', 'u.name', 'u.surname', 'u.nickname', 'b.text as reply_to_text')
                ->whereNotNull('a.reply_to_comment_id')
                ->get();

            $this->all_comments_amt = work_comment::where('work_id', $work_id)
                ->wherenull('reply_to_comment_id')
                ->orderBy('created_at', 'desc')
                ->count();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Отлично!',
                'text' => 'Комментарий успешно добавлен!']);


        }

    }


    public function load_more_comments() {
        $this->comment_amt += 10;
        $this->comments = work_comment::where('work_id', $this->work['id'])
            ->wherenull('reply_to_comment_id')
            ->orderBy('created_at', 'desc')
            ->take($this->comment_amt)
            ->get();

        $this->replies_check = DB::table('work_comments as a')

            ->select('a.parent_comment_id', DB::raw('count(a.id) AS replies_to_comment'))
            ->groupBy('a.parent_comment_id')
            ->where('a.work_id', $this->work['id'])
            ->whereNotNull('a.reply_to_comment_id')
            ->get();


        $this->replies = DB::table('work_comments as a')
            ->where('a.work_id', $this->work['id'])
            ->leftJoin('work_comments as b', function ($join) {
                $join->on('a.reply_to_comment_id', '=', 'b.id');
            })
            ->leftJoin('users as u', function ($join) {
                $join->on('a.user_id', '=', 'u.id');
            })
            ->select('a.*', 'u.avatar', 'u.name', 'u.surname', 'u.nickname', 'b.text as reply_to_text')
            ->whereNotNull('a.reply_to_comment_id')
            ->get();

    }

}
