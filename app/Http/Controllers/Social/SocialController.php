<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\award;
use App\Models\User;
use App\Models\user_subscription;
use App\Models\UserWallet;
use App\Models\Work;
use App\Models\work_comment;
use App\Models\work_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SocialController extends Controller
{
    public function index(Request $request)
    {
        $last_work_first = Work::orderBy('id', 'desc')->first();


        $last_works = Work::where('id', '<', $last_work_first['id'])
            ->orderBy('id', 'desc')
            ->take(20)
            ->orderBy('created_at', 'desc')
            ->get();

        $users_sql = "select
                      `users`.`id`,
                      `users`.`name`,
                      `users`.`surname`,
                      `users`.`nickname`,
                      `users`.`avatar`,
                      `users`.`avatar_cropped`,
                      (
                        select
                          count(*)
                        from
                          `user_subscriptions`
                        where
                          `users`.`id` = `user_subscriptions`.`user_id`
                      ) as `user_subscription_count`,
                      (
                        select
                          count(*)
                        from
                          `work_comments`
                        where
                          `users`.`id` = `work_comments`.`user_id`
                      ) as `work_comment_count`,
                      (
                        select
                          count(*)
                        from
                          `work_likes`
                        where
                          `users`.`id` = `work_likes`.`user_id`
                      ) as `work_likes_count`,
                      (
                        select
                          count(*)
                        from
                          `works`
                        where
                          `users`.`id` = `works`.`user_id`
                      ) as `work_count`
                    from
                    (select * from users order by rand() limit 12) users";

        $users = db::select($users_sql);


        return view('social.index', [
            'last_works' => $last_works,
            'last_work_first' => $last_work_first,
            'users' => $users,
        ]);

    }

    public function user_page(Request $request)
    {
        $user_id = intval($request->user_id);
        $user = User::where('id', $user_id)->first();

        $works = Work::where('user_id', $user_id)->get();

        $last_other_works = Work::inRandomOrder()->limit(5)->get();

//        $user_wallet = UserWallet::where('user_id', Auth::user()->id)->first();
        return view('social.user_page', [
            'user' => $user,
            'works' => $works,
            'last_other_works' => $last_other_works
        ]);

    }

    public function work_page(Request $request)
    {
        $work = Work::where('id', $request->work_id)->first();
        $user = User::where('id', $work['user_id'])->first();

        $user_stat_readers = user_subscription::where('subscribed_to_user_id', $request->user_id)->get();
        $user_stat_reads = user_subscription::where('user_id', $request->user_id)->get();
        $works = Work::where('user_id', $request->user_id)->get();
        $awards = award::where('user_id', $request->user_id)->get();

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
            'replies_check' => $replies_check,
            'awards' => $awards,
            'user_stat_readers' => $user_stat_readers,
            'user_stat_reads' => $user_stat_reads,
            'works' => $works,
        ]);

    }

    protected $withCount = ['relation'];

    public function all_works_feed()
    {
        $works = Work::where('user_id', '<>', 2)->withcount('work_like')->get();
        return view('social.all_works_feed', [
            'works' => $works,
        ]);
    }


}
