<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Work;
use App\Models\work_comment;
use App\Models\work_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocialController extends Controller
{
    public function index(Request $request)
    {
        $last_works = Work::orderBy('id', 'desc')->take(14)->get();
        return view('social.index', [
            'last_works' => $last_works
        ]);

    }

    public function user_page(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        return view('social.user_page', [
            'user' => $user
        ]);

    }

    public function work_page(Request $request)
    {
        $work = Work::where('id', $request->work_id)->first();
        $user = User::where('id', $work['user_id'])->first();

        $comments = work_comment::where('work_id', $request->work_id)
            ->wherenull('reply_to_comment_id')
            ->orderBy('created_at', 'desc')
            ->get();

        $replies_check = DB::table('work_comments as a')

            ->select('a.parent_comment_id', DB::raw('count(a.id) AS replies_to_comment'))
            ->groupBy('a.parent_comment_id')
            ->where('a.work_id', $request->work_id)
            ->whereNotNull('a.reply_to_comment_id')
            ->get();



        $replies = DB::table('work_comments as a')
            ->where('a.work_id', $request->work_id)
            ->leftJoin('work_comments as b', function ($join) {
                $join->on('a.reply_to_comment_id', '=', 'b.id');
            })
            ->leftJoin('users as u', function ($join) {
                $join->on('a.user_id', '=', 'u.id');
            })
            ->select('a.*', 'u.avatar', 'u.name', 'u.surname', 'u.nickname', 'b.text as reply_to_text')
            ->whereNotNull('a.reply_to_comment_id')
            ->get();

        return view('social.work_page', [
            'work' => $work,
            'user' => $user,
            'comments' => $comments,
            'replies' => $replies,
            'replies_check' => $replies_check
        ]);

    }
}
