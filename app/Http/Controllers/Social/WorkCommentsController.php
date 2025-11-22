<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\work_comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkCommentsController extends Controller
{
    public function create_comment(Request $request)
    {
        if ($request->comment_text == '' || $request->comment_text === null) {
            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'error');
            session()->flash('alert_title', 'Что-то пошло не так!');
            session()->flash('alert_text', 'Текст комментария не может быть пустым.');
            return redirect()->back();
        } else {


            $work_id = $request->work_id;
            $comment_text = $request->comment_text;
            $reply_to_comment_id = $request->reply_to_comment_id ?? null;
            $reply_to_user_id = $request->reply_to_user_id ?? null;
            $parent_comment_id = $request->parent_comment_id ?? null;


            $new_comment = new work_comment();
            $new_comment->work_id = $work_id;
            $new_comment->text = $comment_text;
            $new_comment->user_id = Auth::user()->id;
            $new_comment->reply_to_comment_id = $reply_to_comment_id;
            $new_comment->reply_to_user_id = $reply_to_user_id;
            $new_comment->parent_comment_id = $parent_comment_id;
            $new_comment->save();

            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Успешно!');
            session()->flash('alert_text', 'Комментарий добавлен.');
            return redirect()->back();
        }

    }
}
