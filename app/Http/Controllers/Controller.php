<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\own_book;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function site_search(Request $request)
    {


        $search_input = $request->search_input;

        if (strlen($search_input) > 3) {
            $works = Work::Where('title', 'like', '%' . $search_input . '%')->get();
            $users = DB::table('users as u')
                ->leftJoin('user_subscriptions as us', 'u.id', '=', 'us.subscribed_to_user_id')
                ->leftJoin('work_comments as wc', 'u.id', '=', 'wc.user_id')
                ->leftJoin('work_likes as wl', 'u.id', '=', 'wl.user_id_of_work')
                ->leftJoin('works as w', 'u.id', '=', 'w.user_id')
                ->select('u.id', 'u.name', 'u.surname', 'u.nickname', 'u.avatar', 'u.avatar_cropped',
                    DB::raw('count(distinct us.id) AS cnt_user_subs'),
                    DB::raw('count(distinct wc.id) AS cnt_user_comments'),
                    DB::raw('count(distinct wl.id) AS cnt_user_likes'),
                    DB::raw('count(distinct w.id) AS cnt_user_works')
                )
                ->where('u.id', '<>', 2)
                ->where('name', 'like', '%' . $search_input . '%')
                ->orWhere('surname', 'like', '%' . $search_input . '%')
                ->orWHere('nickname', 'like', '%' . $search_input . '%')
                ->groupBy('u.id')
                ->paginate(10);

            $own_books = own_book::where('own_book_status_id', 9)
                ->where(function ($query) use ($search_input) {
                    $query->where('author', 'like', '%' . $search_input . '%')
                        ->orWhere('title', 'like', '%' . $search_input . '%');
                })->orderBy('id', 'desc')->paginate(5);

            $collections = Collection::where('title', 'like', '%' . $search_input . '%')->orderBy('created_at', 'desc')->paginate(5);


            return view('site_search', [
                'works' => $works,
                'users' => $users,
                'search_input' => $search_input,
                'own_books' => $own_books,
                'collections' => $collections,
            ]);
        }
    }

}
