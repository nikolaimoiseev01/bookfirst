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

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function site_search (Request $request)
    {

       $search_input =  $request->search_input;

       if (strlen($search_input) > 3) {
           $works = Work::Where('title', 'like', '%' . $search_input . '%')->get();
           $users = User::where('name', 'like', '%' . $search_input . '%')
               ->orWhere('surname', 'like', '%' . $search_input . '%')
               ->orWHere('nickname', 'like', '%' . $search_input . '%')
               ->paginate(10);

           $own_books = own_book::
           whereRelation('user', 'name', 'like', '%' . $search_input . '%')
               ->orwhereRelation('user', 'surname', 'like', '%' . $search_input . '%')
               ->orwhereRelation('user', 'nickname', 'like', '%' . $search_input . '%')
               ->orwhere('title', 'like', '%' . $search_input . '%')
               ->orderBy('created_at', 'desc')
               ->paginate(5);

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
