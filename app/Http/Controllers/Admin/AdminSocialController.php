<?php

namespace App\Http\Controllers\Admin;

use Akaunting\Apexcharts\Charts;
use App\Charts\LikesChart;
use App\Http\Controllers\Controller;
use App\Http\Livewire\WorkComments;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\work_comment;
use App\Models\work_like;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSocialController extends Controller
{

    public function chats_users()
    {

        $users = User::orderBy('created_at', 'desc')->get();
        $chats = Chat::where('user_created', '<>', 2)->where('user_to', '<>', 2)->orderBy('chat_status_id', 'asc')->orderBy('updated_at', 'desc')->with('message')->paginate(50);
        return view('admin.social.chats_users', [
            'users' => $users,
            'chats' => $chats
        ]);
    }

    public function admin_social_comments()
    {
        $comments = work_comment::orderBy('created_at')->paginate(50);
        return view('admin.social.comments', [
            'comments' => $comments
        ]);
    }

    public function admin_social_likes()
    {

$char_query = "select DATE_FORMAT(dt.datetime, '%Y-%m-%d') AS date,  works_cnt, works_comments_cnt, works_likes_cnt from dc_date dt

left join (
    select DATE_FORMAT(created_at, '%Y-%m-%d') as created_at, count(id) as works_cnt from works
    group by DATE_FORMAT(created_at, '%Y-%m-%d')
) w on DATE_FORMAT(dt.datetime, '%Y-%m-%d') = w.created_at

left join (
    select DATE_FORMAT(created_at, '%Y-%m-%d') as created_at, count(id) as works_comments_cnt from work_comments
    group by DATE_FORMAT(created_at, '%Y-%m-%d')
) wc on DATE_FORMAT(dt.datetime, '%Y-%m-%d') = wc.created_at

left join (
    select DATE_FORMAT(created_at, '%Y-%m-%d') as created_at, count(id) as works_likes_cnt from work_likes
    group by DATE_FORMAT(created_at, '%Y-%m-%d')
) wl on DATE_FORMAT(dt.datetime, '%Y-%m-%d') = wl.created_at

WHERE dt.datetime between '2022-12-10' and sysdate()";

        $char_data = collect(DB::select(DB::raw($char_query)));

//        dd(collect($char_data));



        $chart = (new LarapexChart)->setTitle('Bookfirst stat')
            ->lineChart()
            ->setTitle('Лайков:')
            ->addData('Работ', $char_data->pluck('works_cnt')->toArray())
            ->addData('Комментариев', $char_data->pluck('works_comments_cnt')->toArray())
            ->addData('Лайков', $char_data->pluck('works_likes_cnt')->toArray())
            ->setXAxis($char_data->pluck('date')->toArray());


        $likes = work_like::orderBy('created_at')->paginate(50);
        return view('admin.social.likes', [
            'likes' => $likes,
            'chart' => $chart
        ]);
    }


    public function admin_stat()
    {

        $query_likes_and_comments = "select DATE_FORMAT(dt.datetime, '%d.%m') AS date,  works_comments_cnt, works_likes_cnt from dc_date dt

                left join (
                    select DATE_FORMAT(created_at, '%Y-%m-%d') as created_at, count(id) as works_comments_cnt from work_comments
                    group by DATE_FORMAT(created_at, '%Y-%m-%d')
                ) wc on DATE_FORMAT(dt.datetime, '%Y-%m-%d') = wc.created_at

                left join (
                    select DATE_FORMAT(created_at, '%Y-%m-%d') as created_at, count(id) as works_likes_cnt from work_likes
                    group by DATE_FORMAT(created_at, '%Y-%m-%d')
                ) wl on DATE_FORMAT(dt.datetime, '%Y-%m-%d') = wl.created_at

                WHERE dt.datetime between DATE_SUB(sysdate(), INTERVAL 30 DAY) and sysdate()
                order by dt.datetime asc";

//        dd($query_likes_and_comments);
        $data_likes_and_comments = collect(DB::select($query_likes_and_comments));
//dd($data_likes_and_comments);

        $query_works_uploaded = "
            select DATE_FORMAT(dt.datetime, '%d.%m') AS date,
            sum(case when upload_type = 'вручную' then works_cnt end) as works_cnt_manual,
            sum(case when upload_type = 'из документа' then works_cnt end) as works_cnt_doc,
            sum(case when upload_type is null then works_cnt end) as works_cnt_other,
            sum(works_cnt) as works_cnt_total
            from dc_date dt

                left join (
                    select DATE_FORMAT(created_at, '%Y-%m-%d') as created_at, upload_type, count(id) as works_cnt from works
                    group by DATE_FORMAT(created_at, '%Y-%m-%d'), upload_type
                ) w on DATE_FORMAT(dt.datetime, '%Y-%m-%d') = w.created_at
                WHERE dt.datetime between DATE_SUB(sysdate(), INTERVAL 30 DAY) and sysdate()
                group by  DATE_FORMAT(dt.datetime, '%d.%m')
                order by dt.datetime asc";



        $data_works_uploaded = collect(DB::raw($query_works_uploaded));

        $query_new_users = "select DATE_FORMAT(dt.datetime, '%d.%m') AS date,  cnt_users from dc_date dt

                left join (
                    select DATE_FORMAT(u.created_at, '%Y-%m-%d') AS created_at, count(*) as cnt_users from users u
                    WHERE u.created_at between DATE_SUB(sysdate(), INTERVAL 30 DAY) and sysdate()
                    group by DATE_FORMAT(u.created_at, '%d.%m')
                ) wc on DATE_FORMAT(dt.datetime, '%Y-%m-%d') = wc.created_at


                WHERE dt.datetime between DATE_SUB(sysdate(), INTERVAL 30 DAY) and sysdate()
                order by dt.datetime asc";


        $data_new_users = collect(DB::raw($query_new_users));




        return view('admin.stat', [
            'data_likes_and_comments' => $data_likes_and_comments,
            'data_works_uploaded' => $data_works_uploaded,
            'data_new_users' => $data_new_users
        ]);

    }

}
