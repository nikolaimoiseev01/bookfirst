<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\almost_complete_action;
use App\Models\Collection;
use App\Models\Col_status;
use App\Models\own_book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Fpdi;

class PortalController extends Controller
{
    public function index()
    {

        $own_books = own_book::where('own_book_status_id', 9)->where('promo_price', '>', 1000)->orderBy('id', 'desc')->get();
        $collections = Collection::where('col_status_id', '1')->orderBy('id', 'desc')->get();
        $col_statuses = Col_status::orderBY('id')->get();
        return view('portal.index', [
            'collections' => $collections,
            'col_statuses' => $col_statuses,
            'own_books' => $own_books
        ]);
    }

    public function help_account()
    {
        return view('portal.help_account');
    }

    public function help_collection()
    {
        return view('portal.help_collection');
    }

    public function help_own_book()
    {
        return view('portal.help_own_book');
    }

    public function help_ext_promotion()
    {
        return view('portal.help_ext_promotion');
    }


    public function old_collections()
    {
        $collections = Collection::orderBY('id', 'desc')->where('col_status_id', 9)->orderBy('id', 'desc')->paginate(9);
        return view('portal.old_collections', [
            'collections' => $collections,
            'collection_input_search' => 'no_search',
        ]);
    }

    public function actual_collections()
    {
        $collections = Collection::orderBY('id', 'desc')->where('col_status_id', 1)->paginate(9);
        return view('portal.actual_collections', [
            'collections' => $collections,
        ]);
    }


    public function own_book_page()
    {
        return view('portal.own_book_page', [
        ]);
    }

    public function own_book_user_page($own_book_id)
    {
        $own_book = own_book::where('id', $own_book_id)->first();


        return view('portal.own_book_user_page', [
            'own_book' => $own_book
        ]);
    }

    public function about()
    {
        return view('portal.about', [
        ]);
    }


    public function own_books()
    {
        $own_books = own_book::where('own_book_status_id', 9)->orWhere('old_author_email', "<>", "")->orderBY('id', 'desc')->paginate(9);
        return view('portal.own_books', [
            'own_books' => $own_books,
            'own_book_input_search' => 'no_search',
        ]);
    }

    public function own_book_search($own_book_input_search)
    {

        $own_books = own_book::where('title', 'like', '%' . $own_book_input_search . '%')->orderBy('id', 'desc')->paginate(9);
        session(['previous-url' => request()->url()]);
        return view('portal.own_books', [
            'own_books' => $own_books,
            'own_book_input_search' => $own_book_input_search,
        ]);
    }

    public function ext_promotion()
    {
        return view('portal.ext_promotion');
    }





}
